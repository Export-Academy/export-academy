<?php


namespace common\models\resource;

use common\models\base\BaseModel;

class AssetRelation extends BaseModel
{
  public $entity;
  public $asset;
  public $entity_id;

  public static function tableName()
  {
    return "asset_relation";
  }

  public function getEntityInstance()
  {
    return $this->hasOne(Entity::class, ["id" => $this->entity]);
  }

  public function getReferenceEntity()
  {
    $entity = $this->entityInstance->name;
    if (class_exists($entity))
      return $this->hasOne($entity, [$entity::getPrimaryKey() => $this->model_id]);

    return null;
  }
}