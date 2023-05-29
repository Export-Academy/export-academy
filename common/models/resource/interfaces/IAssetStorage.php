<?php

namespace common\models\resource\interfaces;

use common\models\resource\File;
use lib\app\view\View;

/**
 * @author Joel Henry <joel.henry.023@gmail.com>
 */
interface IAssetStorage
{
  public function upload(File $file, $options);


  public function uploadStream(File $file, $options);


  public function has($path);


  public function read($path);


  public function readStream($path);


  public function deleteFile($path);


  public function exist($path);


  public function getPath(): string;


  public function getName(): string;


  public function getUrl(): string;

  public function referencePath(): string;

  public function getId();

  public function getHandler(View $view);
}
