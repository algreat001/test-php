<?php
class CarInfo
{
  public $id = '';
  public $name = '';
  public $images = '';
  public $features = '';
  public $actions = '';
  public function fromDom($dom)
  {
    $this->id = $dom->getAttribute('data-itemid');
    $this->name = trim($dom->find('.item-title')->find('span')[0]->innerText);

    $this->features = $this->getSubItemsFromDom($dom, '.item-feature');

    $this->actions = $this->getSubItemsFromDom($dom, '.item-action');

    $this->images = [];
    $imagesHtml = $dom->find('.hover-photo');
    foreach ($imagesHtml as $imageHtml) {
      $this->images[] = $GLOBALS['headUrl'] . $imageHtml->getAttribute('data-src');
    }
  }

  private function getSubItemsFromDom(&$dom, $name)
  {
    $featuresDom = $dom->find($name);
    $features = [];
    foreach ($featuresDom as $featureDom) {
      $name = $featureDom->getAttribute('data-original-title');
      if ($name == null || strlen($name) == 0) {
        continue;
      }
      $val = trim($featureDom->innerText);
      if (strlen($val) == 0 && str_contains($name, ':')) {
        [$name, $val] = explode(':', $name);
      }
      if (strlen($val) == 0) {
        $val = TRUE;
      }
      $features[trim($name)] = trim($val);
    }
    return $features;

  }
}
?>