<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require './vendor/autoload.php';

use BigGo\InterviewQuestion\StringConverter;

$string = new StringConverter();

print($string->convertZh2Hans('劉倫嘉'));