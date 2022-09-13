<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './vendor/autoload.php';

use BigGo\InterviewQuestion\ShopeeWebCrawler;

$shopeeWebCrawler = new ShopeeWebCrawler();

$items = $shopeeWebCrawler->getSearchItems(11041645, 0, 0)->toArray();

$totalCount = empty($items['total_count']) == true ? 0 : ($items['total_count'] / 60);

$newest = 0;

for ($i = 0; $i < $totalCount; $i++) {
    $items = $shopeeWebCrawler->getSearchItems(11041645, 60, $newest)->toItems();
    $newest += 60;

    print_r($items);
}