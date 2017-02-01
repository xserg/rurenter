<?php
/**
 * Календарь цен. брони, заявка
 * @package 
 * $Id: Booking_Show.php 368 2013-03-29 11:03:38Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once PROJECT_ROOT.'/layout/activecalendar.php';
require_once PROJECT_ROOT.'DataObjects/Booking_Form.php';

require_once COMMON_LIB.'DVS/ShowList.php';

define('DVS_BOOKING_ADD', 'Ваша заявка на бронирование принята.<br>Владелец объекта свяжется с вами в ближайшее время.<br>Вы можете проверить статус заявки в <a href=/office/?op=booking>личном офисе пользователя</a>');

class Project_Booking_Show extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ar' => 1, 'oc' => 1, 'iu' => 1);

    private $book_status;

    public $mode = 'userbook';

    private $lang_ru = array(
        'price' => 'Цена',
        'Sleeps' => 'спальных мест:',
        'people' => 'Кол-во человек',
        'booking_add' => 'Ваша заявка на бронирование принята.<br>Владелец объекта свяжется с вами в ближайшее время.<br>Вы можете проверить статус заявки в <a href=/office/?op=booking>личном офисе пользователя</a>',
        'Add_prices for' => 'Ввод цен для',
        'Booking for' => 'Бронь виллы',
        'form_help1' => 'Для того, чтобы выбрать даты, кликните числа на календаре.',
        'form_help2' => 'Выберите даты, когда Ваш объект <b>не будет</b> сдаваться в аренду.<br>(Бронь владельца)<br>Для того, чтобы выбрать даты, кликните числа на календаре.',
        'form_help3' => 'Для того, чтобы ввести заявку на бронирование, <a href="/reg/">зарегистрируйтесь</a> или <a href="/office/">авторизуйтесь</a> на сайте',
    //.', <br> или войдите через социальные сети:<br><br>',
        'start_date'  =>  'Начало периода',
        'end_date'  =>  'Конец',
        'table_text' => 'Посмотреть в виде таблицы / Редактировать',
        'note' => 'Примечания',
    );

    private $lang_en = array(
        'price' => 'Price',
        'Sleeps' => 'sleeps:',
        'people' => 'people',
        'booking_add' => 'Your booking saved.<br>The property owner will contact you soon.<br>You can check booking status in the <a href=/office/?op=booking>office</a>',
        'Add_prices for' => 'Add prices for',
        'Booking for' => 'Booking for',
        'form_help1' => 'Click dates to choose',
        'form_help2' => 'Choose dates when you <b>dont want</b> this property to be rented<br>(Booking by Owner)<br>Click dates to choose.',
        'form_help3' => 'For booking, <a href="/office/">sign in</a>',
        'start_date'  =>  'Start date',
        'end_date'  =>  'End date',
        'table_text' => 'Show / Edit',
        'note' => 'Notes',

    );

    function getPageData()
    {        

        @session_start();

        if ($_POST['token']) {
            require_once COMMON_LIB.'DVS/Auth_Loginza.php';
            $auth_obj = new DVS_Auth_loginza();
            $auth_obj->authLoginza();
        }
        //print_r($_SESSION);
        /*
        if ($_SESSION['_authsession']['data']['role_id'] == 'oc' ) {
            if (!$this->perm_obj->checkPermsByField()) {
                $this->msg = 'error_forbidden3';
                return;
            }
        }
        */

        $this->mode = DVS::getVar('mode');
        $this->words = $this->{'lang_'.$this->lang};

        if ($_SESSION['_authsession']['registered'] && $_SESSION['_authsession']['username'] && $this->iface == 'i') {
            $this->mode = 'userbook';
        }

        if (!$this->mode && $this->iface == 'i') {
            $this->mode = 'bookshow';
        }

        //echo $this->mode;

        $villa_id = DVS::getVar('villa_id');
        $villa_obj = DVS_Dynamic::createDbObj('villa');

        $villa_obj->get($villa_id);

        if (!$villa_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        $this->villa_id = $villa_id;
        $this->currency = $villa_obj->currency;

        if ($this->mode == 'price') {
            $page_arr['CENTER_TITLE']   = $this->words['Add_prices for'].' "'.$villa_obj->title.'"';
            $step = 5;
            $table_link = '?op=prices&villa_id='.$villa_id;
            $form_text = $this->words['form_help1'];
        } else if ($this->mode == 'bookstat') {
            $page_arr['CENTER_TITLE']   = $this->words['Booking for'].' "'.$villa_obj->title.'"';
            $step = 6;
            $table_link = '?op=booking&villa_id='.$villa_id;
            $form_text = $this->words['form_help2'];
        } else if ($this->mode == 'bookshow') {
            $page_arr['CENTER_TITLE']   = $this->words['Booking for'].' "'.$villa_obj->title.'"';
            
            $form_text = $this->words['form_help3'];
            /*
            
            .'<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
<a href="http://loginza.ru/api/widget?token_url='.urlencode('http://rurenter.ru/?op=booking&act=show&villa_id=').$villa_id.'" class="loginza">
    <img src="http://loginza.ru/img/sign_in_button_gray.gif" alt="Войти через loginza"/>';
            */

        } else {
            $page_arr['CENTER_TITLE']   = $this->words['Booking for'].' "'.$villa_obj->title.'"';
            $form_text = $this->words['form_help1'];
        }

        if ($villa_obj->user_id == $_SESSION['_authsession']['data']['id']) {
            $this->book_status = 1;
        } else {
            $this->book_status = 2;
        }

        if ($_POST['__submit__']) {
            //DB_DataObject::DebugLevel(1);
            if ($this->mode == 'price') {
                $this->savePrice();
            } else {
                $this->saveBook();
                if ($this->book_status != 1) {
                    return;
                }
            }
        }

        if ($this->mode == 'price') {
                $table = $this->showObjTable('prices');
        }

        if ($this->mode == 'bookstat') {
                //$table = $this->showPriceTable();
                $table = $this->showObjTable('booking');
        }

        $this->createTemplateObj();
        //$villa_obj->nav_arr = $this->nav_arr;
        $nav = $villa_obj->showNavigation($this->template_obj, $this->lang);
        
        
        $this->template_obj->loadTemplateFile('villa_book.tpl');

        if ($this->iface != 'i') {
            $this->template_obj->addBlockFile('tabs', 'tabs_group', 'tabs.tpl');
            $villa_obj->formTabs($this->template_obj, $step);
        } else {
            $this->template_obj->setVariable(array('VILLA_NAV' => $nav,));
            $page_arr['BODY_CLASS']   = 'property';
            //$form_text = 'Для того, чтобы выбрать даты, кликните числа на календаре.';
        }
        
        // Создание календаря
        $book_form_obj = new Booking_Form($this->words);
        
        if ($villa_obj->src_site == 'villarenters.com') {
            $data_arr = $book_form_obj->getVillarentersBook($villa_obj->id);
            //echo '<pre>';
            //print_r($data_arr);
            $this->db_obj->mode = 'userbook';
            $book_form_obj->legend = $this->db_obj->getLegend();
            $calendar = $book_form_obj->getActiveCalendar('userbook', $data_arr[0], $data_arr[1]);
        } else {
            $calendar = $book_form_obj->getCalendar($villa_obj, $this->mode);
        }
        
        if ($book_form_obj->legend) {
            foreach ($book_form_obj->legend as $k => $arr) {
            $this->template_obj->setVariable(array(
                    'CLASS' => 'event '.$arr[1],
                    'PRICE' => $arr[0],
                ));
                $this->template_obj->parse('COLORS');
            }
        }

        if ($calendar) {
            if ($this->mode != 'bookshow') {
                $form = $book_form_obj->calendarForm($villa_obj->sleeps, $villa_obj->currency, $villa_obj->getPay_periodName($this->lang));
            }
        } else {
            $calendar = "<b>Владелец не определил цену на данный объект</b>";
        }

        $this->src_files = array(
            'JS_SRC' => array(  '/js/pickdate_price.js',
                                        ),
            'CSS_SRC'  => array(
                                 '/css/calendar.css',
                                 '/css/manage-listing.css'
                               ),
            
        );

        //$table = $this->showBookingTable();
        //echo $table;


        $this->template_obj->setVariable(array(
                //'BOOKING_TITLE' => 'Бронирование виллы "'.$villa_obj->title.'"<br><br>',
                'BOOKING_FORM' => $form_text.'<br><br>'.$form.$table,
                'CALENDAR' => $calendar,
                //'TABLE_LINK' => $table_link,
                //'VILLA_NAV' => $nav,
            ));


        if ($table_link) {
            $this->template_obj->setVariable(array(
                'TABLE_LINK' => $table_link,
                //'TABLE_TEXT' => $table,
                'TABLE_TEXT' => $this->words['table_text']
                ));
        }

        $page_arr['BODY_CLASS']   = 'property';
        
        $page_arr['CENTER']         = $this->template_obj->get();
        $page_arr['PAGE_TITLE'] = $villa_obj->title_rus.' - Бронирование - '.$this->layout_obj->project_title;

        //$page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        return $page_arr;
    }

    function savePrice()
    {
        $start_date = DVS::getVar('start_date', 'word', 'post');
        $end_date = DVS::getVar('end_date', 'word', 'post');
        $prices_obj = DB_DataObject::factory('prices');
        //DB_DataObject::DebugLevel(1);
        $prices_obj->price = DVS::getVar('price', 'int', 'post');
        $prices_obj->start_date = Booking_Form::convertDate($start_date);
        $prices_obj->end_date = Booking_Form::convertDate($end_date);
        $prices_obj->user_id = $_SESSION['_authsession']['data']['id'];
        $prices_obj->villa_id = $this->villa_id;
        $prices_obj->currency = $this->currency;
        if ($prices_obj->insert()) {
            $this->msg = ADD_ROW;
        }
    }

    function saveBook($villa_obj)
    {
        $start_date = DVS::getVar('start_date', 'word', 'post');
        $end_date = DVS::getVar('end_date', 'word', 'post');
        $price = DVS::getVar('price', 'int', 'post');
        $currency = DVS::getVar('currency', 'word', 'post');
        $people = DVS::getVar('people', 'int', 'post');
        $note = DVS::getVar('note', 'word', 'post');

        if ($start_date && $end_date) {
            $insert = $this->db_obj->saveBook($villa_obj,
                $_SESSION['_authsession']['data']['id'],
                Booking_Form::convertDate($start_date),
                Booking_Form::convertDate($end_date),
                $this->book_status,
                $price,
                $currency,
                $people,
                $note);
        }

        if ($insert) {
            if ($this->book_status != 1) {
                $this->msg = 'BOOKING_ADD';
            } else {
                $this->msg = 'Бронь добавлена';
            }
        }
    }


    function convertDate($str)
    {
        $arr = explode("/", $str);
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }

    function showPriceTable()
    {
        require_once 'HTML/Template/Sigma.php';
        $prices_obj = DVS_Dynamic::createDbObj('prices');
        $prices_obj->qs = '?op=prices&villa_id='.$prices_obj->villa_id;
        $list_obj = new DVS_ShowList;
        $list_obj->role = $this->role;
        $list_obj->db_obj = $prices_obj;
        $list_obj->template_obj = new HTML_Template_Sigma(PROJECT_ROOT.TMPL_FOLDER, PROJECT_ROOT.CACHE_FOLDER.'tmpl');
        $table = $list_obj->showTable();
        return $table;
    }

    function showObjTable($obj_name)
    {
        require_once 'HTML/Template/Sigma.php';
        $obj = DVS_Dynamic::createDbObj($obj_name);
        $obj->qs = '?op='.$obj_name.'&villa_id='.$obj->villa_id;
        $list_obj = new DVS_ShowList;
        $list_obj->role = $this->role;
        $list_obj->db_obj = $obj;
        $list_obj->template_obj = new HTML_Template_Sigma(PROJECT_ROOT.TMPL_FOLDER, PROJECT_ROOT.CACHE_FOLDER.'tmpl');
        $table = $list_obj->showTable();
        return $table;
    }
}

?>