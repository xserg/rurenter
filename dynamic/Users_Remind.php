<?php
/**
 * @package web2matrix
 * Напоминание пароля
 * $Id: Users_Remind.php 168 2012-02-16 14:49:26Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';


class Project_Users_Remind extends DVS_Dynamic
{
    /**
     * Права
     */
    public $perms_arr = array('iu' => 1);

    function getPageData()
    {
 
        $email = strtolower($_POST['email']);
        //echo $email;
        if ($email) {
            $this->db_obj->get('email', $email);
            if ($this->db_obj->N) {
                $data['to'] = $email;
                $data['subject'] = 'Напоминание пароля на ruRenter.ru';

                $data['body'] = 'Пароль пользователя '.$this->db_obj->email.": ".$this->db_obj->password."\n\n";
                
                require_once COMMON_LIB.'DVS/Mail.php';
                DVS_Mail::send($data);
                $this->msg = "Пароль отправлен!<br><br>";
            } else {
                $this->msg = "Данный email не зарегистрирован<br><br>";
            }
        }
       //else {
            $this->createTemplateObj();
            $this->template_obj->loadTemplateFile('remind.tpl');
            $page_arr['CENTER_TITLE'] = 'Напоминание пароля';
            $page_arr['CENTER'] = $this->template_obj->get();
            return $page_arr;
        //}
    }
}

?>