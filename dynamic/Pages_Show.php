<?php
/**
////////////////////////////////////////////////////////////////////////////
aservice
------------------------------------------------------------------------------
Отображение текстовых страниц сайта
------------------------------------------------------------------------------
$Id: Pages_Show.php 157 2011-10-12 10:24:53Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Pages_Show extends DVS_Dynamic
{
    // Права
    var $perms_arr = array('iu' => 1, 'ar' => 1);

    function getPageData()
    {
        $alias = DVS::getVar('alias');
        if (!$this->db_obj->N && !$alias) {
            $alias = 'index';
        }
        if ($this->lang != 'ru') {
            $alias = $alias.'_'.$this->lang;
        }
        if ($alias) {
            $this->db_obj->get('alias', $alias);
        }
        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }
        if (!empty($this->db_obj->title)) {
            $page_arr['CENTER_TITLE'] = $this->db_obj->title;
        }
        $page_arr['CENTER'] = '<p><br><br>'.nl2br($this->db_obj->body).'<br><br>';
        return $page_arr;
    }
}

?>