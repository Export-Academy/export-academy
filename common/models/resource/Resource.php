<?php

namespace common\models\resource;

use common\models\base\BaseModel;
use common\models\resource\format\Format;

class Resource extends BaseModel
{
  public $id;
  public $title;
  public $description;
  public $content;
  public $enabled;
  public $format_id;
  public $created_at;
  public $updated_at;



  public function getFormat()
  {
    return $this->hasOne(Format::class, ["id" => $this->format_id]);
  }
}
