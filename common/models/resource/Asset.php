<?php


namespace common\models\resource;

use common\models\resource\format\Format;
use common\models\resource\format\handlers\FormatHandler;
use common\models\user\User;
use DateTime;
use Exception;
use lib\app\database\Database;
use lib\app\database\Transaction;
use lib\app\Request;
use lib\app\view\View;

class Asset extends AssetModel
{
  public $id;
  public $name;
  public $dir;
  public $format;
  public $created_at;
  public $updated_at;
  public $created_by;
  public $updated_by;


  public function getCreatedByUser()
  {
    return $this->hasOne(User::class, ["id" => $this->created_by]);
  }


  public function getUpdatedByUser()
  {
    return $this->hasOne(User::class, ["id" => $this->updated_by]);
  }


  public function getUpdateUser()
  {
    $user = $this->updatedByUser;
    if ($user)
      return "{$user->getDisplayName()} ($user->email)";

    return "User Not Found";
  }

  public function getCreateUser()
  {
    $user = $this->createdByUser;
    if ($user)
      return "{$user->getDisplayName()} ($user->email)";

    return "User Not Found";
  }


  public function getCreateDate()
  {
    $date = new DateTime($this->created_at, Database::timezone());
    $date->setTimezone(User::timezone());
    return date_format($date, "D M d, Y h:i A");
  }


  public function getUpdateDate()
  {
    $date = new DateTime($this->updated_at, Database::timezone());
    $date->setTimezone(User::timezone());
    return date_format($date, "D M d, Y h:i A");
  }

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

    $user = Request::getIdentity();

    if (!$user) return null;

    $asset = $db->transaction(function ($tr) use ($file, $format, $user) {
      $config = array(
        "name" => $file->name,
        "dir" => $file->getParsedPath(),
        "format" => $format->id,
        "created_by" => $user->userId(),
        "updated_by" => $user->userId()
      );
      $instance  = new Asset($config);


      $instance->save($tr);

      /** @var File $file */
      $instance->upload($file, []);

      return $instance;
    });

    return $asset;
  }


  public function update($update = true, ?Transaction &$transaction = null)
  {
    $user = Request::getIdentity();
    if (!$user) return null;

    $this->updated_by = $user->userId();

    parent::update($update, $transaction);
  }

  public function move($destination, $source = null, $config = [])
  {

    $name = end(explode("/", $destination));

    $destination = $destination . ".{$this->getExtension()}";
    $db = Database::instance();

    $db->transaction(function ($tr) use ($name, $destination, $config) {
      $source = $this->getPath();

      $this->dir = $destination;
      $this->name = $name;

      $this->update(true, $tr);
      parent::move($destination, $source, $config);
    });
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


  public function getExtension()
  {
    return explode("/", mime_content_type($this->readStream()))[1];
  }
}
