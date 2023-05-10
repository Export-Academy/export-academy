<?php


namespace common\models\access;

use common\models\base\BaseModel;
use common\models\user\User;
use lib\app\database\Query;

/**
 * Role Entity
 * 
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * 
 * 
 */
class Role extends BaseModel
{

  public $id;
  public $name;
  public $description;


  /**
   * Get all grants related to a role
   *
   * @param boolean $result
   * @return Grants|Query
   */
  public function getGrants()
  {
    return $this->hasMany(Grants::class, ['role_id' => $this->id]);
  }

  public function getPermissions()
  {
    return $this->hasMany(Permission::class, ["id" => "@" . Grants::tableName() . ".permission_id"])->viaTable(Grants::tableName(), ["role_id" => $this->id]);
  }


  public function getAssignedUsers()
  {
    $userRoleTableName = UserRole::tableName();
    return $this->hasMany(User::class, ["id" => "@$userRoleTableName.user_id"])->viaTable($userRoleTableName, ["role_id" => $this->id]);
  }
}