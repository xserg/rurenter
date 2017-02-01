<?php
/**
 * Карточка пользователя
 * @package web2matrix
 * $Id: Users_Show.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Users_Role extends DVS_Dynamic
{
    // Права
    var $perms_arr = array('iu' => 1, 'ou' => 1, 'oc' => 1);

    function getPageData()
    {

        @session_start();
        $user_id = $_SESSION['_authsession']['data']['id'];
        $role_id = DVS::getVar("role_id");

        $this->db_obj->get($user_id);

        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
            return;
        }

        if (!$this->db_obj->role_id && ($role_id == 'ou' || $role_id == 'oc')) {
            //DB_DataObject::DebugLevel(1);
            $this->db_obj->role_id = $role_id;
            $this->db_obj->update();
            $_SESSION['_authsession']['data']['role_id'] = $role_id;
            header("location: /office/");
        }
        

        $this->createTemplateObj();
        $template_name = 'role.tpl';
        if ($this->lang == 'en') {
            $template_name = 'role_en.tpl';
        }
        $this->template_obj->loadTemplateFile($template_name);

        $fields = array(
                'URL' => $_SERVER['REQUEST_URI'],
                'user_name' => $this->db_obj->name,
                'user_lastname' => $this->db_obj->lastname,
                'address' => $this->db_obj->address,
                //'company' => $this->db_obj->company,
               'company' => 'RuRenter.ru',

        );
        $this->template_obj->setVariable($fields);
        //$address_conditions_obj = DB_DataObject::factory('address_conditions');
        //$cond = $address_conditions_obj->getConditions($this->db_obj->campaign_id);

        if (!empty($this->db_obj->title)) {
            $page_arr['CENTER_TITLE'] = $this->db_obj->title;
        }
        $page_arr['CENTER'] = nl2br($this->template_obj->get());
        return $page_arr;
    }
}

?>