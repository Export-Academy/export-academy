<?php


namespace common\models\access;

use common\models\base\BaseModel;

require_once 'C:\xampp\htdocs\academy\common\models\base\BaseModel.php';

/**
 * Role Entity
 * 
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * 
 * 
 */
class Role extends BaseModel
{

  public $id;
  public $name;
  public $description;
}