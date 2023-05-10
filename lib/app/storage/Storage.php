<?php

namespace lib\app\storage;

use lib\config\Configuration;
use lib\util\BaseObject;



class Storage extends BaseObject
{

  private $client;
  public $apiKey;
  public $reference;


  public static function instance()
  {
    return new Storage(Configuration::get("storage", []));
  }

  public function init()
  {
  }
}