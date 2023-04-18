<?php


namespace common\models\access;

use common\models\base\BaseModel;


require_once 'C:\xampp\htdocs\academy\common\models\base\BaseModel.php';

/**
 * Permission Entity
 * 
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * 
 * 
 */
class Permission extends BaseModel
{

  public $id;
  public $name;
  public $description;
}
