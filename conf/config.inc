<?php
/**
 * @package ruRenter.ru
 * ------------------------------------------------------------------------------
 * Настройки конфигурации проекта
 * ------------------------------------------------------------------------------
 * $Id: config.inc 290 2012-11-25 20:05:04Z xxserg $
 */


/**
 * Вывод SQL - запросов
 * Может быть 1 - 5
 */

//define('TEST_DEBUG', 1);
//define('TEST_CACHE', 1);
//Установка locale

define('LANG', 'ru');
setlocale(LC_ALL, 'ru_RU.CP1251');

define('DB_TYPE', 'mysql');
define('DB_DRIVER', 'MDB2');

define('PROXY_HOST', '');
define('PROXY_PORT', '');
/**
 * Определение путей - не изменять!!
 */
define('HTDOCS_FOLDER', 'WWW/');
define('INC_FOLDER', 'inc/');
define('LIB_FOLDER', 'lib/');
define('CONF_FOLDER', 'conf/');
define('CACHE_FOLDER', 'cache/');
define('BIN_FOLDER', 'bin/');
define('TMPL_FOLDER', 'tmpl/');
define('IMAGE_FOLDER', 'images/');
define('LAYOUT_FOLDER', 'layout/');
define('DINAMIC_FOLDER', 'dynamic/');

/**
 * Путь хранения контента
 */
define('CONTENT_FOLDER', 'images/');

$uri = parse_url($_SERVER['REQUEST_URI']);
$_SERVER['REDIRECT_URL'] = isset($uri['path']) ? $uri['path'] : $_SERVER['REDIRECT_URL'];


if (isset($_SERVER['SCRIPT_FILENAME'])) {
    define('PROJECT_ROOT', preg_replace("/[a-z]+\/$/i", '', dirname(realpath($_SERVER['SCRIPT_FILENAME'])).'/'));
} else {
    define('PROJECT_ROOT', '../');
}
define('COMMON_LIB', PROJECT_ROOT.'lib/');

//echo PROJECT_ROOT;
//print_r($_SERVER);
/**
 * Определение типа сервера - локальный или рабочий
 */
if (preg_match("/Z:/", PROJECT_ROOT) || preg_match("/Z:/",$_SERVER['argv'][0])) {
    // ЭТО ТЕСТОВЫЕ СЕРВЕРА
    /**
     * Параметры соединения с базой данных
    */
    define('DB_USER', 'villa');
    define('DB_PASS', 'villa');
    ini_set('include_path', COMMON_LIB.';'.COMMON_LIB.'\pear;.');
    ini_set('display_errors', 'On');
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    //error_reporting(E_ERROR);
	//error_reporting(E_ALL);
    define('SERVER',   'rurenter');
    define('SERVER_URL',   'http://'.SERVER);
    define('SERVER_TYPE',   'local');
    define('DB_HOST',       'localhost');
    define('DB_NAME', 'rurenter');
    define('REFRESH_TIME', 10);
    define('IMAGE_URL', '/img/');
    define('MAIL_DRIVER', 'mail');
} else {
    // КОНФИГУРАЦИЯ РАБОЧЕГО СЕРВЕРА
    /**
     * Параметры соединения с базой данных
     */
    define('DB_USER', 'xserg');
    define('DB_PASS', 'rottar');
    ini_set('include_path', COMMON_LIB.':'.COMMON_LIB.'pear:.');
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    //error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
    define('SERVER',   'rurenter.ru');
    define('SERVER_URL',   'http://'.SERVER);
    define('IMAGE_URL', '/img/');
    define('SERVER_TYPE', 'remote');
    define('REFRESH_TIME', 3600);
    define('MAIL_DRIVER', 'mail');
    //define('MAIL_DRIVER', 'smtp');
    define('DB_HOST',       'localhost');
    define('DB_NAME', 'xserg_rurenter');
}

define('MAIL_ADMIN', 'info@villarenters.ru');
define('MAIL_FROM', MAIL_ADMIN);

if (MAIL_DRIVER == 'smtp') {
    define('MAIL_HOST', 'ssl://smtp.gmail.com');
    define('MAIL_PORT', '465');
    define('MAIL_AUTH', true);
    define('MAIL_USERNAME', 'info@rurenter.ru');
    define('MAIL_PASSWORD', 'lumumba');
}

/**
 * Префикс названий классов DVS 
 */
define('DVS_CLASS_PREFIX', 'DVS_');

/**
 * Префикс названий классов проекта 
 */
define('DVS_PROJECT_CLASS_PREFIX', 'Project_');

/**
 * dsn
 * Строка соедининения с бд
 */
define('DB_DSN', DB_TYPE.'://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME);

//define("CHARSET", 'cp1251');
define('AUTH_FORM', 'html');
define('DB_DATE_FORMAT', 'Y-m-d H:i');
define('MAX_CONTENT_SIZE', 500*1024*1024);

define('VILLA_IMAGE_URL', '/villa_img/');

//define('VILLARENTERS_IMAGE_URL', 'http://www.rentalsystems.com/data/images/');
define('VILLARENTERS_IMAGE_URL', '/images/');
define('DATA_HOST', 'http://www.villarenters.com/villarenterswebservice/villasearch.asmx/');
define('BOOKING_URL', 'http://www.rentalsystems.com/advert_price_check.asp');
define('VR_ACCOUNT_NUM', '55172A');

define('WMR_PURSE', 'R317211497449');
define('WMZ_PURSE', 'Z265194844567');
define('WME_PURSE', 'E331787085095');
define('WM_KEY', 'EirkDGidMhQD1BNccSaD7DsRFxbuWzNc5');

define('LOGINZA_ID', 38086);
define('LOGINZA_KEY', '70046b0d0a71e3a6b4de70b23669dcfb');


?>