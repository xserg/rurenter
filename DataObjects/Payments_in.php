<?php
/**
 * Table Definition for payments_in
 */
require_once 'DB/DataObject.php';

class DBO_Payments_in extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'payments_in';                     // table name
    public $_database = 'rurenter';                     // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $external_id;                     // varchar(255)  
    public $pay_date;                        // datetime()  
    public $ammount;                         // int(4)  
    public $currency;                        // varchar(16)   not_null
    public $about;                           // varchar(4000)  
    public $user_id;                         // int(4)  
    public $pay_service_id;                  // int(4)  
    public $booking_id;                      // int(4)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Payments_in',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'external_id' =>  DB_DATAOBJECT_STR,
             'pay_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
             'ammount' =>  DB_DATAOBJECT_INT,
             'currency' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'about' =>  DB_DATAOBJECT_STR,
             'user_id' =>  DB_DATAOBJECT_INT,
             'pay_service_id' =>  DB_DATAOBJECT_INT,
             'booking_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
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



    /**
     * Заголовок для таблицы
     */
    public $center_title = 'Входящие платежи';

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form    = 'платеж';

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
             //'user_id' =>  '',
             'user_name' => '',
             'pay_service_name' =>  '',
             'external_id' =>  '',
             'pay_date' =>  '',
             'ammount' =>  '',
             //'about' =>  '',
    );

    public $fb_fieldLabels  = array(
             'external_id' =>  'Внешний номер',
             'pay_date' =>  'Дата платежа',
             'ammount' =>  'Сумма, р.',
             'about' =>  'Доп. информация',
             'user_id' =>  'Пользователь',
            'user_name' => 'Пользователь',
             'pay_service_id' =>  'Платежная система',
             'pay_service_name' =>  'Платежная система',
        );

    public $qs_arr = array('pay_service_id', 'user_id');

    public $fb_textFields = array('about');

    public $fb_linkDisplayLevel = 2;

    public $default_sort = 'id desc';

    public $perms_arr = array(
            'delete'    => array('aa' => 0)
        );
/*
    function __construct()
    {
        $this->pay_service_id = DVS::getVar('pay_service_id');
        $this->user_id = DVS::getVar('user_id');
    }
*/
    function preGenerateForm()
    {
        $pay_services_obj = DB_DataObject::factory('pay_services');
        $pay_services_arr = $pay_services_obj->selArray(2);
        $this->fb_preDefElements['pay_service_id'] = HTML_QuickForm::createElement('select', 'pay_service_id', $this->fb_fieldLabels['pay_service_id'], $pay_services_arr);
    }

    function postGenerateForm($form)
    {
        $form->addRule('ammount', DVS_REQUIRED.' '.$this->fb_fieldLabels['ammount'], 'required', null, 'client');
        $form->addRule('user_id', DVS_REQUIRED.' '.$this->fb_fieldLabels['user_id'], 'required', null, 'client');
        $form->addRule('pay_service_id', DVS_REQUIRED.' '.$this->fb_fieldLabels['pay_service_id'], 'required', null, 'client');

        $form->addRule('ammount', 'Введите число!', 'regex', "/^[0-9.]+$/");

        if ($this->act == 'edit') {
            $form->freeze(array('ammount', 'user_id', 'pay_service_id'));
        }

    }

    function tableRow()
    {
        return array(
            'user_name'     =>  '<a href="?op=users&act=card&id='.$this->user_id.'">'.$this->user_name.'</a>',
             'pay_service_name' =>  $this->pay_service_name,
             'external_id' =>  $this->external_id,
             'pay_date' =>  DVS_Dynamic::RusDate($this->pay_date),
             'ammount' =>  $this->ammount,
            );
    }

    function createObjects()
    {
        $this->users_obj = DB_DataObject::factory('users');
        $this->pay_services_obj = DB_DataObject::factory('pay_services');
    }

    function preGenerateList()
    {
        $this->createObjects();
        
        //$this->advertisers_obj->preGenerateList();
        $this->joinAdd($this->users_obj);
        $this->joinAdd($this->pay_services_obj);
        $this->selectAs();

        $this->selectAs($this->users_obj, 'user_%s');
        $this->selectAs($this->pay_services_obj, 'pay_service_%s');
        //$this->selectAs($this->->users_obj, 'user_%s');
    }

    function preProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'new') {
            $this->query('BEGIN');
        } else {
             $fb->userEditableFields = array(
             'external_id',
             'pay_date',
             'about'
            );
        }
    }

    function postProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'new') {
            $transactions_obj =  DB_DataObject::factory('transactions');
            $transaction_id = $transactions_obj->inputTransaction($vals['user_id'], $this->payment_in_id, $vals['ammount']);
            if(!$this->_lastError && !$transactions_obj->_lastError && $this->payment_in_id && $transaction_id) {
                $this->query('COMMIT');
            } else {
                $this->msg = 'ROLLBACK';
                $this->query('ROLLBACK');
            }
        }
    }

    function newPayment($user_id, $price, $currency, $booking_id, $pay_service_id, $about='')
    {
        $this->user_id = $user_id;
        $this->ammount = $price;
        $this->currency = $currency;
        $this->booking_id = $booking_id;
        $this->pay_service_id = $pay_service_id;
        $this->about = $about;

        $this->pay_date = date('Y-m-d H:i:s');
        return $this->insert();
    }
}
