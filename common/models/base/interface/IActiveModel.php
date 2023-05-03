<?php


namespace common\models\base\interface;

use lib\app\database\Transaction;

interface IActiveModel
{
  public static function instance($config = []);
  public static function tableName();
  public static function getPrimaryKey();
  public static function find($condition);
  public static function findOne($condition);


  public function getPrimaryCOndition();
  public function hasOne($className, $condition);
  public function hasMany($className, $condition);

  public function update($update = true, Transaction &$transaction = null);
  public function save(Transaction &$transaction = null);


  public function delete(Transaction &$transaction = null);


  public static function deleteAll($models, Transaction &$transaction = null);
  public static function updateAll($models, Transaction &$transaction = null);
  public static function saveAll($models, Transaction &$transaction = null);
}
