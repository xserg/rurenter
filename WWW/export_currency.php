#!/usr/bin/php -q
<?php
/**
Экспорт курсов валют с рбс
//http://export.rbc.ru/free/forex.0/free.fcgi?period=DAILY&tickers=NULL&d1=18&m1=03&y1=2010&d2=19&m2=03&y2=2010&lastdays=1&separator=TAB&data_format=BROWSER&header=1
TICKER	DATE	OPEN	HIGH	LOW	CLOSE	WAPRICE
GBP_NOK	2010-03-21	8.847	8.877	8.847	8.864	8.862
GBP_SEK	2010-03-21	10.767	10.7825	10.723	10.754	10.765
GBP_SGD	2010-03-21	2.0982	2.1023	2.098	2.1002	2.101
GBP_USD	2010-03-21	1.50225	1.5033	1.50095	1.5027	1.503
USD_RUR	2010-03-21	29.29	29.33405	29.28	29.33405	29.317
*/

$url = 'http://export.rbc.ru/free/forex.0/free.fcgi'
.'?period=DAILY&tickers=NULL'
//.'&d1=21&m1=03&y1=2010&d2=22&m2=03&y2=2010'
.'&lastdays=1&separator=TAB&data_format=BROWSER&header=1';

$data = file_get_contents($url);

//echo '<pre>';
//echo $data;
//preg_match_all("/(EUR_GBP|EUR_USD|EUR_RUR|USD_RUR)([^\n]+)/", $data, $cur_arr);
preg_match_all("/(EUR_GBP|EUR_USD|EUR_RUR|USD_RUR|GBP_USD)([^\n]+)/", $data, $cur_arr);

//print_r($cur_arr);

foreach ($cur_arr[2] as $k => $v) {
    $a = explode ("\t", $v);
    $rate[strtolower($cur_arr[1][$k])] = $a[5];
    $str .= $cur_arr[1][$k]."\t".$a[5]."\n";
}
echo $str;
//print_r($rate);
if ($str) {
    define('PROJECT_ROOT', preg_replace("/[a-z]+\/$/i", '', dirname(realpath($_SERVER['SCRIPT_FILENAME'])).'/'));
    file_put_contents(PROJECT_ROOT.'conf/currency_rate.txt', $str);
}

?>