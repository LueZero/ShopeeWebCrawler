<?php

namespace Zero;

interface ProductWebCrawlerInterface
{
    public function getProduct($keyword, $limit, $newest, $by = 'relevancy', $order = 'desc');

    public function getCategory();

    public function getCategoryProduct($categoryId, $limit, $newest, $by = 'pop', $order = 'desc');
}
