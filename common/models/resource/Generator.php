<?php

namespace common\models\resource;

use League\Flysystem\Config;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
use lib\util\Helper;

class Generator implements PublicUrlGenerator
{

  public function publicUrl($id, Config $config): string
  {
    return Helper::getURL("web/source/media", ["source" => $id]);
  }
}
