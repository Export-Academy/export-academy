<?php


namespace components\flash;

use components\BaseComponent;
use lib\app\Request;
use lib\util\Helper;
use lib\util\html\Html;

class Flash extends BaseComponent
{
  const ERROR = "danger";
  const WARNING = "warning";
  const SUCCESS = "success";
  const INFO = "info";


  const FLASH_ID = "export-academy-flash";

  public function getViewsDirectory()
  {
    return Helper::getAlias("@components\\flash\\views\\", "\\");
  }


  public function getAssetDirectory()
  {
    return Helper::getAlias("@components\\flash\\assets\\", "\\");
  }


  public static function error($message, $title = null)
  {
    $flashes = Request::get(self::FLASH_ID, []);
    $flashes[self::ERROR][] = ["message" => $message, "title" => $title];

    Request::add(self::FLASH_ID, $flashes);
  }

  public static function warning($message, $title = null)
  {
    $flashes = Request::get(self::FLASH_ID, []);
    $flashes[self::WARNING][] = ["message" => $message, "title" => $title];

    Request::add(self::FLASH_ID, $flashes);
  }


  public static function success($message, $title = null)
  {
    $flashes = Request::get(self::FLASH_ID, []);
    $flashes[self::SUCCESS][] = ["message" => $message, "title" => $title];

    Request::add(self::FLASH_ID, $flashes);
  }

  public static function info($message, $title = null)
  {
    $flashes = Request::get(self::FLASH_ID, []);
    $flashes[self::INFO][] = ["message" => $message, "title" => $title];

    Request::add(self::FLASH_ID, $flashes);
  }

  public static function reset()
  {
    Request::add(self::FLASH_ID, []);
  }


  public function renderFlashes()
  {
    $flashes = Request::get(self::FLASH_ID, []);
    $content = "";

    foreach ($flashes as $category => $alerts) {
      foreach ($alerts as $flash) {
        $content .= $this->render("toast", ["category" => $category, "message" => Helper::getValue("message", $flash, ""), "title" => Helper::getValue("title", $flash)]);
      }
    }

    $content = Html::tag("div", $content, ["class" => "toast-container"]);

    return Html::tag("div", $content, ["class" => "main-toast-container"]);
  }
}
