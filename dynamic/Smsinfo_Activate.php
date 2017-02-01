<?php
/**
 * @package web2matrix
 * Напоминание пароля
 * $Id: Users_Remind.php 168 2012-02-16 14:49:26Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';


class Project_Smsinfo_Activate extends DVS_Dynamic
{
    /**
     * Права
     */
    public $perms_arr = array('iu' => 0, 'oc' => 1, 'ou' => 1);

    function getPageData()
    {
        $code = strtolower($_POST['code']);
        //echo $email;
        if ($_SESSION['_authsession']['data']['id']) {
            $this->db_obj->get('user_id', $_SESSION['_authsession']['data']['id']);
        } else {
            $this->msg = DVS_ERROR_ACTIVATE;
        }


        if ($code) {
            if ($this->db_obj->N) {
                if ($this->db_obj->code == $code && $this->db_obj->status_id == 0) {
                    $this->db_obj->status_id = 2;
                    $this->db_obj->send_new = 1;
                    $this->db_obj->update();
                    $this->msg = DVS_ACTIVATE;
                    $this->adminLetter();
                    return;
                } else {
                    $this->msg = "Неверный код активации!<br><br>";
                }

            } else {
                $this->msg = "Данный телефон не зарегистрирован<br><br>";
            }
        }
       //else {
            $this->createTemplateObj();
            $this->template_obj->loadTemplateFile('code.tpl');
            $this->template_obj->setVariable(array('label' => $this->db_obj->fb_fieldLabels['phone'], 'phone' => $this->db_obj->phone));
            $page_arr['CENTER_TITLE'] = 'Введите код активации';
            $page_arr['CENTER'] = $this->template_obj->get();
            return $page_arr;
        //}
    }

    function adminLetter()
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] =  MAIL_ADMIN;
        $data['subject'] = 'New ruRenter.ru Activation '.$this->db_obj->phone;
        $data['body'] = 'New ruRenter.ru phone Activation '.$this->db_obj->phone;
        DVS_Mail::send($data);
    }
}

?>