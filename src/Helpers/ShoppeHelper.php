<?php

namespace BigGo\InterviewQuestion\Helpers;

class ShoppeHelper
{
    public static function getCategoryCatId($categoryList, $value)
    {
        if (is_array($categoryList)) 
        {
            $children = [];

            foreach ($categoryList as $category) {
                if (isset($category['display_name']) && trim($category['display_name']) == trim($value))
                    return isset($category['catid']) ? $category['catid'] : 0;
                else if (isset($category['children'])) {
                    foreach ($category['children'] as $child)
                        array_push($children, $child);
                }
            }

            return self::getCategoryCatId($children, $value);
        }
    }
}
