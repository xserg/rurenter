<?php
/**
 * Videoradar
 * Frontend
 * $Id: index.php 452 2013-11-12 13:05:10Z xxserg@gmail.com $
 */

//echo '<pre>';
//print_r($_SERVER);

require_once '../conf/config.inc';
require_once COMMON_LIB.'DVS/DVS.php';
require_once PROJECT_ROOT.'layout/Cache.php';
require_once PROJECT_ROOT.'layout/mobir.php';

if (preg_match("/^(apartment|villa)/", $_SERVER['HTTP_HOST'], $match)) {
    $_GET['ptype'] = $match[1];
}

try {
    $page_obj =  new Project_Cache;
    echo $page_obj->showCachedPage();
} catch (Exception $e) {
            //echo $e->getMessage();
}
?>
