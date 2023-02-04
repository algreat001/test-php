<?php
class Statistics
{
  private $carsInfo;
  public function __construct($carsInfo)
  {
    $this->carsInfo = $carsInfo;
  }

  private function calc($callback, $accum)
  {
    foreach ($this->carsInfo as $carInfo) {
      $accum = call_user_func($callback, $carInfo, $accum);
    }
    return $accum;
  }

  public function getAverageMileage()
  {
    $count = count($this->carsInfo);
    if ($count == 0) {
      return 0;
    }
    $accum = 0;
    $callback = function ($carInfo, $accum) {
      $mileAge = intval(str_replace(' ', '', $carInfo->features['Mileage']));
      return $accum + $mileAge;
    };
    return $this->calc($callback, $accum) / $count;
  }

  public function getMostCommonCountryOfOrigin()
  {
    $count = count($this->carsInfo);
    if ($count == 0) {
      return 'not found';
    }
    $accum = [];
    $callback = function ($carInfo, $accum) {
      if (!array_key_exists('Country of origin', $carInfo->actions)) {
        return $accum;
      }
      $country = $carInfo->actions['Country of origin'];
      if ($country) {
        $accum[$country] = array_key_exists($country, $accum) ? $accum[$country] + 1 : 1;
      }
      return $accum;

    };
    $counties = $this->calc($callback, $accum);
    $max = 0;
    $country = 'not found';
    while ($countryCount = current($counties)) {
      if ($countryCount > $max) {
        $max = $countryCount;
        $country = key($counties);
      }
      next($counties);
    }
    return $country;
  }
  public function getMinMaxPower()
  {
    $count = count($this->carsInfo);
    if ($count == 0) {
      return 0;
    }
    $accum = ['Min' => 100000, 'Max' => 0];
    $callback = function ($carInfo, $accum) {
      $power = explode(' ', $carInfo->features['Power'])[0];
      if ($power < $accum['Min']) {
        $accum['Min'] = $power;
      }
      if ($power > $accum['Max']) {
        $accum['Max'] = $power;
      }
      return $accum;
    };
    $power = $this->calc($callback, $accum);
    return 'Min: ' . $power['Min'] . ' Hp; Max: ' . $power['Max'] . ' Hp';

  }

  private function getListAllFeatures($nameFeature)
  {
    $count = count($this->carsInfo);
    if ($count == 0 || $nameFeature == '') {
      return 0;
    }
    $accum = [];
    $callback = function ($carInfo, $accum) use ($nameFeature) {
      $item = $carInfo->features[$nameFeature];
      if ($item) {
        $accum[$item] = TRUE;
      }
      return $accum;
    };

    $items = array_keys($this->calc($callback, $accum));

    return "(" . implode(", ", $items) . ")";
  }

  public function getListAllGerbox()
  {
    return $this->getListAllFeatures('Gearbox');
  }
  public function getListAllFuel()
  {
    return $this->getListAllFeatures('Fuel');
  }

  public function getAllStats()
  {
    $res = PHP_EOL;
    $res .= sprintf('Average Mileage: ' . $this->getAverageMileage() . PHP_EOL);
    $res .= sprintf('Most common Country of origin: ' . $this->getMostCommonCountryOfOrigin() . PHP_EOL);
    $res .= sprintf('Maximum and minimum Power: ' . $this->getMinMaxPower() . PHP_EOL);
    $res .= sprintf('List of all available options of Gearbox: ' . $this->getListAllGerbox() . PHP_EOL);
    $res .= sprintf('List of all available options of Fuel: ' . $this->getListAllFuel() . PHP_EOL);
    return $res;
  }


}

?>