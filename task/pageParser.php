<?php

require "../vendor/autoload.php";
use PHPHtmlParser\Dom;

include 'carParser.php';

class PageParser
{
  private $imagePathFn = '';

  public function __construct($imagePathFn)
  {
    $this->imagePathFn = $imagePathFn;
  }

  /**
   * 
   * @param mixed $url
   * @return CarInfo[]
   */
  public function parse($url)
  {
    $dom = new Dom;
    $dom->loadFromUrl($url);
    $domItems = $dom->find('.car-item');

    $countCarsInPage = count($domItems);
    print_r("found {$countCarsInPage} items\n");
    if ($countCarsInPage > 0) {
      return $this->parseDomItems($domItems);
    } else {
      return [];
    }

  }

  private function parseDomItems($domItems)
  {
    $car = new CarParser($this->imagePathFn);
    $carsOnPage = array();

    foreach ($domItems as $item) {
      $carsOnPage[] = $car->parse($item);
    }

    return $carsOnPage;
  }

}


?>