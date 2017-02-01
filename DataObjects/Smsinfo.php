<?php
/**
 * Table Definition for smsinfo
 */
require_once 'DB/DataObject.php';

class DBO_Smsinfo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'smsinfo';                         // table name
    public $_database = 'rurenter';                         // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $user_id;                         // int(4)   not_null
    public $phone;                           // varchar(255)  unique_key not_null
    public $code;                            // int(4)  
    public $regdate;                         // datetime()   not_null
    public $status_id;                       // int(4)   not_null
    public $send_new;                        // tinyint(1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Smsinfo',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'phone' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'code' =>  DB_DATAOBJECT_INT,
             'regdate' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME + DB_DATAOBJECT_NOTNULL,
             'status_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'send_new' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL,
         );
    }

    function keys()
    {
         return array('id');
    }

    function sequenceKey() // keyname, use native, native name
    {
         return array('id', true, false);
    }

    function defaults() // column default values 
    {
         return array(
             '' => null,
         );
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    public $perms_arr = array(
        'new' => array('iu' => 1, 'mu' => 1, 'oc' => 1),
		'edit' => array('iu' => 0, 'oc' => 1),
        'list' => array('iu' => 0, 'oc' => 1),
    );

    function getForm()
    {
        //    $this->qs = '/office/?op=static&act=update_row';
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '');
        //$form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        $form->addElement('text', 'phone', $this->fb_fieldLabels['phone'].': ');
        
        $form->addElement('text', 'code', $this->fb_fieldLabels['code'].': ');
		$form->addElement('checkbox', 'send_new', '', $this->fb_fieldLabels['send_new'],  'class=radio');

        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addRule('home_phone', $this->rules['phone'][0], 'regex', '/^[0-9-+\(\) ]{10,17}$/');

        return $form;
    }



    function preProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'new') {
            $fb->dateToDatabaseCallback = null;
            $vals['code'] = mt_rand(1000, 9999);
            $vals['regdate'] = date("Y-m-d H:i:s");
        }
    }

    function postProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'new') {
            //echo $this->phone.' '.$this->code;
            self::sendSms($this->phone, 'ruRenter.ru registration code: '.$this->code);
        }
    }

    function sendSms($to, $text)
    {
        $ch = curl_init("http://sms.ru/sms/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(

            "api_id"		=>	"dcdc5a3b-6fa4-52e4-0d04-793ae237c2a5",
            "to"			=>	$to,
            "text"		=>	$text
            //"text"		=>	iconv("windows-1251", "utf-8", $text)

        ));
        $body = curl_exec($ch);
        curl_close($ch);
        $sms_log_obj = DB_DataObject::factory('sms_log');
        $sms_log_obj->phone = $to;
        $sms_log_obj->text = $text;
        $sms_log_obj->send_time = date("Y-m-d H:i:s");
        $sms_log_obj->insert();

    }
}
