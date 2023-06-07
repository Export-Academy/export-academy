<?php


namespace common\models\resource;

use common\models\resource\format\Format;
use lib\app\log\Logger;
use lib\util\BaseObject;

class File extends BaseObject
{
  public $tmp_name;
  public $name;
  public $path;
  public $isUrl;


  public function getParsedPath()
  {
    return empty($this->path) ? "/$this->name.{$this->getExtension()}" : "$this->path/$this->name.{$this->getExtension()}";
  }

  public function getDirectory()
  {
    return empty($this->path) ? "/" : "$this->path/";
  }


  public function getResource()
  {

    if ($this->isUrl) {
      $content = $this->getContent();
      $resource = tmpfile();

      fwrite($resource, $content);

      return $resource;
    }


    $resource = fopen($this->tmp_name, "r");
    return $resource;
  }

  public function getContent()
  {
    return file_get_contents($this->tmp_name);
  }


  public function getFormat()
  {
    $type = $this->getType();
    $name = end(explode("/", $type));
    $format = Format::findOne("name LIKE '%$name%'");

    return $format ?? false;
  }

  public function getType()
  {
    return mime_content_type($this->getResource());
  }

  public function getExtension()
  {
    $type = $this->getType();
    return explode("/", $type)[1];
  }
}
