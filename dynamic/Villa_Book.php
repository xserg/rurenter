<?php
/**
 * Карточка кампании
 * @package web2matrix
 * $Id: Villa_Book.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once ('HTTP/Request2.php');
require_once COMMON_LIB.'DVS/Dynamic.php';

require (PROJECT_ROOT.'/layout/getXML.php');
require (PROJECT_ROOT.'/layout/activecalendar.php');


class Project_Villa_Book extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ar' => 1, 'oc' => 1, 'iu' => 1);

    private $change_content = true;


    public $nav_arr = array(
        '/villa/{VILLA_ID}.html' => 'Назад',
        '/villa/{VILLA_ID}.html#photos-bar' => 'Фото и описания',
        '/villa/{VILLA_ID}.html#facility-bar' => 'Услуги и оборудование',
        '/villa/{VILLA_ID}.html#location-bar' => 'Расположение и окрестности',
        '/?op=villa&act=book&id={VILLA_ID}' => 'Цены и бронирование',
        '/?op=comments&act=show&villa_id={VILLA_ID}' => 'Отзывы',
        '/?op=query&act=new&villa_id={VILLA_ID}' => 'Задать вопрос'
    );


    function getPageData()
    {        
        $ref = DVS::getVar('ref');
        exit;
        if ($ref) {
            //echo '<pre>';
            //print_r($_GET);
            $start = DVS::getVar('start');
            $end = DVS::getVar('end');
            $people = DVS::getVar('extra_people', 'int', 'post');
            //$this->bookingLog($ref, $start, $end, $people);

            //header('location: '.BOOKING_URL.'?ref='.$ref.'&start='.$start.'&end='.$end.'&rag='.VR_ACCOUNT_NUM.'&extra_people='.$people.'&ret=true&rcam=');

            //exit;
        }

        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }
        $this->createTemplateObj();

        $this->db_obj->nav_arr = $this->nav_arr;
        $nav = $this->db_obj->showNavigation($this->template_obj);


        $this->template_obj->loadTemplateFile('villa_book.tpl');
        $calendar = $this->getActiveCalendar();
        if ($calendar) {
            $form = $this->calendarForm($this->db_obj->sleeps);
        } else {
            $calendar = "<b>Владелец не определил цену на данный объект</b>";
        }

        $this->template_obj->setVariable(array(
                'BOOKING_TITLE' => 'Бронирование виллы "'.$this->db_obj->title.'"<br><br>',
                'BOOKING_FORM' => $form,
                'CALENDAR' => $calendar,
                'VILLA_NAV' => $nav,
            ));

        $page_arr['BODY_CLASS']   = 'property';
        //$page_arr['CENTER_TITLE']   = 'Бронирование виллы "'.$this->db_obj->title.'"<br><br>';
        $page_arr['CENTER']         = $this->template_obj->get();
        //$page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        return $page_arr;
    }


    /**
    prop_ref] => 9336
    [currency] => ВЈ (GBP)
    [minNotice] => 3
    [minBookingPeriod] => 0
    [PropertyAvailabilities] => SimpleXMLElement Object
        (
            [PropertyAvailability] => Array
                (
                    [0] => SimpleXMLElement Object
                        (
                            [intYear] => 2010
                            [intMonth] => 3
                            [MonthAvailability] => 0,0,0,0,0,0,0,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
                            [MonthPricing] => 450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450,450
                        )

      @return array(
      [0]
      1 - занято
      0 - свободно
      [1]
        цена
      Array
        (
            [2010-10-1] => Array
                (
                    [0] => 1
                    [1] => 800
                )

            [2010-10-2] => Array
                (
                    [0] => 1
                    [1] => 800
                )


    */
    function showBook()
    {
        $request_obj = new GetXML;
        $request_obj->data_url['GetPropertyAvailability']['params']['prop_ref'] = $this->db_obj->id;
        $xml = $request_obj->requestXMLdata('GetPropertyAvailability');
        //print_r($xml);

        $this->currency = $xml->currency;
        $minBookingPeriod = $xml->minBookingPeriod;
        foreach($xml->PropertyAvailabilities->PropertyAvailability as $k => $v) {
            $days_str = '';
            $date_str = $v->intYear.'-'.$v->intMonth;
            $days_arr = explode(',',strval($v->MonthAvailability));
            $price_arr = explode(',',strval($v->MonthPricing));
            $i = 0;
            foreach ($days_arr as $num => $aval) {
                if ($aval == 1) {
                    $date_arr[] = array(intval($v->intYear), intval($v->intMonth), $num+1);
                    $jsstr .= ($jsstr ? ',' : '')."'".$date_str.'-'.($num+1)."'";
                }
                if ($aval >= 0) {
                    $date_price_arr[$date_str.'-'.($num+1)] = array($aval, $price_arr[$num]);
                }
                $i++;
            }
        }
        echo '<pre>';
        print_r($date_price_arr);
        //print_r($date);
        //echo $jsstr;
        //var_dump($available);
        //return $jsstr;
        //return $date_arr;
        return $date_price_arr;

    }

    function getPrices()
    {
        $prices_obj = DB_DataObject::factory('prices');
        $prices_obj->villa_id = $this->db_obj->id;
        $prices_obj->orderBy('start_date');
        $prices_obj->find();
        while ($prices_obj->fetch()) {
            $ret[$prices_obj->start_date.':'.$prices_obj->end_date] = $prices_obj->price;
        }
        return $ret;
    }

    function getActiveCalendar()
    {
        /*
        $this->src_files = array(
            'JS_SRC' => array(  '/js/pickdate1.js',
                                        ),
            'CSS_SRC'  => array(
                                 '/activecalendar/data/css/plain.css'
                               ),
            
        );
        */
        $this->src_files = array(
            'JS_SRC' => array(  '/js/pickdate_price.js',
                                        ),
            'CSS_SRC'  => array(
                                 '/css/calendar.css'
                               ),
        );
        $day_names_ru = array(
            'вс','пн','вт','ср','чт','пт','сб',
        );
        $monthNamesArray_ru = array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

        $cal = new activeCalendar($yearID,$monthID,$dayID);
        //$cal->enableYearNav($myurl,$arrowBack,$arrowForw); // enables navigation controls
        //$cal->enableDatePicker(2000,2010,$myurl); // enables date picker (year range 2000-2010)
        $cal->enableDayLinks(false,"myDate"); 

//        $cal->enableDayLinks(''); 


        $cal->setDayNames($day_names_ru);
        $cal->setMonthNames($monthNamesArray_ru); 
        $cal->setFirstWeekDay(1); 
        
        $date_arr = $this->showBook();
        //print_r($date_arr);
        /*
        if (is_array($date_arr)) {
            foreach ($date_arr as $k => $a) {
                $cal->setEvent($a[0], $a[1], $a[2], '', 'javascript:unavaildate(\''.$a[2].'/'.$a[1].'/'.$a[0].'\',\''.$a[2].'\',\''.$a[1].'\',\''.$a[0].'\')');
            }
        }
        */
        //$date_arr = array();

        //$date_arr = $this->getPrices();
        //print_r($date_arr);

        if (is_array($date_arr)) {
            foreach ($date_arr as $k => $a) {
                $da = explode('-', $k);
                if ($a[0] == 1) {
                    $cal->setEvent($da[0], $da[1], $da[2], '', 'javascript:unavaildate(\''.$da[2].'/'.$da[1].'/'.$da[0].'\',\''.$da[2].'\',\''.$da[1].'\',\''.$da[0].'\')" title="'.$a[1]);
                } else {
                    $cal->setEvent($da[0], $da[1], $da[2], 'monthday', 'javascript:myDate('.$da[0].','.$da[1].','.$da[2].')" onmouseover=temp_prices('.$a[1].') onmouseout=temp_prices(0) title="Weekly Price: '.$a[1].' ""');
                }
            }
            return $cal->showYear(4, date('m'));
        } else {
            return false;
        }
    }


    function dateOptions()
    {
        return array(   'language' => 'ru',
                        'format' =>'d-F-Y',
                        'minYear' =>date("Y"),
                        'maxYear' =>date("Y")+2,
                        'addEmptyOption' => true
            
        );
    }

    function calendarForm1($slleps=5)
    {
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('calendar', '', $_SERVER['REQUEST_URI']);
        $form->addElement('text', 'date1');
        $form->addElement('text', 'date2');
        $form->addElement('hidden', 'day');
        $form->addElement('hidden', 'req1');
        $form->addElement('hidden', 'req2');
        $d1 = $form->createElement('date', 'ds1', '', $this->dateOptions());
        $d2 = $form->createElement('date', 'd2', '', $this->dateOptions());
        for ($i = 1; $i <= $slleps; $i++) {
            $sleeps_arr[$i] = $i;
        }
        $s = $form->createElement('select', 'extra_people', 'sl', $sleeps_arr);
        $b = $form->createElement('submit', '','Отправить');
        $form->addGroup(array($d1,$d2,$s,$b), '', 'Начало:&nbsp; ', array('&nbsp;Конец:&nbsp; ', '&nbsp;Человек:&nbsp; ', '&nbsp;'));
        $form->addElement('text', 'temp_price', 'Цена в неделю, '.$this->currency.' :');
        /*
        require_once 'HTML/QuickForm/Renderer/ITDynamic.php';
        $this->db_obj->renderer_obj = new HTML_QuickForm_Renderer_ITDynamic($this->template_obj);
        $this->form_obj->accept($this->db_obj->renderer_obj);
        */
        /*
        require_once 'HTML/QuickForm/Renderer/Default.php';
        $renderer =& new HTML_QuickForm_Renderer_Default();
        //echo $renderer->toHtml();
        $renderer->setFormTemplate ( "\n<form{attributes}>\n<table width=100% border=\"1\">\n{content}\n</table>\n</form>"  );
        $form->accept($renderer);
        */
       return $form->tohtml();
    }

    function calendarForm()
    {
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('calendar', '', $_SERVER['REQUEST_URI']);
        $form->addElement('hidden', 'date1');
        $form->addElement('hidden', 'date2');
        $form->addElement('text', 'start_date', 'Начало периода:');
        $form->addElement('text', 'end_date', 'Конец:');
        //$form->addElement('text', 'price', 'Цена в неделю, '.$this->currency.' :');
        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addRule('start_date', DVS_REQUIRED.' "Дата 1"!', 'required', null, 'client');
        $form->addRule('end_date', DVS_REQUIRED.' "Дата 2"!', 'required', null, 'client');
        //$form->addRule('price', DVS_REQUIRED.' Цена!', 'required', null, 'client');
        $form->setRequiredNote('');
       return $form->tohtml();
    }

    function convertDate($str)
    {
        $arr = explode("/", $str);
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }

    function bookingLog($villa_id, $start, $end, $people)
    {
        require_once ('DVS/Auth.php');
        $booking_log_obj = DB_DataObject::factory('booking_log');
        $booking_log_obj->villa_id = $villa_id;
        $booking_log_obj->start_date = $this->convertDate($start);
        $booking_log_obj->end_date = $this->convertDate($end);
        $booking_log_obj->people = $people;
        $booking_log_obj->post_date = date("Y-m-d H:i:s");
        $booking_log_obj->ip = DVS_Auth::getIP();
        $booking_log_obj->insert();
    }
}

?>