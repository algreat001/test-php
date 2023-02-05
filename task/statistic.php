<?php
abstract class AbstactStatisic
{
  protected $carsInfo;

  protected $name;
  public function __construct($name, $carsInfo)
  {
    $this->name = $name;
    $this->carsInfo = $carsInfo;
  }

  protected function calc($callback, $accum)
  {
    foreach ($this->carsInfo as $carInfo) {
      $accum = call_user_func($callback, $carInfo, $accum);
    }
    return $accum;
  }

  protected function getListAllFeatures($nameFeature)
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

  abstract protected function getStatsResult();

  public function getStatsString()
  {
    return sprintf($this->name . ': ' . $this->getStatsResult() . PHP_EOL);
  }
}

class AverageMileageStats extends AbstactStatisic
{

  public function __construct($carsInfo)
  {
    parent::__construct('Average Mileage', $carsInfo);
  }
  public function getStatsResult()
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
}

class MostCommonCountryOfOriginStats extends AbstactStatisic
{

  public function __construct($carsInfo)
  {
    parent::__construct('Most common Country of origin', $carsInfo);
  }
  public function getStatsResult()
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
}

class MinMaxPowerStats extends AbstactStatisic
{

  public function __construct($carsInfo)
  {
    parent::__construct('Minimum and maximum Power', $carsInfo);
  }
  public function getStatsResult()
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
}

class ListAllGerboxStats extends AbstactStatisic
{

  public function __construct($carsInfo)
  {
    parent::__construct('List of all available options of Gearbox', $carsInfo);
  }
  public function getStatsResult()
  {
    return $this->getListAllFeatures('Gearbox');
  }
}

class ListAllFuelStats extends AbstactStatisic
{

  public function __construct($carsInfo)
  {
    parent::__construct('List of all available options of Fuel', $carsInfo);
  }
  public function getStatsResult()
  {
    return $this->getListAllFeatures('Fuel');
  }
}


?>