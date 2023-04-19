<?php


namespace common\models\access;

use common\models\base\BaseModel;

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


  public function getGrants($result = true)
  {
    $query = $this->hasMany(Grant::class, ['role_id' => $this->id]);
    return $result ? $query->all() : $query;
  }
}
