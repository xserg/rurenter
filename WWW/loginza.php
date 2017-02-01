<?php
/*////////////////////////////////////////////////////////////////////////////
rurenter.ru
------------------------------------------------------------------------------

------------------------------------------------------------------------------
$Id: office.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

require_once '../conf/config.inc';
require_once COMMON_LIB.'DVS/Auth_Loginza.php';

ini_set('display_errors', 'On');

define('LOGINZA_ID', 38086);
define('LOGINZA_KEY', '70046b0d0a71e3a6b4de70b23669dcfb');

$token = DVS::getVar('token', 'word', 'post');
$villa_id = DVS::getVar('villa_id');

        if (!$token) {
            echo 'No Token';

            //echo $this->msg;
            //return 'error_login';
        }
        //$res = $auth_obj->getResult($token);


$auth_obj = new DVS_Auth_loginza('a');
$auth_obj->authLoginza();


if ($villa_id) {
    header("location: /?op=booking&act=user&villa_id=".$villa_id);
    exit;
}
//print_r($_SESSION);
//print_r($_COOKIE);

if ($auth_obj->new_user) {
    header("location: /?op=users&act=role");
    exit;    
}

header("location: /office/");
?>
