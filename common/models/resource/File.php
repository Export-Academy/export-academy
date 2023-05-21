<?php


namespace common\models\resource;

use common\models\base\BaseModel;
use DateTime;
use Exception;
use Google\Cloud\Storage\StorageClient;
use lib\app\log\Logger;
use lib\config\Configuration;
use lib\util\Helper;

class File extends BaseModel
{
  public $id;
  public $bucket;
  public $name;

  /** @var StorageClient */
  public $client;
  public $projectId;
  public $credentials;
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

  public static function upload($tempName, $filename, $bucketName, $isURL = false)
  {
    $data = null;
    try {
      $storage = new File();
      $bucket = $storage->client->bucket($bucketName ?? $storage->defaultBucket);

      if ($isURL) {
        $content = file_get_contents($tempName);
        $file = tmpfile();
        fwrite($file, $content);
      } else {
        $file = fopen($tempName, "r");
      }

      if ($file) {
        $data = $bucket->upload(
          $file,
          array("name" => $filename)
        );
        Logger::log("Uploaded file to google cloud storage: " . $data->gcsUri());
      }
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), "error");
    }

    if (isset($data)) {
      $identity = $data->identity();
      $storage->bucket = $identity["bucket"];
      $storage->name = $identity["object"];

      $storage->save();
      return $storage;
    }
    return null;
  }

  public static function uploadByFile($file, $path = "", $bucket = null)
  {
    $tmp_name = Helper::getValue('tmp_name', $file);
    $name = Helper::getValue('name', $file);
    $type = Helper::getValue('type', $file);

    $filename = (empty($path) ? "$name." : "$path/$name.") . explode("/", $type)[1];
    return self::upload($tmp_name, $filename, $bucket);
  }


  public static function uploadByURL($url, $name, $path = "", $bucket = null)
  {
    $filename = (empty($path) ? "$name" : "$path/$name");
    return self::upload($url, $filename, $bucket, true);
  }


  public function getClientBucket()
  {
    return $this->client->bucket($this->bucket);
  }


  public function getFileObject()
  {
    return $this->getClientBucket()->object($this->name);
  }

  public function getURL($options = [], $expiresIn = new DateTime("1 hour"))
  {
    return $this->getFileObject()->signedUrl($expiresIn, $options);
  }
}
