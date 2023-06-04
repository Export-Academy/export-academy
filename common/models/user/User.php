<?php

namespace common\models\user;

use common\models\access\Grants;
use common\models\access\Role;
use common\models\access\UserRole;
use common\models\base\BaseModel;
use DateTimeZone;
use Exception;
use lib\app\auth\interfaces\IAuthIdentity;
use lib\app\database\Database;
use lib\app\database\Query;
use lib\app\Request;
use lib\util\Helper;

/**
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
 * @property string $token
 * @property int $meta
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
  public $meta;
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


  public function getUserMeta()
  {
    return $this->hasOne(UserMeta::class, ["id" => $this->meta]);
  }


  public function getTimezone()
  {
    return new DateTimeZone($this->userMeta->timezone);
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

  public function getRoles()
  {
    $userRoleTableName = UserRole::tableName();
    return $this->hasMany(Role::class, ["id" => "@{$userRoleTableName}.role_id"])->viaTable($userRoleTableName, ["user_id" => $this->id]);
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
    $grant = Grants::find([
      "role_id" => $this->getRoles()->select("id"),
      "permission_id" => $permission
    ])->one();
    return isset($grant) ? true : false;
  }


  public function hasRole($role)
  {
    $userRole = $this->getUserRoles()->where(["role_id" => $role]);
    return $userRole ? true : false;
  }

  public function getDisplayName()
  {
    return "$this->firstName $this->lastName";
  }

  public static function encryptPassword($password)
  {
    return hash('sha256', $password);
  }

  public static function create($data = [])
  {
    $password = Helper::getValue("password", $data, null);

    if (empty($password)) throw new Exception("Password is required");
    $data['password'] = self::encryptPassword($password);
    $db = Database::instance();

    $user = $db->transaction(function ($tr) use ($data) {
      $meta = new UserMeta();
      $meta->save($tr);
      $data["meta"] = $meta->id;
      $user = new User($data);
      $user->save($tr);
      return $user;
    });
    return $user;
  }

  public static function timezone()
  {
    $user = Request::getIdentity();
    if ($user) {
      return $user->getTimezone();
    }
    return Database::timezone();
  }
}
