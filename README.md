# 蝦皮產品爬蟲器

針對<a href="https://shopee.tw">蝦皮網站</a>製作產品爬蟲器。

## 初始化

1. 命令提示下輸入: `composer install`。

## 執行方式

```php
require './vendor/autoload.php';

use GuzzleHttp\Client;
use BigGo\InterviewQuestion\ShopeeProductWebCrawler;
use BigGo\InterviewQuestion\ExcelGenerator;
use BigGo\InterviewQuestion\StringConverter;

$excelGenerator = new ExcelGenerator();
$client = new Client(['base_uri' => 'https://shopee.tw']);
$shopeeProductWebCrawler = new ShopeeProductWebCrawler($excelGenerator, $client);

// 取得所有類別
$categories = $shopeeProductWebCrawler->getCategory()->toArray();

// 取得類別內容
$categoryList = empty($categories['data']['category_list']) == true ? [] : $categories['data']['category_list'];

// 找尋想要的類別名稱並回傳該類別ID
$categoryId = $shopeeProductWebCrawler->findCategoryCatId($categoryList, '娛樂、收藏');

// 執行一次類別
$items = $shopeeProductWebCrawler->getCategoryProduct($categoryId, 0, 0)->toArray();

// 獲取該類別產品總數量
$page = empty($items['total_count']) == true ? 0 : ($items['total_count'] / 60);

// 抓取所有類別下的產品，newest為每一頁數量(第一頁預設=0)，下一頁都是+60
$newest = 0;
$items = $shopeeProductWebCrawler->getCategoryProduct($categoryId, 60, $newest)->toItems();
```
