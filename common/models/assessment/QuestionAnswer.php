<?php


namespace common\models\assessment;

use common\models\base\BaseModel;


/**
 * @property int $question_id
 * @property int $answer_id
 */
class QuestionAnswer extends BaseModel
{


  public $question_id;
  public $answer_id;



  public static function tableName()
  {
    return "question_answer";
  }


  public static function getPrimaryKey()
  {
    return "question_id,answer_id";
  }


  public function getQuestion()
  {
    return $this->hasOne(Question::class, ["id" => $this->question_id]);
  }


  public function getAnswer()
  {
    return $this->hasOne(Answer::class, ["id" => $this->answer_id]);
  }
}