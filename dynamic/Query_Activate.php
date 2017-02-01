<?php
/**
 * Videoradar
 * Активация нового пользователя
 * $Id: Users_Activate.php 65 2010-11-30 09:37:41Z xxserg $
*/

require_once COMMON_LIB.'DVS/Dynamic.php';


class Project_Query_Activate extends DVS_Dynamic
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
            if ($this->db_obj->status_id == 0) {
                $this->db_obj->status_id = 1;
                $this->db_obj->update();
                
                if ($this->db_obj->user_id_to) {
                    $this->userLetter($this->db_obj->user_id_to, $this->db_obj->booking_id);
                }
                $this->msg = DVS_UPDATE_ROW;
                //$this->adminLetter();
                $this->db_obj->qs = '?op=query';
                $this->goLocation();
            } else {
                $this->msg = DVS_ERROR_REACTIVATE;
            }
        } else {
            $this->msg = DVS_ERROR_ACTIVATE;
        }

    }

    function userLetter($user_id_to, $booking_id)
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($user_id_to);

        $smsinfo_obj = DB_DataObject::factory('smsinfo');
        $smsinfo_obj->user_id = $user_id_to;
        $smsinfo_obj->find('true');

        if ($smsinfo_obj->phone && $smsinfo_obj->send_new) {
            $smsinfo_obj->sendSms($smsinfo_obj->phone, 'new message for booking '.$booking_id);
        }


        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'ruRenter.ru notification';
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$booking_id;
        $data_arr = array(
            'booking_id' => $booking_id,
            'booking_link' => $booking_link,
            'body' => $this->db_obj->body,
            //'content-type' => 'text/html; charset="Windows-1251"'
            );
        $data['content-type'] = 'text/html; charset="Windows-1251"';
        $data['body'] = DVS_Mail::letter($data_arr, 'notification.htm');
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