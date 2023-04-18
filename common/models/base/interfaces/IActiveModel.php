<?php


namespace common\models\base\interface;

interface IActiveModel
{
  public static function find($condition);
  public static function findOne($condition);
}