<?php


namespace common\models\resource;

use common\models\resource\format\Format;
use lib\util\BaseObject;

class File extends BaseObject
{
  public $tmp_name;
  public $name;
  public $path;
  public $isUrl;


  public function getParsedPath()
  {
    return (isset($this->path) ? "$this->path/$this->name" : $this->name) . "." . $this->getExtension();
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
    $name = explode("/", $type)[0];
    $format = Format::findOne(["name" => $name]);

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
