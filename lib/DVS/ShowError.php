<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Класс вывода сообщения о фатальной ошибке
------------------------------------------------------------------------------
$Id: ShowError.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

require_once COMMON_LIB.'DVS/Page.php';

class DVS_ShowError extends DVS_Page
{
    var $error   = true;
    var $nocache = true;
    var $err_code;

    function showPage()
    {
        $this->createTemplateObj();
        $this->createLayoutObj();
        $this->page_arr = $this->layout_obj->getPageData(&$this->template_obj);
        $this->showMessage($this->msg);
        if (isset($this->error_debug)) {
            $this->page_arr['CENTER'] = $this->error_debug;
        }
        return $this->parsePage();
    }

    function createLayoutObj()
    {
        require_once COMMON_LIB.'DVS/Layout.php';
        require_once PROJECT_ROOT.'conf/dbo_conf.inc';
        require_once 'DB/DataObject.php';
        if ($this->err_code == DVS_PAGE_ERROR_INVALIDLAYOUT) {
            include_once COMMON_LIB.'DVS/Layout_User.php';
            $layout_classname        = DVS_CLASS_PREFIX.'Layout_User';
            $this->layout_obj        = new $layout_classname;
            $this->layout_obj->iface = 'i';
        } else {
            $this->layout_obj = DVS_Layout::factory($this->iface, $this->role);
        }
    }
}
?>
