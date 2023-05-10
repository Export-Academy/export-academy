<?php

namespace common\models\user;

use common\models\access\Role;
use common\models\access\UserRole;
use common\models\base\BaseModel;
use lib\app\auth\interface\IAuthIdentity;
use lib\app\database\Query;

/**
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
 * @property string $token
 * @property int $type_id
 * @property bool $verified
 * @property bool $email_verified
 * @property bool $disabled
 * @property bool $requires_verification
 * @property bool $last_logged_in
 * @property date $created_at
 * @property date $updated_at
 */
class User extends BaseModel implements IAuthIdentity
{

  public $id;
  public $firstName;
  public $lastName;
  public $email;
  public $password;
  public $token;
  public $type_id;
  public $verified;
  public $email_verified;
  public $disabled;
  public $requires_verification;
  public $last_logged_in;
  public $created_at;
  public $updated_at;


  public $authenticated = false;


  public static function excludeProperty()
  {
    return array_merge(parent::excludeProperty(), ['authenticated', 'token']);
  }


  /**
   * Undocumented function
   *
   * @param boolean $result
   * @return UserRole|Query
   */
  public function getUserRoles()
  {
    return $this->hasMany(UserRole::class, ['user_id' => $this->id]);
  }

  public function getRoles($result = true)
  {
    $userRoleTableName = UserRole::tableName();
    return $this->hasMany(Role::class, ["id" => "@$userRoleTableName.role_id"])->viaTable($userRoleTableName, ["user_id" => $this->id]);
  }

  public function getUserType()
  {
  }



  public function userId()
  {
    return $this->id;
  }


  public function isAuthenticated()
  {
    return $this->authenticated;
  }


  public function hasPermission($permission)
  {
  }


  public function userPermissions()
  {
  }


  public function userRoles()
  {
  }


  public function hasRole($role)
  {
  }

  public function getDisplayName()
  {
    return "$this->firstName $this->lastName";
  }

  public static function encryptPassword($password)
  {
    return hash('sha256', $password);
  }
}