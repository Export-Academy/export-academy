<?php

namespace common\models\assessment;

use common\models\base\BaseModel;
use Exception;
use lib\app\database\Database;
use lib\util\Helper;

/**
 * @property int $id
 */
class Answer extends BaseModel
{
  public $id;
  public $context;
  public $type;
  public $link;

  protected static function excludeProperty()
  {
    return array_merge([""], parent::excludeProperty());
  }

  public function getQuestion()
  {
    return $this->hasOne(Question::class, ["id" => "@" . QuestionAnswer::tableName() . ".question_id"])->viaTable(QuestionAnswer::tableName(), ["answer_id" => $this->id]);
  }


  public function getQuestionType()
  {
    return $this->hasOne(QuestionType::class, ["id" => $this->type])->cache(30);;
  }


  public function getLinked()
  {
    return $this->hasOne(Question::class, ["id" => $this->link]);
  }

  public function parseContext()
  {
    return unserialize($this->context);
  }

  public static function configure($question, $context)
  {
    if (!$question instanceof Question) throw new Exception();
    $context = is_array($context) ? $context : ["value" => $context];

    $result = Database::instance()->transaction(function ($tr) use ($question, $context) {
      $answer = new Answer([
        "context" => serialize($context),
        "type" => $question->type
      ]);

      $answer->save($tr);

      $relation = new QuestionAnswer([
        "answer_id" => $answer->id,
        "question_id" => $question->id
      ]);


      $relation->save($tr);

      return $answer;
    });


    return $result;
  }
}
