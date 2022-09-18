<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './vendor/autoload.php';

use GuzzleHttp\Client;
use Zero\ShopeeWebCrawler;
use Zero\ExcelGenerator;
use Zero\StringConverter;

$excelGenerator = new ExcelGenerator();
$client = new Client(['base_uri' => 'https://shopee.tw']);
$shopeeWebCrawler = new ShopeeWebCrawler($excelGenerator, $client);

$categories = $shopeeWebCrawler->getCategory()->toArray();
$categoryList = empty($categories['data']['category_list']) == true ? [] : $categories['data']['category_list'];
$categoryId = $shopeeWebCrawler->findCategoryCatId($categoryList, '娛樂、收藏');

$items = $shopeeWebCrawler->getCategoryProduct($categoryId, 0, 0)->toArray();
$page = empty($items['total_count']) == true ? 0 : ($items['total_count'] / 60);
$newest = 0;

$source = [['名稱', '金額']];

for ($i = 0; $i < $page; $i++) {

    $items = $shopeeWebCrawler->getCategoryProduct($categoryId, 60, $newest)->toItems();
    $newest += 60;

    foreach($items as $item) {
        $price = '$'.$item->price;

        if ($item->priceMin != $item->priceMax)
            $price = '$'.$item->priceMin . ' - ' . '$'.$item->priceMax;

        array_push($source, [StringConverter::convertZh2Hans($item->name), $price]);
    }
}


$shopeeWebCrawler->exportExcel($source, 'zero', 'product');