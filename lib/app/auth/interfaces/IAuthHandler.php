<?php


namespace lib\app\auth\interfaces;

use lib\app\auth\interfaces\IAuthIdentity;
use lib\app\Request;


interface IAuthHandler
{
  /**
   * This is responsible for constructing the user's identity based on request context. It returns an `IAuthIdentity` indication whether authentication was successful and, if so, the user's identity is an authentication.
   *
   * @return IAuthIdentity
   */
  public function authenticate(Request &$request);

  /**
   * This is invoked by authorization when an unauthenticated user requests an endpoint that requires authentication. An authentication challenge is issued, for example, when an anonymous user requests a restricted resource.
   *
   * @return void
   */
  public function challenge(IAuthIdentity $user);

  /**
   * Forbid action is called when an authenticated or unauthorized user attempts to access a resource they're not permitted to access.
   *
   * @return void
   */
  public function forbid();


  /**
   * Undocumented function
   *
   * @param string $username
   * @param string $password
   * @param boolean $remember_me
   * @return IAuthIdentity
   */
  public function handleLogin($email, $password, $remember_me = false);



  public function handleAuthenticatedRedirect(IAuthIdentity $user);


  public function handleSignOut();
}
