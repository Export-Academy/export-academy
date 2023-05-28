<?php

namespace common\models\resource\interfaces;

use common\models\resource\File;
use DateTime;


/**
 * @author Joel Henry <joel.henry.023@gmail.com>
 */
interface IAssetModel
{
  const BY_URL = "url";
  const BY_FILE = "file";

  /**
   * Upload files to the Google Cloud
   *
   * @param File $file
   * @param string $bucket
   * @param string $type
   * @return mixed
   */
  public static function upload(File $file, $bucket = null, $type = self::BY_FILE);

  /**
   * Upload files by provided URL
   *
   * @param string $url
   * @param string $name
   * @param string $type
   * @param string $path
   * @param string $bucket
   * @return mixed
   */
  public static function uploadByURL($url, $name, $type, $path = "", $bucket = null);

  /**
   * Upload files by provided file
   *
   * @param mixed $file
   * @param string $path
   * @param string $bucket
   * @return mixed
   */
  public static function uploadByFile($file, $path = "", $bucket = null);

  /**
   * Get FileObject instance
   *
   * @return mixed
   */
  public function getFileInstance();

  /**
   * Get public URL for the asset available
   * for only a limited period of time
   *
   * @param array $options
   * @param DateTime $expiresIn
   * @return string
   */
  public function getURL($options = [],  $expiresIn = new DateTime("1 hour"));
}