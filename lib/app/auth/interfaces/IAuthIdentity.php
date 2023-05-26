<?php


namespace lib\app\auth\interfaces;


interface IAuthIdentity
{
  public function getDisplayName();
  public function userId();
  public function isAuthenticated();
  public function userRoles();
  public function hasRole($role);
  public function userPermissions();
  public function hasPermission($permission);
}
