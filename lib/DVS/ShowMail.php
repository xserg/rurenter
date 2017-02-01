<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс для создания веб-формы для отправки письма
------------------------------------------------------------------------------
$Id: ShowMail.php 417 2013-05-13 14:29:13Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_ShowMail extends DVS_Dynamic
{
    // Права на действие
    var $perms_arr = array('ar' => 1);

    function getPageData()
    {
        // Объект DVS_Mail
        require_once COMMON_LIB.'DVS/Mail.php';
        $mail_obj =& new DVS_Mail;

        // Инициализация параметров письма
        $mail_obj->params['from']  = $_SESSION['_authsession']['username'];
        if (method_exists($this->db_obj, 'generateParamsMail')) { 
            $mail_obj->params = array_merge($mail_obj->params, $this->db_obj->generateParamsMail());
        }

        // Объект формы
        $form = $mail_obj->getFormObj();
        if (method_exists($this->db_obj, 'postGenerateFormMail')) { 
            $this->db_obj->postGenerateFormMail(&$form);
        }

        // Отправка письма и переход на список
        if ($form->validate()) {
            $mail_obj->params = $_POST;
            if (method_exists($this->db_obj, 'processMail')) { 
                $this->db_obj->processMail(&$mail_obj);
            } else {
                $this->processMail(&$mail_obj);
            }
        }

        // Вывод формы
        $page_arr['CENTER_TITLE']  = 'Отправка письма';
        $page_arr['CENTER']        = $form->toHtml();

        return $page_arr;
    }

    // Отправка письма
    function processMail($mail_obj)
    {
        //print_r($mail_obj->params);
        $mail_obj->params['content-type'] = 'text/html; charset="Windows-1251"';
         $mail_obj->params['cte'] = 'quoted-printable';
        $mail_obj->send($mail_obj->params);
        $this->msg = 'send_letter';
        $this->goLocation();
    }
}

?>