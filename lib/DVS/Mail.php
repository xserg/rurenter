<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Класс создания для отправки писем в веб-интерфейсе
------------------------------------------------------------------------------
$Id: Mail.php 467 2014-02-18 07:20:41Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/
require_once 'Mail.php';
require_once 'Mail/mime.php';

class DVS_Mail
{
    // Параметры письма
    var $params = array();

    var $opt = array(
        'default_heafers' => array(
            'MIME-Version'      => '1.0',
            'Content-Type'      => 'text/plain; charset="windows-1251"',
        ),
        'default_mime' => array(
            'text_encoding'     => '7bit',
            'html_encoding'     => 'quoted-printable',
            '7bit_wrap'         => 998,
            'html_charset'      => 'windows-1251',
            'text_charset'      => 'windows-1251',
            'head_charset'      => 'windows-1251'
        ),
        'status' => array(
            0 => 'Новое',
            1 => 'Исходящее',
            2 => 'Отправленное',
            3 => 'Ошибка',
        ),
    );

    // Рисует форму для отправки письма
    function getFormObj()
    {
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('fm', 'POST', $_SERVER['REQUEST_URI']);
        $form->addElement('text', 'to', 'Кому: ',  array('size' => 74, 'maxlength' => 52));
        $form->addElement('text', 'from', 'От кого: ',  array('size' => 74, 'maxlength' => 52));
        $form->addElement('text', 'subject', 'Тема: ',  array('size' => 74, 'maxlength' => 52));
        $form->addElement('textarea', 'body', 'Письмо: ',  array('rows' => 25, 'cols' => 63));
        $form->addElement('submit', '__submit__', 'Отправить');
        $form->setDefaults($this->params);
        $form->setRequiredNote('<span style="font-size:80%; color:#ff0000;">*</span> Поля для обязательного заполнения');      

        // Правила
        $form->addRule('to', 'Заполните поле!', 'required', null);
        $form->addRule('from', 'Заполните поле!', 'required', null);
        $form->addRule('subject', 'Заполните поле!', 'required', null);
        $form->addRule('body', 'Заполните поле!', 'required', null);

        return $form;
    }

    // Отправка письма
    /**
    * $backend  smtp 
    * $params["host"] - The server to connect. Default is localhost 
    * $params["port"] - The port to connect. Default is 25 
    * $params["auth"] - Whether or not to use SMTP authentication. Default is FALSE 
    * $params["username"] - The username to use for SMTP authentication. 
    * $params["password"] - The password to use for SMTP authentication. 
    * $params["persist"] - Indicates whether or not the SMTP connection should persist over multiple calls to the send() method. 
    */

    function send($data = array(), $mail_obj = '')
    {
        if (!$data['to'] || !$data['subject'] || !$data['body']) {
            echo 'send_error';
            return false;
        }


        // Инициализируем параметры письма
        $headers['To']                        = $data['to'];
        //$headers['Сc']                       = $data['сc'];
        //$headers['Bcc']                       = $data['bcc'];
        $headers['Subject']                   = $data['subject'];
        $headers['From']                      = $data['from'] ? $data['from'] : MAIL_FROM;
        $headers['Reply-To']                  = $data['reply-to'] ? $data['reply-to'] : MAIL_FROM;
        $headers['Return-Path']               = MAIL_FROM;
        $headers['Content-Type']              = $data['content-type'] ? $data['content-type'] : 'text/plain; charset="Windows-1251"';
        $headers['Mime-Version']              = $data['mime'] ? $data['mime'] : '1.0';
        $headers['Content-Transfer-Encoding'] = $data['cte'] ? $data['cte'] : '8bit';
        
        
        $mime_mail = new Mail_mime();
        //$mime_mail->_build_params = $this->opt['default_mime'];
        $headers = $mime_mail->headers($headers);

        if (preg_match("/html/", $headers['Content-Type'])) {
            $mime_mail->setHTMLBody($data['body']);
            $headers['Content-Transfer-Encoding'] = 'quoted-printable';
            $data['body'] = $mime_mail->get();
        }
        

        /* Правим багу mail_mime */
        $headers['Subject'] = str_replace(" ", "\xA0", $headers['Subject']);


        if (!$mail_obj) {
            $send_params = null;
            if (MAIL_DRIVER == 'smtp') {
                $send_params['host'] = MAIL_HOST;
                $send_params['port'] = MAIL_PORT;
                if (MAIL_AUTH == 'true') {
                    $send_params['auth'] = true;
                    $send_params['username'] = MAIL_USERNAME;
                    $send_params['password'] = MAIL_PASSWORD;
                }
            }
            require_once 'Mail.php';
            $mail_obj =& Mail::factory(MAIL_DRIVER, $send_params);
        }
        //print_r($mail_obj);

        $mail = $mail_obj->send($headers['To'], $headers, $data['body']);

         if (PEAR::isError($mail) && TEST_DEBUG) {
           echo("<p>" . $mail->getMessage() . "</p>");
          } else {
           //echo("<p>Message successfully sent!</p>");
          }


    }

    function letter($data, $tmpl_name)
    {
        require_once 'HTML/Template/Sigma.php';
        $tpl_obj =& new HTML_Template_Sigma(PROJECT_ROOT.TMPL_FOLDER, PROJECT_ROOT.CACHE_FOLDER.'tmpl');
        $tpl_obj->loadTemplateFile($tmpl_name, 1, 1);
        $tpl_obj->setVariable($data);
        return $tpl_obj->get();
    }
}

?>