<?php

namespace BigGo\InterviewQuestion;

use GuzzleHttp\Client;
use BigGo\InterviewQuestion\StringConverter;
use BigGo\InterviewQuestion\Models\Item;

class ShopeeProductWebCrawler
{
    use StringConverter;

    /**
     * @var string
     */
    private $baseUrl = 'https://shopee.tw';

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $body;

    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => $this->baseUrl]);
    }

    /**
     * @return ShopeeWebCrawler 
     */
    public function getCategoryTree()
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
     * @var int categoryId
     * @var int limit
     * @var int newest
     * @var string by
     * @return ShopeeWebCrawler
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function getSearchItems($categoryId, $limit, $newest, $by = 'pop', $order = 'desc')
    {
        try {

            $uri = '/api/v4/search/search_items?by=' . $by . '&fe_categoryids=' . $categoryId . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_CATEGORY&version=2';

            $response = $this->httpClient->request('GET', $uri, [
                'headers' => ['x-api-source' => 'pc']
            ]);

            $this->body = (string) $response->getBody();

            return $this;

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $response = $e->getResponse();
            throw new \ErrorException($response->getBody()->getContents());

        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (strlen($this->body) == 0)
            return [];

        return (array) json_decode($this->body, true);
    }

    /**
     * @return Item[]
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
                $item->name = $this->convertZh2Hans($productItem['item_basic']['name']);
                $item->priceMin = intval($productItem['item_basic']['price_min']) / 100000;
                $item->priceMax = intval($productItem['item_basic']['price_max']) / 100000;
                $item->price = intval($productItem['item_basic']['price']) / 100000;
                array_push($items, $item);
            }
        }
        
        return $items;
    }
}
