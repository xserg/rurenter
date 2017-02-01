<?php
/**
 * Карточка пользователя
 * @package web2matrix
 * $Id: Users_Show.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Users_Agree extends DVS_Dynamic
{
    // Права
    var $perms_arr = array('ou' => 1, 'oc' => 1);

    function getPageData()
    {
        $user_id = $_SESSION['_authsession']['data']['id'];
        $this->db_obj->get($user_id);

        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        $this->createTemplateObj();

        if ($_SESSION['_authsession']['data']['role_id'] == 'oc') {
            $template_name = 'owner_agree.tpl';
            if ($this->lang == 'en') {
                $template_name = 'owner_agree_en.tpl';
            }
        } else {
            $template_name = 'renter_agree.tpl';
        }

        $this->template_obj->loadTemplateFile($template_name);

        $fields = array(
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