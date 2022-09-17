<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './vendor/autoload.php';

use GuzzleHttp\Client;
use BigGo\InterviewQuestion\ShopeeProductWebCrawler;
use BigGo\InterviewQuestion\ExcelGenerator;
use BigGo\InterviewQuestion\StringConverter;

$excelGenerator = new ExcelGenerator();
$client = new Client(['base_uri' => 'https://shopee.tw']);
$shopeeProductWebCrawler = new ShopeeProductWebCrawler($excelGenerator, $client);

$categories = $shopeeProductWebCrawler->getCategory()->toArray();
$categoryList = empty($categories['data']['category_list']) == true ? [] : $categories['data']['category_list'];
$categoryId = $shopeeProductWebCrawler->findCategoryCatId($categoryList, '娛樂、收藏');

$items = $shopeeProductWebCrawler->getCategoryProduct($categoryId, 0, 0)->toArray();
$page = empty($items['total_count']) == true ? 0 : ($items['total_count'] / 60);
$newest = 0;

for ($i = 0; $i < $page; $i++) {

    $items = $shopeeProductWebCrawler->getCategoryProduct($categoryId, 60, $newest)->toItems();
    $newest += 60;

    foreach($items as $item) {
        $price = '$'.$item->price;

        if ($item->priceMin != $item->priceMax)
            $price = '$'.$item->priceMin . ' - ' . '$'.$item->priceMax;

        array_push($source, [StringConverter::convertZh2Hans($item->name), $price]);
    }
}

$source = [['名稱', '金額']];
$shopeeProductWebCrawler->exportExcel($source, 'zero', 'product');