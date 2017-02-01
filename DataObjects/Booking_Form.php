<?php

require_once 'DB/DataObject.php';
require_once 'Date.php';

class Booking_Form
{

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
                     'start_date'       =>  '',
                     'end_date'         =>  '',
                     'price'            =>  '',
                     'currency'         =>  '',
    );

    public $fb_fieldLabels   = array(
                     'start_date'       =>  'Дата 1',
                     'end_date'         =>  'Дата 2',
                     'price'            =>  'Цена',
                     'currency'         =>  'Валюта',
    );


    public $perms_arr = array(
        'new' => array('oc' => 1),
        'list' => array('oc' => 1),
        'edit' => array('oc' => 1),
        'delete' => array('oc' => 1),
        'card' => array('oc' => 1),
    );

    public $qs_arr = array('villa_id');


    public $currency_arr = array('RUR' => 'Руб.', 'USD' => 'USD', 'EUR' => 'EUR');

    public $legend = array();

    private $mode;

    private $currency;

    private $pay_period;

    function __construct($words)
    {
        //print_r($_SESSION);
        $this->user_id = $_SESSION['_authsession']['data']['id'];
        $this->villa_id = DVS::getVar('villa_id');
        $this->words = $words;
    }

    function checkDates($vals)
    {
        $t1=strtotime(DBO_Prices::convertDate($vals['start_date']));
        $t2=strtotime(DBO_Prices::convertDate($vals['end_date']));
        if (($t2-$t1) <= 0 ) {
            return array('end_date' => 'Конечная дата должна быть больше начальной!');
        }
        return true;
    }

    /**
     Создание массива дат из отрезка
    */
    function makeDatePeriod($start, $end, $params=array())
    {
        $next_day = $start;
        $i = 0;
        while ($next_day != $end) {
            $i++;
            if ($i > 366) {
                return $ret;
            }
            $ret[$next_day] = $params;
            if ($i == 1) {
                $ret[$next_day][2] = 'bbd';
            }
            $date_arr = explode('-', $next_day);
            $next_day = Date_Calc::nextDay($date_arr[2], $date_arr[1], $date_arr[0], '%Y-%m-%d');
        }
        $ret[$end] = $params;
        $ret[$end][2] = 'ebd';
        return $ret;
    }

    function getBookArray($villa_id, $db_obj)
    {
        $dates = array();
        $db_obj->villa_id = $villa_id;
        $db_obj->whereAdd('end_date > NOW()');
        $db_obj->orderBy('start_date');
        $db_obj->find();
        while ($db_obj->fetch()) {
            $dates += $this->makeDatePeriod($db_obj->start_date, $db_obj->end_date, $db_obj->ret_fields());
        }
        return $dates;
    }


    function getVillarentersBook($id)
    {
        require_once ('HTTP/Request2.php');
        require (PROJECT_ROOT.'/layout/getXML.php');
        $request_obj = new GetXML;
        $request_obj->data_url['GetPropertyAvailability']['params']['prop_ref'] = $id;
        $xml = $request_obj->requestXMLdata('GetPropertyAvailability');
        if ($xml) {
            $this->currency = $xml->currency;
            $minBookingPeriod = $xml->minBookingPeriod;
            $price_arr = array();
            $book_arr = array();
            foreach($xml->PropertyAvailabilities->PropertyAvailability as $k => $v) {
                $days_str = '';
                $date_str = $v->intYear.'-'.$v->intMonth;
                $days_arr = explode(',',strval($v->MonthAvailability));
                $price_arr = explode(',',strval($v->MonthPricing));
                $i = 0;
                foreach ($days_arr as $num => $aval) {
                    $date = $date_str.'-'.($num+1);
                   if ($aval >= 0) {
                        //$date_price_arr[$date_str.'-'.($num+1)] = array($aval, $price_arr[$num]);
                        $price[$date] = array($price_arr[$num], 'a');
                        $book_arr[$date] = array($aval, $aval ? 'bd' : '');
                    }
                    $i++;
                }
            }
            return array($price, $book_arr);
        }
    }



//    function getBookPriceArray($villa_id, $mode='bookstat')
    function getCalendar($villa_obj, $mode='bookstat')
    {
        $this->mode = $mode;
        $this->currency = $villa_obj->currency;
        $this->pay_period = $villa_obj->getPay_periodName($this->lang);

        $prices_obj = DB_DataObject::factory('prices');
        $price_arr = $this->getBookArray($villa_obj->id, $prices_obj);
       
        if ($mode != 'price') {
            $booking_obj = DVS_Dynamic::createDbObj('booking');
            $booking_obj->mode = $mode;
            $book_arr = $this->getBookArray($villa_obj->id, $booking_obj);
            $this->legend = $booking_obj->getLegend();
        } else {
            $this->legend = $prices_obj->getLegend();
        }
       
        /*
        echo '<pre>';
        echo 'PRICES';
        print_r($price_arr);
       
        echo 'BOOKINGS';
        print_r($book_arr);
        */
        $calendar = $this->getActiveCalendar($mode, $price_arr, $book_arr);
        return $calendar;
    }


    public static function convertDate($str)
    {
        $arr = explode(".", $str);
        $arr[1] = strlen($arr[1]) == 1 ? '0'. $arr[1] : $arr[1];
        $arr[0] = strlen($arr[0]) == 1 ? '0'. $arr[0] : $arr[0];
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }

    private function priceTitle()
    {
        return 'Цена ('.$this->pay_period.') '.$this->currency.': ';
    }

    function preProcessForm(&$vals, &$fb)
    {
        $fb->dateToDatabaseCallback = null;
        $vals['start_date'] = $this->convertDate($vals['start_date']);
        $vals['end_date'] = $this->convertDate($vals['end_date']);
    }


    /**
     * Вывод календаря
     * @parametr $mode
     *  'price' - Ввод цен владельцем, различие по цвету (Владелец)
     *  'bookstat' - Все брони, различие по цвету статуса (Владелец)
     *  'userbook' - Бронь пользователей, бронь владельца,
     *   зарезервированные и оплаченные различие - свободно.занято (Пользователь) + форма бронирования
     *  'bookshow' - показать бронь для незарегистрированных пользователей (только календарь)
     * 
     * PRICES
                Array
                    (
                        [2010-07-25] => Array
                            (
                                [0] => 444
                                [1] => a
                            )
       BOOKINGS
                Array
                (

                    [2010-10-04] => Array
                        (
                            [0] => 1
                        )

     */
    function getActiveCalendar($mode='price', $price_arr=array(), $book_arr=array())
    {
        $day_names_ru = array(
            'вс','пн','вт','ср','чт','пт','сб',
        );
        $monthNamesArray_ru = array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
        $cal = new activeCalendar($yearID,$monthID,$dayID);
        $cal->enableDayLinks(false,"myDate"); 
        $cal->setDayNames($day_names_ru);
        $cal->setMonthNames($monthNamesArray_ru); 
        $cal->setFirstWeekDay(1); 
        if ($mode != 'price' && is_array($book_arr) && is_array($price_arr)) {
            $diff = array_diff_key($book_arr, $price_arr);
        }
        /*
            echo '<pre>';
            print_r($diff);
        */
        if (is_array($price_arr)) {
            foreach ($price_arr as $k => $a) {
                $da = explode('-', $k);
                if ($mode == 'price') {
                    $cal->setEvent($da[0], $da[1], $da[2], 'event'.' '.$a[1], 'javascript:unavaildate(\''.$da[2].'.'.$da[1].'.'.$da[0].'\')" title="'.$this->priceTitle().$a[0]);
                //} else  if ($mode == 'bookstat') {
                } else {
                    if ($book_arr[$k][1] && !$book_arr[$k][2]) {
                        $cal->setEvent($da[0], $da[1], $da[2], 'event '.$book_arr[$k][1], 'javascript:unbook(\''.$da[2].'.'.$da[1].'.'.$da[0].'\')" title="'.$this->priceTitle().$a[0]);
                    } else {
                        // Первый - последний день периода
                        $cal->setEvent($da[0], $da[1], $da[2], ($book_arr[$k][1] && $book_arr[$k][2]) ? 'event '.$book_arr[$k][2] : 'monthday', 'javascript:myDate('.$da[0].','.$da[1].','.$da[2].')" title="'.$this->priceTitle().$a[0]);
                    }
                }
            }
        } else {
            return false;
        }
        if (is_array($diff)) {
            foreach ($diff as $k => $a) {
                $da = explode('-', $k);
                    if ($book_arr[$k][1] && !$book_arr[$k][2]) {
                    //if ($a[1]) {

                        $cal->setEvent($da[0], $da[1], $da[2], 'event'.' '.$a[1], 'javascript:unbook(\''.$da[2].'.'.$da[1].'.'.$da[0].'\')" title=""');
                    } else {

                        $cal->setEvent($da[0], $da[1], $da[2], ($book_arr[$k][1] && $book_arr[$k][2]) ? 'event '.$book_arr[$k][2] : 'monthday', 'javascript:myDate('.$da[0].','.$da[1].','.$da[2].')" title=""');
                    }
            }
        }
        return $cal->showYear(4, date('m'));

    }


    function calendarForm($sleeps, $currency, $pay_period)
    {
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('calendar', '', $_SERVER['REQUEST_URI']);
        $form->addElement('hidden', 'date1');
        $form->addElement('hidden', 'date2');
        $form->addElement('text', 'start_date', $this->words['start_date'].':', array(' readonly' => 'on'));
        $form->addElement('text', 'end_date', $this->words['end_date'].':', array(' readonly' => 'on'));

        for ($i = 1; $i <= $sleeps; $i++) {
            $sleeps_arr[$i] = $i;
        }

        //$s = $form->createElement('select', 'extra_people', 'sl', $sleeps_arr);
        if ($this->mode == 'userbook') {
            $form->addElement('select', 'people', $this->words['people'].': ', $sleeps_arr);
            $form->addElement('textarea', 'note', $this->words['note'].': ');

        }

        if ($this->mode == 'price') {
            $form->addElement('text', 'price', $this->words['price'].' '.$pay_period.', '.$currency.' :');
            $form->addRule('price', DVS_REQUIRED.' '.$this->words['price'].'!', 'required', null, 'client');
        }

        if ($this->mode == 'userbook') {
            $form->addElement('submit', 'next', DVS_NEXT);
        } else {
            $form->addElement('submit', '__submit__', DVS_SAVE);
        }
        
        $form->addRule('start_date', DVS_REQUIRED.' '.$this->words['start_date'].'!', 'required', null, 'client');
        $form->addRule('end_date', DVS_REQUIRED.' '.$this->words['end_date'].'!', 'required', null, 'client');
        $form->setRequiredNote('');
        $form->setJsWarnings  ( DVS_ERROR, '');
       return $form->tohtml();
    }


    function dateDiff($start, $end)
    {
        $s = explode('-', $start);
        $e = explode('-', $end);
        $diff = Date_Calc::dateDiff($e[2], $e[1], $e[0], $s[2], $s[1], $s[0]);
        return $diff;
    }

    function getPrice($villa_obj, $start, $end)
    {
        $this->villa_id = $villa_obj->id;
        $prices_obj = DB_DataObject::factory('prices');
        $price_arr = $this->getBookArray($villa_obj->id, $prices_obj);
        //echo "$start, $end";
        //echo $this->dateDiff($start, $end);
        //echo '<pre>';
        //print_r($price_arr);
        //Date_Calc::dateDiff($day1,$month1,$year1,$day2,$month2,$year2)
        $pay_period = $villa_obj->pay_period == 1 ? 7 : 1;
        $in_period = false;
        if ($price_arr[$start] && $price_arr[$end]) {
            foreach($price_arr as $date => $price_arr) {
                if ($date == $start) {
                    $in_period = true;
                }
                if ($date == $end) {
                    break;
                }
                if ($in_period) {
                    $price += $price_arr[0] / $pay_period;
                }

            }
        }
        return round($price);
    }

    public function showObjTable($obj_name, $current=true)
    {
        require_once COMMON_LIB.'DVS/ShowList.php';
        require_once 'HTML/Template/Sigma.php';
        $obj = DVS_Dynamic::createDbObj($obj_name);
        $obj->qs = '?op='.$obj_name.'&villa_id='.$obj->villa_id;
        if ($current) {
            $obj->whereAdd("end_date > NOW()");
        }
        $list_obj = new DVS_ShowList;
        $list_obj->role = $this->role;
        $list_obj->db_obj = $obj;
        $list_obj->template_obj = new HTML_Template_Sigma(PROJECT_ROOT.TMPL_FOLDER, PROJECT_ROOT.CACHE_FOLDER.'tmpl');
        $table = $list_obj->showTable();
        if ($obj->N > 0) {
            return $table;
        }
    }
}
