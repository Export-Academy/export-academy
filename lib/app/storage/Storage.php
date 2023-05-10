<?php

namespace lib\app\storage;

use Exception;
use Google\Cloud\Storage\StorageClient;
use lib\app\log\Logger;
use lib\config\Configuration;
use lib\util\BaseObject;
use lib\util\Helper;

class Storage extends BaseObject
{
  /** @var StorageClient */
  private $client;
  public $projectId;
  public $credentials;
  public $defaultBucketName;


  public static function instance()
  {
    return new Storage(Configuration::get("storage", []));
  }

  protected function init()
  {
    $this->client = new StorageClient([
      "projectId" => $this->projectId
    ]);
    putenv("GOOGLE_APPLICATION_CREDENTIALS=" . Helper::getBasePath() . "/$this->credentials");
  }


  public function getStorageClient()
  {
    return $this->client;
  }


  public function upload($filename, $name, $bucketName = null)
  {
    $bucket = $this->client->bucket($bucketName ?? $this->defaultBucketName);
    return $bucket->upload(
      fopen($filename, "r"),
      [
        "name" => $name
      ]
    );
  }
}
