<?php

namespace common\models\resource;

use common\models\base\BaseModel;
use common\models\resource\interfaces\IAssetStorage;
use Exception;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToReadFile;
use lib\app\log\Logger;
use lib\app\view\View;
use lib\util\Helper;


/**
 * @author Joel Henry <joel.henry.023@gmail.com>
 */
abstract class AssetModel extends BaseModel implements IAssetStorage
{
  /** @var Filesystem */
  private $_filesystem;
  /** @var LocalFilesystemAdapter */
  private $_adapter;


  public static function base()
  {
    return Helper::getBasePath() . "/source";
  }


  protected function init()
  {
    parent::init();
    $this->_adapter = new LocalFilesystemAdapter(self::base());
    $this->_filesystem = new Filesystem($this->_adapter, publicUrlGenerator: new Generator);
  }

  protected static function excludeProperty()
  {
    return array_merge(["_filesystem", "_adapter"], parent::excludeProperty());
  }



  public function upload(File $file, $options)
  {
    try {
      $this->_filesystem->write($file->getParsedPath(), $file->getContent(), $options);
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), 'error');
    }

    return $file;
  }


  public function uploadStream(File $file, $options)
  {
    try {
      $this->_filesystem->writeStream($file->getParsedPath(), $file->getResource(), $options);
    } catch (Exception $ex) {
      Logger::log($ex->getMessage(), 'error');
    }
  }

  public function has($path = null)
  {
    return $this->_filesystem->has($path ?? $this->getPath());
  }


  public function read($path = null)
  {
    try {
      $response = $this->_filesystem->read($path ?? $this->getPath());
    } catch (FilesystemException | UnableToReadFile $ex) {
      Logger::log($ex->getMessage(), 'error');
    }

    return $response;
  }


  public function readStream($path = null)
  {
    try {
      $response = $this->_filesystem->readStream($path ?? $this->getPath());
    } catch (FilesystemException | UnableToReadFile $ex) {
      Logger::log($ex->getMessage(), 'error');
    }

    return $response;
  }



  public function deleteFile($path = null)
  {
    try {
      $this->_filesystem->delete($path ?? $this->getPath());
    } catch (FilesystemException | UnableToDeleteFile $ex) {
      Logger::log($ex->getMessage(), 'error');
    }
  }


  public function exist($path = null)
  {
    try {
      $fileExists = $this->_filesystem->fileExists($path ?? $this->getPath());
    } catch (FilesystemException | UnableToCheckExistence $ex) {
      Logger::log($ex->getMessage(), 'error');
    }

    return $fileExists;
  }


  public function getUrl(): string
  {
    return $this->_filesystem->publicUrl($this->getId());
  }


  public function referencePath(): string
  {
    return self::base() . "/" . $this->getPath();
  }


  public function getMime()
  {
    return mime_content_type($this->readStream());
  }

  public function move($destination, $source = null, $config = [])
  {
    $this->_filesystem->move($source ?? $this->getPath(), $destination, $config);
  }

  abstract public function renderThumbnail(View $view);

  abstract public function view(View $view);
}
