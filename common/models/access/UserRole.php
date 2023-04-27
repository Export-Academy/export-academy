<?php


namespace common\models\access;

use common\models\base\BaseModel;
use common\models\user\User;

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

  public static function getPrimaryKey()
  {
    return "role_id,user_id";
  }

  public function getUser($result = true)
  {
    $query = self::hasOne(User::class, ['id' => $this->user_id]);
    return $result ? $query->all() : $query;
  }

  public function getRole($result = true)
  {
    $query = self::hasOne(Role::class, ['id' => $this->role_id]);
    return $result ? $query->all() : $query;
  }
}
