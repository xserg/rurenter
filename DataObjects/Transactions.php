<?php
/**
 * Table Definition for transactions
 */
require_once 'DB/DataObject.php';

class DBO_Transactions extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'transactions';                    // table name
    public $_database = 'rurenter';                    // database name (used with database_{*} config)
    public $id;                              // int(4)   not_null
    public $pay_date;                        // datetime()  
    public $payment_in_id;                   // int(4)  
    public $balance;                         // float()  
    public $user_id;                         // int(4)  
    public $credit;                          // float()  
    public $debit;                           // float()  
    public $currency;                        // varchar(11)   not_null
    public $booking_id;                      // int(4)  
    public $descr;                           // varchar(255)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Transactions',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'pay_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
             'payment_in_id' =>  DB_DATAOBJECT_INT,
             'balance' =>  DB_DATAOBJECT_INT,
             'user_id' =>  DB_DATAOBJECT_INT,
             'credit' =>  DB_DATAOBJECT_INT,
             'debit' =>  DB_DATAOBJECT_INT,
             'currency' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'booking_id' =>  DB_DATAOBJECT_INT,
             'descr' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
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
    public $center_title = 'Личный счет';

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form    = 'платеж';

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
            //'user_name'         => '',
             //'advertiser_id' =>  '',
             //'payment_in_id' =>  '',
             'pay_date' => 'class="td-date"',
             'debit' =>  '',
             //'credit' =>  '',
             //'balance' =>  '',
             'descr' => ''
    );

    public $fb_fieldLabels  = array(
             'payment_in_id' =>  'Входящий платеж',
             'pay_date' => 'Дата платежа',
             'balance' =>  'Баланс',
             'advertiser_id' =>  'Рекламодатель',
             'credit' =>  'Расход',
             'debit' =>  'Приход-Расход',
             'campaign_id' =>  'Кампания',
             'campaign_name' =>  'Кампания',
             'user_name'         => 'Рекламодатель',
             'descr' => 'Описание'
        );

    public $currency_arr = array('RUR' => 'Руб.', 'USD' => 'USD', 'EUR' => 'EUR');


    /**
     * Сортировка 
     */
    var $sortLabels   = array(
                'user_name'     => 'user_name',
                'pay_date'      => 'pay_date',
                'balance'       =>  'balance',
                'credit'        =>  'credit',
                'debit'         =>  'debit',
                'campaign_name' =>  'campaign_name',
    );

    public $search_arr = array('fields_search' => array('campaigns.name', 'users.name'));

    public $qs_arr = array('user_id');

    public $default_sort = 'id DESC';

    // Права
    public $perms_arr = array(
        'list' => array('ar' => 1, 'oc' => 1, 'ou' => 1),
        'edit' => array('aa' => 0),
        'new' => array('aa' => 0),
        'delete' => array('aa' => 0),
        'download'  => array('ar' => 1, 'oc' => 1),
    );

    //public $downolad_list = true;

    function __construct()
    {
        if (isset($_SESSION['_authsession']['data']['id'])) {
            $this->user_id = $_SESSION['_authsession']['data']['id'];
        }

    }

    function tableRow()
    {
        return array(
                     'pay_date' => $this->pay_date,
                     'debit' =>  ($this->debit ? $this->debit : '-'.$this->credit).' '.$this->currency,
                     //'credit' =>  $this->credit.' '.$this->currency,
                     'balance' =>  '<b>'.$this->balance.'</b> '.$this->currency,
                     'descr' => $this->descr,

            );
    }


    /**
     * Создание транзакции для входящего платежа
     */
    function inputTransaction($user_id, $payment_in_id, $debit, $credit, $currency, $booking_id, $descr='')
    {
        //$balance_obj = clone $this;
        $balance_obj = DB_DataObject::factory('transactions');
        $balance_obj->getUserBalance($user_id, $currency);
        $this->balance = $balance_obj->balance;
        $this->user_id = $user_id;
        //echo $this->balance;
        $this->debit = $debit;
        $this->credit = $credit;
        $this->descr = $descr;
        if ($debit) {
            $this->balance += $debit;
        }
        if ($credit) {
            $this->balance -= $credit;
        }
        $this->currency = $currency;
        $this->payment_in_id = $payment_in_id;
        $this->booking_id = $booking_id;
        $this->pay_date = date(DB_DATE_FORMAT);
        return $this->insert();
    }

    function getUserBalance($user_id, $currency)
    {
        if (empty($this->user_id)) {
            $this->user_id = $user_id;
        }
        $this->currency = $currency;
        $this->orderBy('id DESC');
        $this->limit(1);
        $this->find(true);
        if ($this->N == 0) {
            $this->balance = 0;
        }
    }
/*
    function joinName()
    {
        $advertisers_obj = DB_DataObject::factory('advertisers');
        $users_obj = DB_DataObject::factory('users');
        $advertisers_obj->joinAdd($users_obj);
        $this->joinAdd($advertisers_obj);
        $this->selectAs();
        $this->selectAs($advertisers_obj, 'advertiser_%s');
        $this->selectAs($users_obj, 'user_%s');
    }

    function joinCampaign()
    {
        $campaign_obj = DB_DataObject::factory('campaigns');
        $this->joinAdd($campaign_obj, 'LEFT');
        $this->selectAs($campaign_obj, 'campaign_%s');
    }

    function preGenerateList()
    {
        $this->selectAs();
        if ($advertiser_id = DVS::getVar('advertiser_id')) {
            $this->advertisers_obj = DB_DataObject::factory('advertisers');
            $this->advertisers_obj->getAdvertiser($advertiser_id);
            $this->center_title = '<a href="?op=advertisers&act=show&id='.$this->advertiser_id.'">'
            .$this->advertisers_obj->user_name.'</a> / '.$this->center_title;
            unset($this->listLabels['user_name']);
        } else {
            $this->joinName();
        }
        $this->joinCampaign();
    }
*/
    /**
     * Оплата кампании
     */
/*
    function campaignTransaction($campaign_obj)
    {
        $this->getAdvertizerBalance($campaign_obj->advertiser_id);
        if ($this->balance < $campaign_obj->campaign_price) {
            return -1;
        }
        $this->payment_in_id = null;
        $this->debit = '';
        $this->balance -= $campaign_obj->campaign_price;
        $this->credit = $campaign_obj->campaign_price;
        $this->advertiser_id = $campaign_obj->advertiser_id;
        $this->campaign_id = $campaign_obj->campaign_id;
        $this->pay_date = date(DB_DATE_FORMAT);
        return $this->insert();
    }

    function dateOptions()
    {
        return array(   'language' => 'ru',
                        'format'=>'d-F-Y',
                        'minYear'=>date("Y")-1,
                        'maxYear'=>date("Y")+1,
                        'addEmptyOption' => true
            
        );
    }

    function setWhereDate($name)
    {
        $date_arr = $_GET[$name];
        if (intval($date_arr['Y']) > 0 && intval($date_arr['d']) > 0 && intval($date_arr['F']) > 0) {
            if ($name == 'end') {
                $date_arr['d'] = $date_arr['d']+1;
            }
            require_once 'DB/DataObject/FormBuilder.php';
            $date = DB_DataObject_FormBuilder::_array2date($date_arr);
            if (strtotime($date) > 0) {
                $this->whereAdd("pay_date ".($name == 'start' ? '>' : '<')." '$date'");
            }
        }
    }

    //Форма поиска для страницы администрирования

    function getSearchForm()
    {
        require_once('HTML/QuickForm.php');
        $form =& new HTML_QuickForm('search_form', 'get', $this->qs, '', 'class="search"');
        $form->addElement('hidden', 'op', 'transactions');
        $text = HTML_QuickForm::createElement('text', 'st', 'Искать', array('class' => 'sinp'));
        $submit = HTML_QuickForm::createElement('submit', 'search', '>>');
        $start = HTML_QuickForm::createElement('date', 'start', '', $this->dateOptions());
        $end = HTML_QuickForm::createElement('date', 'end', '', $this->dateOptions());
        $form->addGroup(array($text, $submit), '', '');
        //$form->addElement($text);
        $form->addGroup(array($start, $end), '', '', array('&nbsp;-&nbsp;'));
        return $form->toHtml();
    }

    function setSearch()
    {
        foreach ($this->search_arr['fields_search'] as $key=>$val) {
            $where .= 'UPPER('.$val.") LIKE '".strtoupper($_GET['st'])."%' OR ";
        }
        $this->whereAdd('('.substr($where, 0, -4).')');
        $this->setWhereDate('start');
        $this->setWhereDate('end');
    }

*/

    function preGenerateList()
    {
        $out = false;
        foreach ($this->currency_arr as $k => $v) {
            $balance_obj = DB_DataObject::factory('transactions');
            $balance_obj->getUserBalance($this->user_id, $k);
            $balance_arr[$k] = $balance_obj->balance;
            $str .= ($str ? ', ' : '').$balance_arr[$k].' '.$k;
            if ($balance_arr[$k] > 0) {
                $out = true;
            }
        }
        $this->page_arr['LEFT'] = '<br><b>Баланс: '.$str.'</b><br><br>';
        if ($this->role == 'oc' && $out) {
            $this->page_arr['LEFT'] .= '<br><a href="?op=payments_out&act=new">Заявка на вывод средств</a><br><br>';
        }
    }

    function getSearchForm()
    {
        require_once('HTML/QuickForm.php');
        $form =& new HTML_QuickForm('search_form', 'get', $this->qs, '', 'class="search"');
        $form->addElement('hidden', 'op', 'transactions');
        $text = HTML_QuickForm::createElement('select', 'currency', 'Выберите валюту:', $this->currency_arr);;
        $submit = HTML_QuickForm::createElement('submit', 'search', '>>');
        $form->addGroup(array($text, $submit), '', 'Выберите валюту:&nbsp;');

        
        //$form->addElement('select', 'currency', 'Выберите валюту:', $this->currency_arr);

        return $form->toHtml();
            //.'<br><a href="">Заявка на вывод средств</a><br><br>';
    }

    function bookingPayment($payment_in_id)
    {
        $payments_in_obj = DB_DataObject::factory('payments_in');
        $payments_in_obj->get($payment_in_id);
        if (!$payments_in_obj->id) {
            return false;
        }
        $user_id_from = $payments_in_obj->user_id;
        $ammount = $payments_in_obj->ammount;
        $currency = $payments_in_obj->currency;

        if ($payments_in_obj->booking_id) {
            $booking_obj = DB_DataObject::factory('booking');
            $booking_obj->get($payments_in_obj->booking_id);
            if (!$booking_obj->id) {
                return false;
            }
            $villa_obj = DB_DataObject::factory('villa');
            $villa_obj->get($booking_obj->villa_id);
            if (!$villa_obj->user_id) {
                return false;
            }
            $user_id_to = $villa_obj->user_id;
        }
        $this->fromToTransaction($from, $to, $ammount);
    }

    function fromToTransaction($from, $to, $ammount, $currency, $booking_id, $payment_in_id='')
    {
        $this->query('BEGIN');
        $res1 = $this->inputTransaction($from, $payment_in_id, $ammount, null, $currency, null, 'Пополнение счета');
        $res2 = $this->inputTransaction($from, '', null, $ammount, $currency, $booking_id, 'Оплата заявки № '.$booking_id);
        $res3 = $this->inputTransaction($to, '', $ammount, null, $currency, $booking_id, 'Оплата заявки № '.$booking_id);
        if ($res1 && $res2 && $res3) {
            //$this->msg = 'UPDATE_ROW';
            $this->query('COMMIT');
            return true;
        }
        return false;
    }


}
