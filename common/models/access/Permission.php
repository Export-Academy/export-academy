<?php


namespace common\models\access;

use common\models\base\BaseModel;
use ReflectionClass;

/**
 * Permission Entity
 * 
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * 
 * 
 */
class Permission extends BaseModel
{

  const CreateUser = 1;
  const UpdateUser = 2;
  const AccessUserController = 3;

  const CreateQuestion = 4;
  const UpdateQuestion = 5;
  const AccessQuestionController = 6;

  const CreateResource = 7;
  const UpdateResource = 8;
  const AccessResourceController = 9;

  const CreateUserRole = 10;
  const UpdateUserRole = 11;

  const CreatePermission = 12;
  const UpdatePermission = 13;


  public static function getConstants()
  {
    $instance = new ReflectionClass(self::class);
    return $instance->getConstants();
  }



  public $id;
  public $name;
  public $description;

  public function getGrants($result = true)
  {
    $query = $this->hasMany(Grants::class, ['permission_id' => $this->id]);
    return $result ? $query->all() : $query;
  }


  public function getAssignedRoles($result = true)
  {
    $query = $this->hasMany(Role::class, ['id' => Grants::tableName() . ".role_id"])->viaTable(Grants::tableName(), ["permission_id" => $this->id]);
    return $result ? $query->all() : $query;
  }
}
