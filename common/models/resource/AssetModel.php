<?php

namespace common\models\resource;

use common\models\base\BaseModel;
use common\models\resource\interfaces\IAssetModel;
use DateTime;
use Exception;
use Google\Cloud\Storage\StorageClient;
use lib\app\log\Logger;
use lib\config\Configuration;
use lib\util\Helper;


/**
 * @author Joel Henry <joel.henry.023@gmail.com>
 */
abstract class AssetModel extends BaseModel implements IAssetModel
{
  public $projectId;
  public $credentials;

  public $client;
  public $defaultBucket;


  protected function init()
  {
    parent::init();
    Helper::configure($this, Configuration::get("storage", []));

    if ($this->credentials) {
      putenv("GOOGLE_APPLICATION_CREDENTIALS=" . Helper::getBasePath() . "/$this->credentials");
      $this->client = new StorageClient([
        "projectId" => $this->projectId
      ]);
    }
  }

  protected static function excludeProperty()
  {
    return array_merge(["client", "projectId", "credentials", "defaultBucket"], parent::excludeProperty());
  }


  public static function upload(File $file, $bucket = null, $type = self::BY_FILE)
  {
    $data = null;


    try {
      $instanceClass = get_called_class();
      $instance = new $instanceClass;
      $bucket = $instance->client->bucket($bucket ?? $instance->defaultBucket);

      if ($type === self::BY_URL) {
        $content = file_get_contents($file->tmp_name);

        $tempFile = tmpfile();
        fwrite($tempFile, $content);
      } else {
        $tempFile = fopen($file->tmp_name, "r");
      }


      if ($tempFile) {
        $data = $bucket->upload($tempFile, array("name" => $file->name));
        Logger::log("File Uploaded: " . $data->getUri());

        return $data;
      }
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
    }

    return null;
  }


  public static function uploadByURL($url, $name, $type, $path = "", $bucket = null)
  {
    $file = new File([
      "temp_name" => $url,
      "name" => (empty($path) ? "$name." : "$path/$name.") . explode("/", $type)[1],
      "type" => $type
    ]);

    return self::upload($file, $bucket, self::BY_URL);
  }

  public static function uploadByFile($file, $path = "", $bucket = null)
  {
    $file = new File($file);

    Helper::configure($file, [
      "name" => (empty($path) ? "$file->name." : "$path/$file->name.") . explode("/", $file->type)[1]
    ]);

    return self::upload($file, $bucket, self::BY_FILE);
  }


  public function getURL($options = [], $expiresIn = new DateTime("1 hour"))
  {
    return $this->getFileInstance()->signedUrl($expiresIn, $options);
  }
}