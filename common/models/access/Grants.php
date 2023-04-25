<?php


namespace common\models\access;

use common\models\base\BaseModel;



/**
 * Grants Entity
 * 
 * 
 * @property int $role_id
 * @property int $permission_id
 * 
 * 
 */
class Grants extends BaseModel
{

  public $role_id;
  public $permission_id;

  public static function getPrimaryKey()
  {
    return "role_id,permission_id";
  }

  public function getRole($result = true)
  {
    $query = self::hasOne(Role::class, ['id' => $this->role_id]);
    return $result ? $query->all() : $query;
  }


  public function getPermission($result = true)
  {
    $query = self::hasOne(Role::class, ['id' => $this->permission_id]);
    return $result ? $query->all() : $query;
  }
}
