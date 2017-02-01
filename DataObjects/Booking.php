<?php
/**
 * Table Definition for booking
 */
//require_once PROJECT_ROOT.'DataObjects/Booking_Form.php';

require_once 'DB/DataObject.php';

class DBO_Booking extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'booking';                         // table name
    public $_database = 'rurenter';                         // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)  unique_key not_null
    public $start_date;                      // date()  unique_key not_null
    public $end_date;                        // date()  unique_key not_null
    public $user_id;                         // int(4)  unique_key not_null
    public $book_status;                     // tinyint(1)   not_null
    public $price;                           // float()  
    public $prepay;                          // float()  
    public $currency;                        // varchar(11)  
    public $people;                          // tinyint(1)   not_null
    public $note;                            // text()  
    public $post_date;                       // datetime()   not_null
    public $post_ip;                         // varchar(64)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Booking',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'start_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_NOTNULL,
             'end_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'book_status' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
             'price' =>  DB_DATAOBJECT_INT,
             'prepay' =>  DB_DATAOBJECT_INT,
             'currency' =>  DB_DATAOBJECT_STR,
             'people' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
             'note' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'post_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME + DB_DATAOBJECT_NOTNULL,
             'post_ip' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
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

public $book_status_arr = array(
        1 => 'Бронь владельца',
        //2 => 'Новая заявка',
        2 => 'Зарезервировано',
        3 => 'Выставлен счет',
        4 => 'Оплачено',
        5 => 'Отменена',
        6 => 'Счет к оплате'
    );

public $book_status_colors = array(
        1 => 'ba',
        //2 => 'bb',
        2 => 'bc',
        3 => 'bd',
        4 => 'be',
        6 => 'bf',
    );


    /**
     * Заголовок для таблицы
     */
    public $center_title = 'Заявки на бронирование';

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form = 'бронь';

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
                     'villa_title'         => '',
                     'post_date'        => '',
                    'period' => '',
                     //'start_date'       =>  '',
                     //'end_date'         =>  '',
                     'book_status'      =>  '',
                     //'price'            =>  '',
                     //'currency'         =>  '',
    );

    public $fb_fieldLabels   = array(
                     'id' => '#',
                     'villa_title' => 'Объект',
                     'villa_id'         => 'Вилла',
                     'period' => 'Период',
                     'start_date'       =>  'Дата 1',
                     'end_date'         =>  'Дата 2',
                     'book_status'      =>  'Статус',
                     'price'            =>  'Цена',
                     'currency'         =>  'Валюта',
                    'post_date'       =>  'Дата добавления',
    );


    public $perms_arr = array(
        'new' => array('oc' => 0, 'aa' => 0),
        'list' => array('oc' => 1, 'ou' => 1),
        'edit' => array('oc' => 0, 'aa' => 0),
        'delete' => array('oc' => 1),
        'card' => array('oc' => 0),
    );

    public $qs_arr = array('villa_id');

    public $default_sort = 'id DESC';

    public $currency_arr = array('RUR' => 'Руб.', 'USD' => 'USD', 'EUR' => 'EUR');

    public $mode;


    function __construct()
    {
        //print_r($_SESSION);
        //$this->user_id = $_SESSION['_authsession']['data']['id'];
        $villa_id = DVS::getVar('villa_id');
        if ($villa_id) {
            $this->villa_id = $villa_id;
        }
    }


    function tableRow()
    {
        return array(
            'villa_title' =>  '<a href="?op=booking&act=showcard&id='.$this->id.'">'.$this->villa_title.'</a>',
            'period' => DVS_Page::RusDate($this->start_date).' - '.DVS_Page::RusDate($this->end_date),
            //'start_date'       =>  $this->start_date,
             //'end_date'         =>  $this->end_date,
             'book_status'      =>  $this->book_status_arr[$this->book_status],
             'post_date'        => DVS_Page::RusDate($this->post_date),
                'price'            =>  $this->price,
             'currency'         =>  $this->currency,
        );
    }

    function book2color($book_status)
    {
        if ($this->mode == 'userbook' || $this->mode == 'bookshow') {
            if ($book_status == 1 || $book_status == 3 || $book_status == 4 || $book_status == 6) {
                return $this->book_status_colors[3];
            }
            // Залипуха чтобы не показывать зарезервированные
            return '';
            if ($book_status >= 1 && $book_status <= 4) {
                return $this->book_status_colors[3];
            } else {
                return '';
            }
        }
        return $this->book_status_colors[$book_status];
    }

    public function ret_fields()
    {
        return array($this->book_status, $this->book2color($this->book_status));
    }

    function getLegend()
    {
        if ($this->mode == 'userbook' || $this->mode == 'bookshow') {
            return array(
                array('Занято', $this->book_status_colors[3]),
                array('Свободно', $this->book_status_colors[6]),
            );
        }
        foreach ($this->book_status_arr as $k => $v) {
            $ret[$k] = array($v, $this->book_status_colors[$k]);
        }
        return $ret;
    }

    function saveBook($villa_obj, $user_id, $start_date, $end_date, $book_status, $price='', $currency='', $people=0, $note='')
    {
        require_once COMMON_LIB.'DVS/Auth.php';
        $this->user_id = $user_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->book_status = $book_status;
        $this->price = $price;
        $this->currency = $currency;
        $this->people = $people;
        $this->note = $note;
        $this->post_date = date('Y-m-d H:i:s');
        $this->post_ip = DVS_Auth::getIP();
        $res = $this->insert();
        if ($res) {
            if ($this->book_status != 1) {
                $this->userLetter($villa_obj);
            }
            return true;
        }
        return false;
    }

    function preGenerateList()
    {
        if (isset($this->book_status) && $this->book_status == '') {
            unset($this->book_status);
        }

        //if (!$this->villa_id) {
            $villa_obj = DB_DataObject::factory('villa');
            //$str = $villa_obj->getVillaByUser($_SESSION['_authsession']['data']['id']);
            $this->selectAs();
            $this->joinAdd($villa_obj);
            $this->selectAs(array('title','user_id'), 'villa_%s', 'villa');
            
            //if ($_SESSION['_authsession']['data']['role_id'] != 'aa') {
                $this->whereAdd("villa.user_id=".$_SESSION['_authsession']['data']['id']);
            //}
            /*
            $villa_obj->user_id = $_SESSION['_authsession']['data']['id'];
            $villa_obj->find();
            while ($villa_obj->fetch()) {
                $str .= ($str ? ',' : '').$villa_obj->id;
            }
            */
            if ($str) {
                //echo $str;
                $this->whereAdd("villa_id IN(".$str.")");
            }
        //}
    }

    function getLetterInfo()
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->user_id);

    }

    function userLetter($villa_obj, $new=1)
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj2 = DB_DataObject::factory('users');


        //Владелец виллы
        $users_obj->get($villa_obj->user_id);
        //Арендатор
        $users_obj2->get($this->user_id);

        $smsinfo_obj = DB_DataObject::factory('smsinfo');
        $smsinfo_obj->user_id = $users_obj->id;
        $smsinfo_obj->find('true');

        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'New ruRenter.ru booking villa '.$this->villa_id;
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->id;
        //$booking_link = SERVER_URL."/office/?op=booking&act=admincard&id=".$this->id;
        $data_arr = array(
            'owner_name' => $users_obj->name,
            'owner_lastname' => $users_obj->lastname,
            'renter_name' => $users_obj2->name,
            'renter_lastname' => $users_obj2->lastname ? $users_obj2->lastname[0].'.' : '',
            'booking_link' => $booking_link,
            'villa_id' => $this->villa_id,
            'villa_name' => $villa_obj->title,
            'user_id' => $this->user_id,
            //'user_name' => $user_obj->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'people' => $this->people,
            );

        $data['body'] = DVS_Mail::letter($data_arr, 'booking_letter.tpl');
        if ($users_obj->email) {
            DVS_Mail::send($data);
        }

        if ($smsinfo_obj->phone && $smsinfo_obj->send_new) {
            $smsinfo_obj->sendSms($smsinfo_obj->phone, 'ruRenter.ru new Booking '.$this->id.' villa '.$this->villa_id.' '.$this->start_date.'-'.$this->end_date.' '.$this->people.' pers.');
        }


        if ($new) {
            $data['to'] = $_SESSION['_authsession']['username'];
            //$data_arr['booking_link'] = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->id;
            $data['body'] = DVS_Mail::letter($data_arr, 'renter_booking_letter.tpl');
            DVS_Mail::send($data);

            $data['to'] = 'info@villarenters.ru';
            $data['subject'] = 'ruRenter.ru booking '.$this->id;
            $data_arr['booking_link'] = SERVER_URL."/office/?op=booking&act=admincard&id=".$this->id;

            if ($this->note) {
                $data_arr['note'] = 'Примечания: '.$this->note."\n";
            }
            $data['body'] = DVS_Mail::letter($data_arr, 'booking_letter.tpl');
            DVS_Mail::send($data);
        }
    }

    function checkPermsOp()
    {
        return true;
    }

    function daysCount($start='', $end ='')
    {
        if (!$start && !$end) {
            $start = $this->start_date;
            $end = $this->end_date;
        }
        $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
        return ceil($days);
    }

    //Форма поиска для страницы администрирования
    function getSearchForm()
    {
        require_once('HTML/QuickForm.php');
        $form =& new HTML_QuickForm('search_form', 'get', $this->qs, '', 'class="search"');
        $form->addElement('hidden', 'op', 'booking');
        $st = $form->createElement('select', 'book_status', 'Статус :', array('' => 'Все') + $this->book_status_arr);
        /*
        $text = HTML_QuickForm::createElement('text', 'st', 'Искать', array('class' => 'sinp'));
        $start = HTML_QuickForm::createElement('date', 'start', '', $this->dateOptions());
        $end = HTML_QuickForm::createElement('date', 'end', '', $this->dateOptions());
        $form->addGroup(array($text, $submit), '', '');
        //$form->addElement($text);
        $form->addGroup(array($start, $end), '', '', array('&nbsp;-&nbsp;'));
        */
        $submit = HTML_QuickForm::createElement('submit', 'search', '>>');
        $form->addGroup(array($st, $submit), '', '');

        return $form->toHtml();
    }
    /*
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

    function preDelete()
    {
        if ($this->book_status < 3) {
            return true;
        } else {
            $this->msg = 'error status';
            return false;
        }
    }

    function invoiceLetter()
    {
        $users_obj = DB_DataObject::factory('users');
        
        // Арендатор
        $users_obj->get($this->user_id);

        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'ruRenter.ru booking';
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->id;
        //$booking_link = SERVER_URL."/office/?op=booking&act=admincard&id=".$this->id;
        $data_arr = array(
            'booking_link' => $booking_link,
            'villa_id' => $this->villa_id,
            'villa_name' => $villa_obj->title,
            'user_id' => $this->user_id,
            //'user_name' => $user_obj->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'people' => $this->people,
            'price' => $this->price,
            'prepay' => $this->prepay,
            'currency' => $this->currency,

            );
        //$data['to'] = $_SESSION['_authsession']['username'];
        //$data_arr['booking_link'] = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->id;
        $data['body'] = DVS_Mail::letter($data_arr, 'renter_invoice_letter.tpl');
        //DVS_Mail::send($data);


        $data['to'] = 'info@villarenters.ru';
        $data['subject'] = 'ruRenter.ru booking '.$this->id;
        $data_arr['booking_link'] = SERVER_URL."/office/?op=booking&act=admincard&id=".$this->id;
        $data['body'] = 'Владелец выставил счет по заявке №'.$this->id."\n".$data_arr['booking_link'];
        DVS_Mail::send($data);
    }


    function statusLetter()
    {
        $users_obj = DB_DataObject::factory('users');
        // Арендатор
        $users_obj->get($this->user_id);

        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'ruRenter.ru booking status';
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->id;
        //$booking_link = SERVER_URL."/office/?op=booking&act=admincard&id=".$this->id;
        $data_arr = array(
            'booking_link' => $booking_link,
            'villa_id' => $this->villa_id,
            'villa_name' => $villa_obj->title,
            'user_id' => $this->user_id,
            //'user_name' => $user_obj->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'people' => $this->people,
            'price' => $this->price,
            'prepay' => $this->prepay,
            'currency' => $this->currency,
            'status' => $this->book_status_arr[$this->book_status],
            'booking_id' => $this->id,
            );

        //$data['to'] = $_SESSION['_authsession']['username'];
        //$data_arr['booking_link'] = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->id;
        $data['body'] = DVS_Mail::letter($data_arr, 'booking_status.tpl');
        DVS_Mail::send($data);


        $data['to'] = 'info@villarenters.ru';
        $data['subject'] = 'ruRenter.ru booking '.$this->id;
        $data_arr['booking_link'] = SERVER_URL."/office/?op=booking&act=admincard&id=".$this->id;
        $data['body'] = 'Владелец изменил статус заявки №'.$this->id."\n".$data_arr['booking_link'];
        DVS_Mail::send($data);
    }
}
