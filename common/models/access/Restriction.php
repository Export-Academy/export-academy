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



  public function getUser()
  {
    return self::hasOne(User::class, ['id' => $this->user_id]);
  }

  public function getPermission()
  {
    return self::hasOne(Permission::class, ['id' => $this->permission_id]);
  }

  public function getRole()
  {
    return self::hasOne(Role::class, ['id' => $this->role_id]);
  }
}