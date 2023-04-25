<?php


namespace common\models\access;

use common\models\base\BaseModel;
use common\models\User;
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
  public function getGrants($result = true)
  {
    $query = $this->hasMany(Grants::class, ['role_id' => $this->id]);
    return $result ? $query->all() : $query;
  }

  public function getPermissions($result = true)
  {
    $query = $this->hasMany(Permission::class, ["id" => "@" . Grants::tableName() . ".permission_id"])->viaTable(Grants::tableName(), ["role_id" => $this->id]);
    return $result ? $query->all() : $query;
  }


  public function getAssignedUsers($result = true)
  {
    $userRoleTableName = UserRole::tableName();
    $query = $this->hasMany(User::class, ["id" => "@$userRoleTableName.user_id"])->viaTable($userRoleTableName, ["role_id" => $this->id]);
    return $result ? $query->all() : $query;
  }
}
