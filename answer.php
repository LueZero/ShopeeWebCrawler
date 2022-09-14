<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './vendor/autoload.php';

use BigGo\InterviewQuestion\ShopeeWebCrawler;
use BigGo\InterviewQuestion\ExcelGenerator;

$shopeeWebCrawler = new ShopeeWebCrawler();

$categoryTree = $shopeeWebCrawler->getCategoryTree()->toArray();
$categoryList = empty($categoryTree['data']['category_list']) == true ? [] : $categoryTree['data']['category_list'];
$key = array_search('娛樂、收藏', array_column($categoryList, 'display_name'));
$categoryId = $categoryList[$key]['catid'];

$items = $shopeeWebCrawler->getSearchItems($categoryId, 0, 0)->toArray();
$totalCount = empty($items['total_count']) == true ? 0 : ($items['total_count'] / 60);
$newest = 0;

$database = [['產品ID', '產品名稱', '產品金額', '產品最小金額', '產品最大金額']];

for ($i = 0; $i < $totalCount; $i++) {

    $result = $shopeeWebCrawler->getSearchItems($categoryId, 60, $newest)->toItems();
    $newest += 60;

    foreach($result as $value)
        array_push($database, (array) $value);
}

$excelGenerator = new ExcelGenerator();
$excelGenerator->fromArray($database, 'A1');
$excelGenerator->save('prodcut');