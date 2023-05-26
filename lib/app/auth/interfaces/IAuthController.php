<?php

namespace lib\app\auth\interfaces;


interface IAuthController
{
  /**
   * Defines the security parameters of the model
   * 
   * ```
   * // All endpoints within the controller requires authenticated user
   *  "requiresAuth" => ["*"],
   * // Optionally by using the method name auth will be required for methods mentioned in the array
   * "requiresAuth" => ["actionIndex", ...]
   * // Methods recorded in this array will not be accessible to authenticated users
   * "strictNoAuth" => ["actionLogin", ...]
   * // All endpoints within the controller will not be accessible to authenticated users
   *  "strictNoAuth" => ["*"]
   * 
   * ```
   *
   * @return array
   */
  public function secure();
}
