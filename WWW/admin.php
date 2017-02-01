<?php
/*////////////////////////////////////////////////////////////////////////////
autoru
------------------------------------------------------------------------------
Администрирование серверов AUTO.RU
------------------------------------------------------------------------------
$Id: admin.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

require_once '../conf/config.inc';
require_once COMMON_LIB.'DVS/Admin_Office.php';

$page_obj = new DVS_Admin_Office('a');
try {
    $content = $page_obj->showPage();
    echo $content;
} catch (Exception $e) {
    print_r($e);
    echo $e->getMessage();
}

if (PEAR::isError($page_obj)) {
    print "DVS_Page error: ".$page_obj->getMessage()."<BR>\n";
} else {
    //print_r ($page_obj);
}

?>
