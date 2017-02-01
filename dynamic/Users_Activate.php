<?php
/**
 * Videoradar
 * Активация нового пользователя
 * $Id: Users_Activate.php 65 2010-11-30 09:37:41Z xxserg $
*/

require_once COMMON_LIB.'DVS/Dynamic.php';


class Project_Users_Activate extends DVS_Dynamic
{
    /**
     * Права
     */
    var $perms_arr = array('iu' => 1);

    function getPageData()
    {
 
        $code = $_GET['code'];
        if ($code) {
            $this->db_obj->get('reg_code', $code);
        }
        //print_r($this->db_obj);

        if ($this->db_obj->N) {
            if ($this->db_obj->status_id == 1) {
                $this->db_obj->status_id = 2;
                $this->db_obj->update();
                $this->msg = DVS_ACTIVATE;
                $this->adminLetter();
                $this->db_obj->qs = '/office/';
                //$this->goLocation();
            } else {
                $this->msg = DVS_ERROR_REACTIVATE;
            }
        } else {
            $this->msg = DVS_ERROR_ACTIVATE;
        }

    }

    function adminLetter()
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] =  MAIL_ADMIN;
        $data['subject'] = 'New ruRenter.ru Activation '.$this->db_obj->email;
        $data['body'] = 'New ruRenter.ru Activation '.$this->db_obj->email;
        DVS_Mail::send($data);
    }
}

?>