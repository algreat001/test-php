<?php
include 'pageParser.php';

class SiteParser
{
  private $urlFn;
  private $imagePathFn;
  private $showDebugInfo;
  function __construct($urlFn, $imagePathFn, $showDebugInfo)
  {
    $this->urlFn = $urlFn;
    $this->imagePathFn = $imagePathFn;
    $this->showDebugInfo = $showDebugInfo;
  }

  private $page;

  private $carsInfo = array();

  public function parse()
  {
    $page = new PageParser($this->imagePathFn);
    $numPage = 1;

    do {
      $url = call_user_func($this->urlFn, $numPage);

      if ($this->showDebugInfo) {
        print_r("Parse page:{$numPage}, url:{$url}\n");
      }

      $cars = $page->parse($url);
      $this->carsInfo = array_merge($this->carsInfo, $cars);
      $numPage++;
    }
    while ($cars);
  }

  public function printCars()
  {
    print_r($this->carsInfo);
  }
  public function getCars()
  {
    return $this->carsInfo;
  }
}
?>