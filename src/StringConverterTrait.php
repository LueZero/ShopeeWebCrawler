<?php

namespace BigGo\InterviewQuestion;

use MediaWiki\Languages\Data\ZhConversion;

trait StringConverterTrait
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
