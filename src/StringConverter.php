<?php

namespace BigGo\InterviewQuestion;

use MediaWiki\Languages\Data\ZhConversion;

class StringConverter
{
    private $zh2Hant;

    private $zh2Hans;
    
    public function __construct()
    {
        $this->zh2Hant = ZhConversion::$zh2Hant;
        $this->zh2Hans = ZhConversion::$zh2Hans;
    }

    public function convertZh2Hant($str)
    {
      	return strtr($str, $this->zh2Hant);
    }

    public function convertZh2Hans($str)
    {
      	return strtr($str, $this->zh2Hans);
    }
}