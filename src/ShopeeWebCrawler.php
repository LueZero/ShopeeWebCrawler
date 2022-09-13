<?php

namespace BigGo\InterviewQuestion;

use GuzzleHttp\Client;
use BigGo\InterviewQuestion\StringConverter;
use BigGo\InterviewQuestion\Models\Item;

class ShopeeWebCrawler
{
    use StringConverter;

    /**
     * @var string
     */
    private $baseUrl = "https://shopee.tw";

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $webCrawlerResult;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @var int categoryId
     * @var int limit
     * @var int newest
     * @return ShopeeWebCrawler
     */
    public function getSearchItems($categoryId, $limit, $newest)
    {
        try {

            $url = $this->baseUrl . "/api/v4/search/search_items?by=relevancy&fe_categoryids=" . $categoryId . "&limit=" . $limit . "&newest=" . $newest . "&order=desc&page_type=search&scenario=PAGE_CATEGORY&version=2";

            $response = $this->httpClient->get($url);

            $json = (string) $response->getBody();

            $this->webCrawlerResult = $json;

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
        return (array) json_decode($this->webCrawlerResult, true);
    }

    /**
     * @return Item[]
     */
    public function toItems()
    {
        $items = [];

        $array = (array) json_decode($this->webCrawlerResult, true);

        $items = empty($array['items']) == true ? [] : $array['items'];

        if (!empty($items)) 
        {
            foreach ($items as $value) 
            {
                $item = new Item();
                $item->itemId = $value['item_basic']['itemid'];
                $item->name = $this->convertZh2Hans($value['item_basic']['name']);
                $item->price = intval($value['item_basic']['price']) / 100000;
                $item->priceMin = intval($value['item_basic']['price_min']) / 100000;
                $item->priceMax = intval($value['item_basic']['price_max']) / 100000;
                array_push($items, $item);
            }
        }

        return $items;
    }
}
