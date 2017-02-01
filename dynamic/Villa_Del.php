<?php
/*////////////////////////////////////////////////////////////////////////////
villarenters.ru
------------------------------------------------------------------------------
Отображение текстовых страниц сайта
------------------------------------------------------------------------------
$Id: Villa_Del.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

set_time_limit (7200);
require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Villa_Del extends DVS_Dynamic
{
    // Права
    //var $perms_arr = array('ar' => 1);

    function getPageData()
    {
        $location_id = DVS::getVar('loc');
        if (!isset($location_id)) {
            echo 'Error';
            exit;
        }

        $this->db_obj->deleteVillasByLocation($location_id);


        //sleep(5);
        //$this->goLocation('/?op=countries&parent_id='.$location_id);
        exit;
        /*
        if (!empty($this->db_obj->title)) {
            $page_arr['CENTER_TITLE'] = $this->db_obj->title;
        }
        $page_arr['CENTER'] = '<p>'.nl2br($this->db_obj->body);
        return $page_arr;
        */
    }
}

?>