<?php
/**
Переход на мобильную версию и обратно

*/

$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
//$webkit = strpos($_SERVER['HTTP_USER_AGENT'],"WebKit");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$mobile = strpos($_SERVER['HTTP_USER_AGENT'],"Mobile");
$symb = strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
$operam = strpos($_SERVER['HTTP_USER_AGENT'],"Opera M");
$htc = strpos($_SERVER['HTTP_USER_AGENT'],"HTC_");
$fennec = strpos($_SERVER['HTTP_USER_AGENT'],"Fennec/");
$winphone = strpos($_SERVER['HTTP_USER_AGENT'],"WindowsPhone");
$wp7 = strpos($_SERVER['HTTP_USER_AGENT'],"WP7");
$wp8 = strpos($_SERVER['HTTP_USER_AGENT'],"WP8");

/*
if (($ipad || $iphone || $android || $palmpre || $ipod || $berry || $mobile || $symb || $operam || $htc || $fennec || $winphone || $wp7 || $wp8) == true) {
    header('Location: http://m.villa'); 
}
*/
if (isset($_GET['versm'])) {
        setcookie('versm', $_GET['versm'], time()+9999999, '/', SERVER);
        $_COOKIE['versm'] = $_GET['versm'];
}


if ($_COOKIE['versm'] != 'full' && $_GET['versm'] != 'full' && !preg_match('/^m/', $_SERVER['HTTP_HOST'])) {
   if (($ipad || $iphone || $android || $palmpre || $ipod || $berry || $mobile || $symb || $operam || $htc || $fennec || $winphone || $wp7 || $wp8) == true) {
    //setcookie('mobile', '2', time()+9999999, '/', 'domain.ru');
    //header('Location: http://m.'.SERVER);
   }
}

if ($_GET['versm'] == 'mob') {
    header('Location: http://m.'.SERVER);
}

?>