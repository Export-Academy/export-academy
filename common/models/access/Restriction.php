<?php


namespace common\models\access;

use common\models\base\BaseModel;
use common\models\user\User;

/**
 * Restriction Entity
 * 
 * 
 * @property int $user_id
 * @property int $permission_id
 * @property int $role_id
 * 
 * 
 */
class Restriction extends BaseModel
{

  public $user_id;
  public $permission_id;
  public $role_id;



  public function getUser($result = true)
  {
    $query = self::hasOne(User::class, ['id' => $this->user_id]);
    return $result ? $query->all() : $query;
  }

  public function getPermission($result = true)
  {
    $query = self::hasOne(Permission::class, ['id' => $this->permission_id]);
    return $result ? $query->one() : $query;
  }

  public function getRole($result = true)
  {
    $query = self::hasOne(Role::class, ['id' => $this->role_id]);
    return $result ? $query->all() : $query;
  }
}
