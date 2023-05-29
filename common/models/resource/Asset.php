<?php


namespace common\models\resource;

use common\models\resource\format\Format;
use common\models\resource\format\handlers\FormatHandler;
use Exception;
use lib\app\database\Database;
use lib\app\database\Transaction;
use lib\app\view\View;

class Asset extends AssetModel
{
  public $id;
  public $name;
  public $dir;
  public $format;
  public $created_at;
  public $updated_at;

  public function getAssetFormat()
  {
    return $this->hasOne(Format::class, ["id" => $this->format])->cache(30);
  }


  public function getPath(): string
  {
    return $this->dir;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getId()
  {
    return $this->id;
  }

  /**
   * @return static
   */
  public static function create(File $file)
  {

    $format = $file->getFormat();
    if (!$format) throw new Exception("Unsupported format");
    $db = Database::instance();

    $asset = $db->transaction(function ($tr) use ($file, $format) {
      $config = array(
        "name" => $file->name,
        "dir" => $file->getParsedPath(),
        "format" => $format->id,
      );
      $instance  = new Asset($config);


      $instance->save($tr);

      /** @var File $file */
      $instance->upload($file, []);

      return $instance;
    });

    return $asset;
  }

  public static function findOne($condition = false)
  {
    $instance = parent::findOne($condition);

    if ($instance) {
      if ($instance->exist())
        return $instance;

      Asset::deleteAll([$instance]);
      return null;
    }

    return $instance;
  }


  public function delete(?Transaction &$transaction = null)
  {
    if (isset($tr)) {
      parent::delete($tr);
      $this->deleteFile();

      return;
    }

    $db = Database::instance();

    $db->transaction(function ($tr) {
      parent::delete($tr);
      if ($this->exist()) $this->deleteFile($this->getPath());
    });

    return;
  }


  public function renderThumbnail(View $view)
  {
    $instance = $this->assetFormat->handlerInstance($view);

    if ($instance instanceof FormatHandler)
      return $instance->renderThumbnail($this);

    return "Invalid Handler";
  }



  public function view(View $view)
  {
    $instance = $this->assetFormat->handlerInstance($view);
    if ($instance instanceof FormatHandler)
      return $instance->renderView($this);
    return "Invalid Handler";
  }


  public static function path($path)
  {
    if (!isset($path)) return self::find()->all();

    $query = self::find()->where("dir LIKE '%$path%'");
    return $query->all();
  }


  public function getHandler(View $view)
  {
    return $this->assetFormat->handlerInstance($view);
  }
}
