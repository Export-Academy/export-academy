<?php


namespace common\models\resource;

use common\models\resource\format\Format;

class Asset extends AssetModel
{
  public $id;
  public $bucket;
  public $name;
  public $format;

  public function getAssetFormat()
  {
    return $this->hasOne(Format::class, ["id" => $this->format]);
  }


  public function getFileInstance()
  {
    return $this->client->bucket($this->bucket)->object($this->name);
  }
}
