<?php


namespace common\models\access;

use common\models\base\BaseModel;

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

  public function getGrants($result = true)
  {
    $query = $this->hasMany(Grant::class, ['permission_id' => $this->id]);
    return $result ? $query->all() : $query;
  }
}
