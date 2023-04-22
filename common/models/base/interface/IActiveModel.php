<?php


namespace common\models\base\interface;

interface IActiveModel
{
  public static function tableName();
  public static function find($condition);
  public static function findOne($condition);
  public function hasOne($className, $condition);
  public function hasMany($className, $condition);
}
