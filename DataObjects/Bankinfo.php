<?php
/**
 * Table Definition for bankinfo
 */
require_once 'DB/DataObject.php';

class DBO_Bankinfo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'bankinfo';                        // table name
    public $_database = 'rurenter';                        // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $user_id;                         // int(4)   not_null
    public $account_name;                    // varchar(255)  
    public $bank_name;                       // varchar(255)  
    public $bank_address;                    // varchar(255)  
    public $bank_city;                       // varchar(255)  
    public $bank_country;                    // varchar(255)  
    public $bank_phone;                      // varchar(255)  
    public $postcode;                        // varchar(255)  
    public $account_number;                  // varchar(64)   not_null
    public $swift;                           // varchar(255)  
    public $bic;                             // varchar(255)  
    public $iban;                            // varchar(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Bankinfo',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'account_name' =>  DB_DATAOBJECT_STR,
             'bank_name' =>  DB_DATAOBJECT_STR,
             'bank_address' =>  DB_DATAOBJECT_STR,
             'bank_city' =>  DB_DATAOBJECT_STR,
             'bank_country' =>  DB_DATAOBJECT_STR,
             'bank_phone' =>  DB_DATAOBJECT_STR,
             'postcode' =>  DB_DATAOBJECT_STR,
             'account_number' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'swift' =>  DB_DATAOBJECT_STR,
             'bic' =>  DB_DATAOBJECT_STR,
             'iban' =>  DB_DATAOBJECT_STR,
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

    public $listLabels   = array(
            //'user_id'           => '',
            'account_name'      =>  '',
            'bank_name'         =>  '',
            'account_number'    => '',
    );

    public $perms_arr = array(
        'new' => array('iu' => 0, 'oc' => 1),
        'list' => array('iu' => 0, 'oc' => 1),
        'edit' => array('iu' => 0, 'oc' => 1),
        'card' => array('iu' => 0, 'oc' => 1),
        'delete' => array('iu' => 0, 'oc' => 1),
    );

    public $qs_arr = array('user_id');

    /**
     * Строка данных таблицы
     */
    function tableRow()
    {
            return array(
                 'account_name'     =>  '<a href="?op=bankinfo&act=card&id='.$this->id.'">'.$this->account_name.' '.$this->name.'</a>',
                'bank_name' =>  $this->bank_name,
                 'account_number'     =>  $this->account_number,
            );
    }

    function preGenerateForm(&$fb)
    {
        if ($this->act == 'new') {
            $this->user_id = DVS::getVar('user_id');
            if (!$this->user_id) {
                return;
            }
            $users_obj = DB_DataObject::factory('users');
            $users_obj->get($this->user_id);
            
            $this->fb_preDefElements['user_id'] = HTML_QuickForm::createElement('hidden', 'user_id', $this->fb_fieldLabels['user_id'], $this->user_id);

            $this->center_title = $users_obj->name.' '.$users_obj->lastname;
        }
    }
}
