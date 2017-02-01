<?php
/**
 * Table Definition for prices
 */
require_once 'DB/DataObject.php';

class DBO_Prices extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'prices';                          // table name
    public $_database = 'rurenter';                          // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)   not_null
    public $start_date;                      // date()   not_null
    public $end_date;                        // date()   not_null
    public $user_id;                         // int(4)   not_null
    public $price;                           // float()  
    public $currency;                        // varchar(11)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Prices',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'start_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_NOTNULL,
             'end_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'price' =>  DB_DATAOBJECT_INT,
             'currency' =>  DB_DATAOBJECT_STR,
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
    public $center_title = 'Цены';

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form = 'Цены';

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
                     //'start_date'       =>  '',
                     //'end_date'         =>  '',
                     'period' => '',
                     'price'            =>  '',
                     //'currency'         =>  '',
    );

    public $fb_fieldLabels   = array(
                    'period' => 'Период',
                     'start_date'       =>  'Дата 1',
                     'end_date'         =>  'Дата 2',
                     'price'            =>  'Цена',
                     'currency'         =>  'Валюта',
    );


    public $perms_arr = array(
        'new' => array('aa' => 0, 'ar' => 0, 'oc' => 0),
        'edit' => array('aa' => 0, 'ar' => 0, 'oc' => 0),
        'delete' => array('oc' => 1),
        'list' => array('oc' => 1),
        //'card' => array('oc' => 1),
    );

    public $qs_arr = array('villa_id');

    public $default_sort = 'start_date';

    public $currency_arr = array('RUR' => 'Руб.', 'USD' => 'USD', 'EUR' => 'EUR');

    public $price_arr = array();

    public $price_colors = array(
    //        'a',
            'b',
            'c',
            'd',
//            'e',
            'f',
            'g',
            'h',
            'i',
            'j'
        );


    function __construct()
    {
        //print_r($_SESSION);
        //$this->user_id = $_SESSION['_authsession']['data']['id'];
        $this->villa_id = DVS::getVar('villa_id');
    }


    function tableRow()
    {

        return array(
            'period' => DVS_Page::RusDate($this->start_date).' - '.DVS_Page::RusDate($this->end_date),
            //'end_date' => DVS_Page::RusDate($this->end_date),
            'price' => $this->price.' '.$this->currency,
        );
    }

    function preGenerateList()
    {
        //$this->user_id = $_SESSION['_authsession']['data']['id'];
        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($this->villa_id);
        $this->center_title .= ' / <a href="?op=booking&act=show&mode=price&villa_id='.$this->villa_id.'">'.$villa_obj->title_rus.' > календарь</a>';
    }

    function getForm()
    {
        $this->src_files = array(
            'JS_SRC' => array(  '/js/jquery-1.3.1.min.js',
                                '/js/dp/datePicker2.js',
                                '/js/dp/date.js',
                                '/js/dp/date_ru_win1251.js',
        ),
            'CSS_SRC'  => array('/css/datePicker.css',
                                '/css/demo1.css',
                                //'/datepicker/css/layout.css',
        )
        );

    $this->page_arr['JSCRIPT'] =
            "$(function()
            {
                $('.datepicker').datePicker();
            });";

        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '');
        $form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        $form->addElement('text', 'start_date', 'Дата 1:', array(
                                                        'id' => 'date1',
                                                        'class' => "input datepicker",
                                                        'onkeydown' => "return false;"
                                                    ));
        $form->addElement('text', 'end_date', 'Дата 2:', array(
                                                        'id' => 'date1',
                                                        'class' => "input datepicker",
                                                        'onkeydown' => "return false;"
                                                    ));
        //$form->addElement('text', 'price', 'Цена:');

        $t2 = $form->createElement('text', 'price', '', 'size=5');
        $t3 = $form->createElement('select', 'currency', '', $this->currency_arr);
        $form->addGroup(array($t2, $t3), 'pr', 'Цена (в неделю): ', ' ', 0);

        $form->addElement('submit', '__submit__', DVS_SAVE);

        $form->addRule('start_date', DVS_REQUIRED.' "Дата 1"!', 'required', null, 'client');
        $form->addRule('end_date', DVS_REQUIRED.' "Дата 2"!', 'required', null, 'client');
        $form->addRule('pr', DVS_REQUIRED, 'required', null);
        
        $form->addGroupRule('pr', array(
        'price' => array(
        //array('Name is letters only', 'lettersonly'),
        array(DVS_REQUIRED.' Цена!', 'required', null, 'client')
            ),
        ));

        $form->addFormRule(array('DBO_Prices', 'checkDates'));

        return $form;
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
    
    function makeDatePeriod($start, $end, $price)
    {
        require_once 'Date.php';
        $next_day = $start;
        $i = 0;
        while ($next_day != $end) {
            $i++;
            if ($i > 366) {
                return $ret;
            }
            $ret[$next_day] = $price;
            $date_arr = explode('-', $next_day);
            $next_day = Date_Calc::nextDay($date_arr[2], $date_arr[1], $date_arr[0], '%Y-%m-%d');
        }
        $ret[$end] = $price;
        return $ret;
    }

    function getPricesArray($villa_id)
    {
        $dates = array();
        $prices = array();
        $this->villa_id = $villa_id;
        $this->orderBy('start_date');
        $this->find();
        while ($this->fetch()) {
            $dates += $this->makeDatePeriod($this->start_date, $this->end_date, $this->price);
            $prices[$this->price] = 1;
        }
        $colors = $this->color($prices);
        return array($dates, $colors);
    }

    function color($prices)
    {
        $cl = array(
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j'
        );
        $prices_arr = array_keys($prices);
        for ($i = 0; $i < sizeof($prices); $i++) {
            $prices[$prices_arr[$i]] = $i < 10 ? $cl[$i] : $cl[0];
        }
        //ksort($prices);
        return $prices;
    }

    public static function convertDate($str)
    {
        $arr = explode(".", $str);
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }
*/
    function preProcessForm(&$vals, &$fb)
    {
        $fb->dateToDatabaseCallback = null;
        $vals['start_date'] = $this->convertDate($vals['start_date']);
        $vals['end_date'] = $this->convertDate($vals['end_date']);
    }

    /**
    Задает цвет для цены

    */
    function price2color()
    {
        if (!$this->price_arr[$this->price]) {
            $this->price_arr[$this->price] = sizeof($this->price_arr)+1;
        }
        $color = $this->price_colors[$this->price_arr[$this->price]] ? $this->price_colors[$this->price_arr[$this->price]] : 'a';
        return $color;
    }
    /**
    Array
    (
        [3000] => 2
        [5000] => 2
        [6000] => 3
        [7000] => 1
    )
    */
    function getLegend()
    {
        ksort($this->price_arr);
        //print_r($this->price_arr);
        
        foreach ($this->price_arr as $k => $v) {
            //echo "$k => $v<br>";
            $ret[$v] = array($k, $this->price_colors[$v]);
        }

        return $ret;
    }

    public function ret_fields()
    {
        return array($this->price, $this->price2color());
    }

    public function checkPrice($villa_id)
    {
        $this->villa_id = $villa_id;
        return $this->count();
    }

    function preDelete()
    {
        $this->qs = '?op=booking&act=show&mode=price&villa_id='.$_GET['villa_id'];
        return true;
    }
}
