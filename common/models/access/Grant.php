<?php


namespace common\models\access;

use common\models\base\BaseModel;


require_once 'C:\xampp\htdocs\academy\common\models\index.php';
require_once 'C:\xampp\htdocs\academy\common\models\base\BaseModel.php';
/**
 * Grant Entity
 * 
 * 
 * @property int $role_id
 * @property int $permission_id
 * @property bool $enabled
 * 
 * 
 */
class Grant extends BaseModel
{

  public $role_id;
  public $permission_id;
  public $enabled;


  public function getRole()
  {
    return [];
  }


  public function getPermission()
  {
    return [];
  }
}
