<?php


namespace lib\app\auth\interface;


interface IAuthIdentity
{
  public function userId();
  public function isAuthenticated();
  public function userRoles();
  public function hasRole($role);
  public function userPermissions();
  public function hasPermission($permission);
}
