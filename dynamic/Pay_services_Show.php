<?php
/**
 * Карточка пользователя
 * @package web2matrix
 * $Id: Pay_services_Show.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once 'HTML/QuickForm.php';

class Project_Pay_services_Show extends DVS_Dynamic
{
    // Права
    var $perms_arr = array('iu' => 1);

    function getPageData()
    {


        $links = '<br>[<a href="'.$this->db_obj->qs.'&act=edit&id='.$this->db_obj->pay_service_id.'">Редактировать</a>]&nbsp;&nbsp;[<a href="?op=payments_in&pay_service_id='.$this->db_obj->pay_service_id.'">Платежи</a>]<br><br>';

        $this->db_obj->act = 'edit';
        $form = $this->db_obj->getForm();
        $form->removeElement('__submit__');
        $form->freeze();

        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('form.tmpl');
        require_once 'HTML/QuickForm/Renderer/ITDynamic.php';
        $this->db_obj->renderer_obj = new HTML_QuickForm_Renderer_ITDynamic($this->template_obj);
        $form->accept($this->db_obj->renderer_obj);
        $page_arr['CENTER_TITLE']   = $this->db_obj->users_obj->name;
        $page_arr['CENTER']         = $links.$this->template_obj->get().$links;
        return $page_arr;
    }
}

?>