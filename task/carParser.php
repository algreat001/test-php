<?php
include 'carInfo.php';
class CarParser
{

  private $imagePathFn = '';

  public function __construct($imagePathFn)
  {
    $this->imagePathFn = $imagePathFn;
  }

  /**
   *
   * @param mixed $dom
   * @return CarInfo
   */
  public function parse($dom)
  {
    $info = new CarInfo();
    $info->fromDom($dom);
    $this->saveImagesFromUrl($info->id, $info->images);
    return $info;
  }

  private function saveImagesFromUrl($id, $imageUrls)
  {
    $countImage = 0;
    foreach ($imageUrls as $imageUrl) {
      $pathForSaveImage = call_user_func($this->imagePathFn, $id . '/' . $countImage);
      $this->createDirectory($pathForSaveImage);
      $this->saveImage($imageUrl, $pathForSaveImage);
      $countImage++;
    }
  }

  private function saveImage($url, $pathForSaveImage)
  {
    $fileName = $this->getFileNameFromUrl($url);
    if ($fileName == null) {
      return false;
    }
    $data = file_get_contents($url);
    return file_put_contents($pathForSaveImage . $fileName, $data);
  }
  private function createDirectory($path)
  {
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
  }
  private function getFileNameFromUrl($url)
  {
    $expoledeUrl = explode('/', $url);
    if (!$expoledeUrl) {
      return null;
    }
    $fileName = end($expoledeUrl);
    return $fileName;
  }
}

?>