<?php

namespace BigGo\InterviewQuestion;

use BigGo\InterviewQuestion\Models\Shoppe\Item;
use BigGo\InterviewQuestion\ExcelGenerator;
use GuzzleHttp\Client;

class ShopeeProductWebCrawler implements ProductWebCrawlerInterface
{
    /**
     * @var \BigGo\InterviewQuestion\ExcelGenerator
     */
    private $excelGenerator;

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $body;

    /**
     * @param \BigGo\InterviewQuestion\ExcelGenerator excelGenerator
     * @param \GuzzleHttp\Client; client
     */
    public function __construct($excelGenerator, $client)
    {
        $this->excelGenerator = $excelGenerator;
        $this->httpClient = $client;
    }

    /**
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $keyword
     * @param int $limit
     * @param int $newest
     * @param string $by
     * @param string $order
     * @return this
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function getProduct($keyword, $limit, $newest, $by = 'relevancy', $order = 'desc')
    {
        try {

            $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

            $response = $this->httpClient->request('GET', $uri, ['headers' => ['x-api-source' => 'pc']]);

            $this->body = (string) $response->getBody();

            return $this;

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $response = $e->getResponse();
            throw new \ErrorException($response->getBody()->getContents());

        }
    }

    /**
     * @param this 
     */
    public function getCategory()
    {
        try {

            $uri = '/api/v4/pages/get_category_tree';

            $response = $this->httpClient->request('GET', $uri);

            $this->body = (string) $response->getBody();

            return $this;

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $response = $e->getResponse();
            throw new \ErrorException($response->getBody()->getContents());

        }
    }

    /**
     * @param int $categoryId
     * @param int $limit
     * @param int $newest
     * @param string $by
     * @param string $order
     * @return this
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function getCategoryProduct($categoryId, $limit, $newest, $by = 'pop', $order = 'desc')
    {
        try {

            $uri = '/api/v4/search/search_items?by=' . $by . '&fe_categoryids=' . $categoryId . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_CATEGORY&version=2';

            $response = $this->httpClient->request('GET', $uri, ['headers' => ['x-api-source' => 'pc']]);

            $this->body = (string) $response->getBody();

            return $this;

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $response = $e->getResponse();
            throw new \ErrorException($response->getBody()->getContents());

        }
    }

    /**
     * @param array
     */
    public function toArray()
    {
        return strlen($this->body) == 0 ? [] : (array) json_decode($this->body, true);
    }

    /**
     * @param Item[]
     */
    public function toItems()
    {
        $items = [];
        $products = $this->toArray();

        $productItems = empty($products['items']) == true ? [] : $products['items'];
        
        if (!empty($productItems)) 
        {
            foreach ($productItems as $productItem) 
            {
                $item = new Item();
                $item->itemId = $productItem['item_basic']['itemid'];
                $item->name = $productItem['item_basic']['name'];
                $item->priceMin = intval($productItem['item_basic']['price_min']) / 100000;
                $item->priceMax = intval($productItem['item_basic']['price_max']) / 100000;
                $item->price = intval($productItem['item_basic']['price']) / 100000;
                array_push($items, $item);
            }
        }
        
        return $items;
    }

    /**
     * @param array $categoryList
     * @param string $displayName
     */
    public function findCategoryCatId($categoryList, $displayName)
    {
        if (is_array($categoryList)) {

            $children = [];

            foreach ($categoryList as $category) {

                if (isset($category['display_name']) && trim($category['display_name']) == trim($displayName)) {

                    return isset($category['catid']) ? $category['catid'] : 0;
                    
                } else if (isset($category['children'])) {

                    foreach ($category['children'] as $child)
                        array_push($children, $child);
                }
            }

            return $this->findCategoryCatId($children, $displayName);
        }
    }
    
    /**
     * @param array $source
     * @param string $sheetTitle
     * @param string $fileName
     */
    public function exportExcel($source, $sheetTitle, $fileName)
    {
        $this->excelGenerator->setTitle($fileName);
        $this->excelGenerator->fromArray($source, 'A1');
        $this->excelGenerator->save($fileName);
    }
}
