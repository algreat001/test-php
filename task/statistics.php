<?php
include 'statistic.php';
class Statistics
{
  private $carsInfo;
  public function __construct($carsInfo)
  {
    $this->carsInfo = $carsInfo;
  }

  public function getAllStats()
  {
    $res = PHP_EOL;
    $stats = array("AverageMileageStats", "MostCommonCountryOfOriginStats", "MinMaxPowerStats", "ListAllGerboxStats", "ListAllFuelStats");

    foreach ($stats as $stat) {
      $res .= (new $stat($this->carsInfo))->getStatsString();
    }

    return $res;
  }


}

?>