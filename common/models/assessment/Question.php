<?php



namespace common\models\assessment;

use components\ModelComponent;
use Exception;
use lib\app\view\View;
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

  public $prompt;
  public $content;
  public $type;
  public $enabled;
  public $answer;
  public $created_at;
  public $updated_at;


  public static function instance($config = [])
  {
    $question = parent::instance($config);
    $type = $question->questionType;

    return Helper::createObject(array_merge($config, $question->parsedContent), $type->handler);
  }


  public static function excludeProperty()
  {
    $ref = new ReflectionClass(get_called_class());
    $properties = $ref->getProperties();
    $excluded = [];

    foreach ($properties as $property) {
      $name = $property->name;
      $class = $property->class;
      if ($class == get_called_class())
        $excluded[] = $name;
    }

    return array_merge($excluded, parent::excludeProperty());
  }


  public function getQuestionType($result = true)
  {
    $query = $this->hasOne(QuestionType::class, ["id" => $this->type]);
    return $result ? $query->one() : $query;
  }


  public function getParsedContent()
  {
    return unserialize($this->content);
  }


  public function setContent()
  {
    $ref = new ReflectionClass(get_called_class());
    $properties = $ref->getProperties();

    $content = [];

    foreach ($properties as $property) {
      $name = $property->name;
      $class = $property->class;

      if ($class == self::class) {
        $value = $this->{$name};
        if ($value instanceof BaseObject) {
          $content[$name] = $value->toArray();
        } else {
          $content[$name] = $value;
        }
      }
    }
    $this->content = serialize($content);
  }

  public function getViewsDirectory()
  {
    return Helper::getAlias("@common\models\assessment\\views\\");
  }

  public function getAssetDirectory()
  {
    return Helper::getAlias("@common\models\assessment\\assets\\");
  }


  public static function renderBuild(View $view)
  {
    throw new Exception("Please implement this method");
  }
}