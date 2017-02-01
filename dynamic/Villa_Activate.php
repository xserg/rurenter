<?php
/**
 * 
 * Активация нового виллы
 * $Id: $
*/

require_once COMMON_LIB.'DVS/Dynamic.php';


class Project_Villa_Activate extends DVS_Dynamic
{
    /**
     * Права
     */
    //var $perms_arr = array('iu' => 1);

    function getPageData()
    {
        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        if ($this->db_obj->N) {
            if ($this->db_obj->status_id != 2) {
                $this->db_obj->status_id = 2;
                $this->db_obj->update();
                
                if ($this->db_obj->user_id) {
                    $this->userLetter($this->db_obj->user_id, $this->db_obj->id);
                }
                $this->msg = DVS_UPDATE_ROW;
                //$this->adminLetter();
                $this->db_obj->qs = '?op=villa'.($this->db_obj->sale ? '&sale=1' : '');
                $this->goLocation();
            } else {
                $this->msg = DVS_ERROR_REACTIVATE;
            }
        } else {
            $this->msg = DVS_ERROR_ACTIVATE;
        }

    }

    function userLetter($user_id_to, $villa_id)
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($user_id_to);

        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'ruRenter.ru notification';
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$booking_id;
        $data_arr = array(
            'villa_link' => 'http://rurenter.ru/villa/'.$villa_id.'.html',
            );
        $data['body'] = DVS_Mail::letter($data_arr, 'villa_active.tpl');
        DVS_Mail::send($data);
        
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