<?php

namespace common\models;

use common\models\access\UserRole;
use common\models\base\BaseModel;
use lib\app\auth\interface\IAuthIdentity;
use lib\app\database\Query;
use lib\util\Helper;

require_once Helper::getAlias('@common\models\base\BaseModel.php');

/**
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
 * @property string $token
 * @property int $type_id
 * @property int $meta_id
 * @property date $created_at
 * @property date $updated_at
 */
class User extends BaseModel implements IAuthIdentity
{


  public $firstName;
  public $lastName;
  public $email;
  public $password;
  public $token;
  public $type_id;
  public $meta_id;
  public $created_at;
  public $updated_at;


  public $authenticated = false;


  /**
   * Undocumented function
   *
   * @param boolean $result
   * @return UserRole|Query
   */
  public function getUserRoles($result = true)
  {
    $query = $this->hasMany(UserRole::class, ['user_id' => $this->id]);
    return $result ? $query->all() : $query;
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


  public static function encryptPassword($password)
  {
    return hash('sha256', $password);
  }
}
