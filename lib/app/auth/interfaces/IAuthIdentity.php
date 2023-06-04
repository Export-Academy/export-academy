<?php


namespace lib\app\auth\interfaces;


interface IAuthIdentity
{
  public function getDisplayName();
  public function userId();
  public function isAuthenticated();
  public function hasRole($role);
  public function hasPermission($permission);
  public function getTimezone();
}
