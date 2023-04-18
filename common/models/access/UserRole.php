<?php


namespace common\models\access;

use common\models\base\BaseModel;

require_once 'C:\xampp\htdocs\academy\common\models\base\BaseModel.php';

/**
 * UserRole Entity
 * 
 * 
 * @property int $role_id
 * @property int $user_id
 * 
 * 
 */
class UserRole extends BaseModel
{

  public $user_id;
  public $role_id;


  public static function tableName()
  {
    return "user_role";
  }


  public function getUser()
  {
    return [];
  }

  public function getRole()
  {
    return [];
  }
}