<?php
/**
 * Table Definition for payments_out
 */
require_once 'DB/DataObject.php';

class DBO_Payments_out extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'payments_out';                    // table name
    public $_database = 'rurenter';                    // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $external_id;                     // int(4)  
    public $pay_date;                        // datetime()  
    public $ammount;                         // int(4)  
    public $currency;                        // varchar(16)   not_null
    public $about;                           // varchar(4000)  
    public $user_id;                         // int(4)  
    public $pay_service_id;                  // int(4)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Payments_out',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'external_id' =>  DB_DATAOBJECT_INT,
             'pay_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
             'ammount' =>  DB_DATAOBJECT_INT,
             'currency' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'about' =>  DB_DATAOBJECT_STR,
             'user_id' =>  DB_DATAOBJECT_INT,
             'pay_service_id' =>  DB_DATAOBJECT_INT,
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
    public $center_title = 'Вывод средств';

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form    = 'платеж';

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
             'user_id' =>  '',
             //'user_name' => '',
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
            'new' => array('oc' => 1),
            'delete'    => array('aa' => 0)
        );
    public $currency_arr = array('RUR' => 'Руб.', 'USD' => 'USD', 'EUR' => 'EUR');


    function getForm()
    {
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI']);
        $out = false;
        foreach ($this->currency_arr as $k => $v) {
            $balance_obj = DB_DataObject::factory('transactions');
            $balance_obj->getUserBalance($this->user_id, $k);
            $balance_arr[$k] = $balance_obj->balance;
            $str .= ($str ? ', ' : '').$balance_arr[$k].' '.$k;
            if ($balance_arr[$k] > 0) {
                $out = true;
                $this->ammount = $balance_arr[$k];
                $this->currency = $k;
                $form->setDefaults(array('ammount' => $balance_arr[$k], 'currency' => $k));
                break;
            }
        }

        //$form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        //$form->addElement('text', 'ammount', $this->fb_fieldLabels['ammount'], array('size' => 50));
        $text = HTML_QuickForm::createElement('text', 'ammount');
        $cur = HTML_QuickForm::createElement('select', 'currency', '', $this->currency_arr);;
        $form->addGroup(array($text, $cur), '', 'Сумма: ');
        $form->addElement('submit', '__submit__', 'Сохранить');
        $form->addRule('name', 'Заполните поле!', 'required');
        return $form;
    }

    function preProcessForm(&$vals, &$fb)
    {
        $this->pay_date = date('Y-m-d H:i:s');
        $this->user_id = $_SESSION['_authsession']['data']['id'];
        $this->qs = '?op=transactions';
        $this->msg = 'Ваша заявка на вывод средств принята';
    }

}
