<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './vendor/autoload.php';

use BigGo\InterviewQuestion\ShopeeWebCrawler;
use BigGo\InterviewQuestion\ExcelGenerator;

$categoryId = 11041645;
$shopeeWebCrawler = new ShopeeWebCrawler();
$items = $shopeeWebCrawler->getSearchItems($categoryId, 0, 0)->toArray();
$totalCount = empty($items['total_count']) == true ? 0 : ($items['total_count'] / 60);

$database = [];
$newest = 0;

for ($i = 0; $i < $totalCount; $i++) {

    $result = $shopeeWebCrawler->getSearchItems($categoryId, 60, $newest)->toItems();
    $newest += 60;

    foreach($result as $value)
        array_push($database, (array) $value);
}

$excelGenerator = new ExcelGenerator();
$excelGenerator->fromArray($database, 'A1');
$excelGenerator->save('prodcut');