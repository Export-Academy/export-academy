<?php



namespace common\models\assessment;

use common\models\File;
use components\ModelComponent;
use lib\app\database\Database;
use lib\util\BaseObject;
use lib\util\Helper;
use ReflectionClass;


/**
 * @property string $prompt
 * @property int $type
 * @property string $content
 * @property int $answer
 * @property bool $enabled
 * @property date $created_at
 * @property data $updated_at
 * 
 */
class Question extends ModelComponent
{
  public $id;
  public $prompt;
  public $context;
  public $type;
  public $enabled;
  public $answer;
  public $created_at;
  public $updated_at;

  public static function instance($config = [], $model = null)
  {
    $question = parent::instance($config, $model);
    $type = $question->questionType;
    return Helper::createObject(array_merge($config, $question->getParsedContent()), $type->handler);
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


  public function getQuestionType()
  {
    return $this->hasOne(QuestionType::class, ["id" => $this->type]);
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

  public function renderBuilder()
  {
    return $this->render("builder");
  }

  public static function configure($data, QuestionType $type, $context, $media = [])
  {
    $db = Database::instance();
    $qid = Helper::getValue("id", $data);

    $q = null;

    if ($qid)
      $q = Question::findOne(["id" => $qid]);


    $result = $db->transaction(
      function ($tr) use ($type, $data, $context, $media, $q) {

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

        return $q;
      }
    );
    return $result;
  }


  public static function createContext($context)
  {
    return [];
  }
}
