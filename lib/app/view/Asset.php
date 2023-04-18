<?php


namespace lib\util\Helper;

require_once 'C:\xampp\htdocs\academy\lib\util\Helper.php';


class Asset
{
  /** @var string  */
  public $basePath;

  /** @var array */
  public $styles = [];

  /** @var array */
  public $scripts = [];



  public function registerJs(string $filename, $pos = '')
  {
    $this->scripts[] = [
      'pos' => $pos,
      'filename' => $filename,
    ];
  }


  public function registerCSS(string $filename)
  {
    $this->styles[] = $filename;
  }


  public function renderJs($pos)
  {
    $render = '';

    foreach ($this->scripts as $script) {
      if ($script['pos'] != $pos) continue;

      $render .= "<script src='" . $this->basePath . $script['filename'] . "'></script>";
    }

    return $render;
  }


  public function renderCss()
  {
    $render = '';

    foreach ($this->styles as $style) {
      $render .= "<link rel='stylesheet' href='" . $this->basePath . $style . "'>";
    }

    return $render;
  }
}
