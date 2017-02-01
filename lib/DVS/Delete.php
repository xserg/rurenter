<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов
Класс удаления записей
------------------------------------------------------------------------------
$Id: Delete.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_Delete extends DVS_Dynamic
{
    function getPageData()
    {
        $res = true;
        //Операции перед удалением
        if (method_exists($this->db_obj, 'preDelete')) {
            $res = $this->db_obj->preDelete();
        }
        if ($res && !$this->db_obj->msg) {
            $this->msg = $this->db_obj->delete() ? 'DELETE_ROW' : 'ERROR';
        } else {
            $this->msg = $this->db_obj->msg;
        }

        $this->goLocation();
    }
}
?>
