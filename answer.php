<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './vendor/autoload.php';

use BigGo\InterviewQuestion\ShopeeProductWebCrawler;
use BigGo\InterviewQuestion\ExcelGenerator;
use BigGo\InterviewQuestion\Helpers\ShoppeHelper;

$excelGenerator = new ExcelGenerator();
$shopeeProductWebCrawler = new ShopeeProductWebCrawler();

$database = [['名稱', '金額']];

$categoryTree = $shopeeProductWebCrawler->getCategoryTree()->toArray();
$categoryList = empty($categoryTree['data']['category_list']) == true ? [] : $categoryTree['data']['category_list'];
$categoryId = ShoppeHelper::getCategoryCatId($categoryList, '娛樂、收藏');

$items = $shopeeProductWebCrawler->getSearchItems($categoryId, 0, 0)->toArray();
$page = empty($items['total_count']) == true ? 0 : ($items['total_count'] / 60);

$newest = 0;

for ($i = 0; $i < $page; $i++) {

    $items = $shopeeProductWebCrawler->getSearchItems($categoryId, 60, $newest)->toItems();
    $newest += 60;

    foreach($items as $item) {
        $price = '$'.$item->price;

        if ($item->priceMin != $item->priceMax)
            $price = '$'.$item->priceMin . ' - ' . '$'.$item->priceMax;

        array_push($database, [$item->name, $price]);
    }
}

$excelGenerator->setTitle('zero');
$excelGenerator->fromArray($database, 'A1');
$excelGenerator->save('product');