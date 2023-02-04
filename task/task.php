<?php
include 'siteParser.php';

$GLOBALS['headUrl'] = 'https://ecarstrade.com';

$genUrlPage = function ($numPage): string {
  return $GLOBALS['headUrl'] . '/auctions/stock/page' . strval($numPage) . '?sort=mark_model.asc';
};
$genImagePath = function ($idCar): string {
  return './images/' . strval($idCar) . '/';
};

$site = new SiteParser($genUrlPage, $genImagePath, TRUE);

$site->parse();

$stats = new Statistics($site->getCars());

print_r(PHP_EOL . "Some statistic info about cars from site \"" . $GLOBALS['headUrl'] . "\" .{$stats->getAllStats()}" . PHP_EOL);

?>