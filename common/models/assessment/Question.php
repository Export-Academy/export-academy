<?php



namespace common\models\assessment;

use common\models\File;
use components\ModelComponent;
use Exception;
use lib\app\database\Database;
use lib\util\BaseObject;
use lib\util\Helper;
use ReflectionClass;


/**
 * @property string $prompt
 * @property int $type
 * @property string $content
 * @property int $link
 * @property bool $enabled
 * @property date $created_at
 * @property data $updated_at
 * 
 */
class Question extends ModelComponent
{
  const QUESTION_LINK = self::class;
  const ANSWER_LINK = Answer::class;


  public $id;
  public $prompt;
  public $context;
  public $type;
  public $enabled;
  public $link;
  public $created_at;
  public $updated_at;

  public static function instance($config = [], $model = null)
  {
    $question = parent::instance($config, $model);
    $type = $question->questionType;

    $parsedContent = $question->getParsedContent();
    return Helper::createObject(array_merge($config, $parsedContent ? $parsedContent : []), $type->handler);
  }

  public static function tableName()
  {
    return "question";
  }


  public static function excludeProperty()
  {
    $ref = new ReflectionClass(get_called_class());
    $properties = $ref->getProperties();
    $excluded = ["view"];

    foreach ($properties as $property) {
      $name = $property->name;
      $class = $property->class;
      if ($class == get_called_class())
        $excluded[] = $name;
    }
    return array_merge($excluded, parent::excludeProperty());
  }


  public function getLinkQuestion()
  {
    return $this->hasOne(self::class, ["id" => $this->link]);
  }


  public function getLinked()
  {
    return $this->hasMany(self::class, ["link" => $this->id]);
  }

  public function getQuestionAnswers()
  {
    return $this->hasMany(QuestionAnswer::class, ["question_id" => $this->id]);
  }

  public function getQuestionType()
  {
    return $this->hasOne(QuestionType::class, ["id" => $this->type])->cache(30);
  }

  public function getAssets()
  {
    return $this->hasMany(QuestionAsset::class, ["question_id" => $this->id]);
  }


  public function getFiles()
  {
    $assetTableName = QuestionAsset::tableName();
    return $this->hasMany(File::class, ["id" => "@$assetTableName.file_id"])->viaTable($assetTableName, ["question_id" => $this->id]);
  }


  public function getParsedContent()
  {
    return unserialize($this->context);
  }


  public function setContext()
  {
    $ref = new ReflectionClass(get_called_class());
    $properties = $ref->getProperties();

    $context = [];

    foreach ($properties as $property) {
      $name = $property->name;
      $class = $property->class;

      if ($class == self::class) {
        $value = $this->{$name};
        if ($value instanceof BaseObject) {
          $context[$name] = $value->toArray();
        } else {
          $context[$name] = $value;
        }
      }
    }
    $this->context = serialize($context);
  }

  public function getViewsDirectory()
  {
    return Helper::getAlias("@common\models\assessment\\views\\");
  }

  public function getAssetDirectory()
  {
    return Helper::getAlias("@common\models\assessment\\assets\\");
  }


  public function renderBuild()
  {
    return "Builder Not Implemented";
  }

  public function renderView()
  {
    return "View not Implemented";
  }

  public function getAnswer(Answer $answer)
  {
    return "Invalid Answer";
  }

  public function renderBuilder($link = null, $type = self::QUESTION_LINK)
  {
    return $this->render("builder", ["prefix" => spl_object_id($this) . "-", "link" => $link, "linkType" => $type]);
  }

  public static function configure($params = [])
  {

    $data = Helper::getValue("data", $params);
    if (!isset($data)) throw new Exception("Question data is required");


    $type = Helper::getValue("type", $params);
    if (!$type && !$type instanceof QuestionType) throw new Exception("Invalid type provided");


    $context = Helper::getValue("context", $params, []);
    $media = Helper::getValue("media", $params, []);
    $link = Helper::getValue("link", $params, []);



    $db = Database::instance();
    $qid = Helper::getValue("id", $data);

    $q = null;

    if ($qid)
      $q = Question::findOne(["id" => $qid]);


    $result = $db->transaction(
      function ($tr) use ($type, $data, $context, $media, $q, $link) {

        $handlerName = $type->handler;

        $params = [
          "prompt" => Helper::getValue("prompt", $data),
          "type" => $type->id,
          "context" => serialize($handlerName::createContext($context))
        ];

        if ($q) {
          Helper::configure($q, $params);
          $q->update($tr);
          $q = self::findOne($q->getPrimaryCondition());
        } else {
          $q = $handlerName::instance($params);
          if (!$q instanceof Question) return;
          $q->save($tr);
        }


        $assets = array_map(function ($asset) {
          return $asset->file_id;
        }, $q->assets);

        foreach ($media as $mediaId) {
          if (in_array($mediaId, $assets))
            continue;

          $asset = new QuestionAsset([
            "file_id" => $mediaId,
            "question_id" => $q->id
          ]);
          $asset->save($tr);
        }

        $removeAssets = array_diff($assets, $media);
        if (!empty($removeAssets))
          QuestionAsset::deleteByCondition(["file_id" => $removeAssets, "question_id" => $q->id]);



        if (!empty($link)) {
          $type = Helper::getValue("type", $link);
          $id = Helper::getValue("id", $link);

          $instance = $type::findOne(["id" => $id]);


          if (!isset($instance)) throw new Exception("Failed to link the question. " . basename($type) . " ($id) is invalid");
          $instance->link = $q->id;
          $instance->update(true, $tr);
        }
        return $q;
      }
    );
    return $result;
  }


  public static function createContext($context)
  {
    return [];
  }


  public static function dropdownOptions($skip = [])
  {
    $skip = is_array($skip) ? $skip : [$skip];
    $questions = self::find()->all();
    $options = [];

    foreach ($questions as $option) {
      if (in_array($option->id, $skip))
        continue;
      $type = $option->questionType->name;
      $options[$option->id] = "QID-$option->id ($type) <br/> <small>$option->prompt</small>";
    }

    return $options;
  }
}
