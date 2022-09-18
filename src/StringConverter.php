<?php

namespace Zero;

use MediaWiki\Languages\Data\ZhConversion;

trait StringConverter
{
    public static function convertZh2Hant($str)
    {
        return strtr($str, ZhConversion::$zh2Hant);
    }

    public static function convertZh2Hans($str)
    {
        return strtr($str, ZhConversion::$zh2Hans);
    }
}
