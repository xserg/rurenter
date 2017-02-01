<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс создания настроек проекта в интерфейсе admin.php
------------------------------------------------------------------------------
$Id: Layout_Admin_Office.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

define('DVS_LAYOUT_ADMIN_CLASSFILE_NOT_FOUND', -3);

class DVS_Layout_Admin_Office extends DVS_Layout
{
    /* Создает и возвращает массив настроек */
    function getPageData($tpl = null)
    {
        $page_arr['PAGE_TITLE']       = $this->project_obj->page_title.' '.$this->project_obj->project_title;
        $page_arr['IMAGE_L1']         = 'l1.gif';
        $page_arr['LEFT_SUB_TITLE']   = 'НА СЕРВЕРЕ';
        $page_arr['IMAGE_C1']         = 'c1.gif';
        $page_arr['CENTER_SUB_TITLE'] = $this->project_obj->project_name;
        $page_arr['IMG_URL']          = IMAGE_URL;

        if (method_exists($this->project_obj, 'getPageData')) {
            $page_arr = array_merge($page_arr, $this->project_obj->getPageData($tpl));
        }
        $page_arr['MENU']             = $this->getMenu($tpl);
        //$page_arr['EMAIL']            = FEEDBACK_SERVER;

        return $page_arr;
    }
}
?>
