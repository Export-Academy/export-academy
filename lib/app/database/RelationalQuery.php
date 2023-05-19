<?php

namespace lib\app\database;

use common\models\base\BaseModel;
use Exception;
use lib\util\Helper;

class RelationalQuery extends Query
{



  public static function instance($className, $condition)
  {
    try {
      $instance = Helper::createObject([], $className);
      if ($instance instanceof BaseModel) {
        return (new RelationalQuery(['model' => $className]))->from($instance->tableName())->where($condition);
      }
    } catch (Exception $ex) {
      throw new Exception('Not Instance of ' . BaseModel::class);
    }
  }

  public function viaTable($tableName, $condition)
  {
    return $this->leftJoin($tableName, $condition);
  }

  public function limit($limit)
  {
    parent::limit($limit);
    return $this;
  }
}
