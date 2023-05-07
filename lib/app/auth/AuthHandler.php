<?php


namespace lib\app\auth;

use common\models\user\User;
use DateTime;
use lib\app\auth\interface\IAuthHandler;
use lib\app\auth\interface\IAuthIdentity;
use lib\app\database\Database;
use lib\app\http\Request;
use lib\app\router\Router;
use lib\util\BaseObject;
use lib\util\Helper;

class AuthHandler extends BaseObject implements IAuthHandler
{

  public $challengePath;
  public $forbiddenPath;
  public $redirectPath;


  private const COOKIE_KEY = "Export_Academy";


  public function authenticate(Request &$request)
  {
    $results = Helper::getValue(self::COOKIE_KEY, $_COOKIE, false);
    $data = ["authenticated" => false];
    if ($results) {
      $decrypt = $this->decrypt($results);
      if (is_array($decrypt)) {
        $data = array_merge($decrypt, ["authenticated" => true]);
      }
    }
    $user = new User($data);
    if ($user->isAuthenticated())
      $request->setIdentity($user);
    return $user;
  }

  public function challenge(IAuthIdentity $user = null, $signOut = false)
  {
    setcookie(self::COOKIE_KEY, "", time() - 1800);


    if ($signOut)
      Router::redirect("$this->challengePath");


    $current_path = Request::url();
    Router::redirect("$this->challengePath?r=$current_path");
  }


  public function forbid()
  {
    Router::redirect($this->forbiddenPath);
  }


  public function handleLogin($email, $password, $remember_me = false)
  {
    /** @var User $user */
    $user = User::findOne([
      "email" => $email,
      "password" => User::encryptPassword($password)
    ]);
    if (!$user) return false;

    $user->last_logged_in = (new DateTime("now", Database::timezone()))->format("Y-m-d H:i:s");
    $user->update(false);


    $userData = $user->toArray();
    $encrypted = $this->encrypt($userData);
    setcookie(self::COOKIE_KEY, $encrypted, time() + 1800);
    return $user;
  }


  public function handleSignOut()
  {
    $this->challenge(null, true);
  }


  public function handleAuthenticatedRedirect(IAuthIdentity $user)
  {
    Router::redirect($this->redirectPath, true);
  }


  private function encrypt($data)
  {
    $data = serialize($data);
    return base64_encode($data);
  }


  private function decrypt($cipher)
  {
    $decrypt = base64_decode($cipher);
    return unserialize($decrypt);
  }
}