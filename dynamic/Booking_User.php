<?php
/**
 * Календарь цен. брони, заявка
 * Для пользователя - туриста
 * @package 
 * $Id: Booking_Show.php 321 2013-02-13 14:32:26Z xxserg@gmail.com $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once PROJECT_ROOT.'/layout/activecalendar.php';
require_once PROJECT_ROOT.'DataObjects/Booking_Form.php';


define('DVS_BOOKING_ADD', 'Ваша заявка на бронирование принята.<br>Владелец объекта свяжется с вами в ближайшее время.<br>Вы можете проверить статус заявки в <a href=/office/?op=booking>личном офисе пользователя</a>');

class Project_Booking_User extends DVS_Dynamic
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
        'form_help3' => 'Для того, чтобы ввести заявку на бронирование,<br> <a href="/reg/">зарегистрируйтесь</a> или <a href="/office/">авторизуйтесь</a> на сайте',
        'form_help4' => 'Подтвердите бронирование',
    //.', <br> или войдите через социальные сети:<br><br>',
        'start_date'  =>  'Начало периода',
        'end_date'  =>  'Конец',
        'table_text' => 'Посмотреть в виде таблицы / Редактировать',
        'note' => 'Примечания',
         'form_help5' => '* Размер платежей, условия проживания и другую информацию Вам сообщит владелец объекта',
         'form_help6' =>   'Также, Вы можете войти используя:',
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
        'form_help4' => 'Confirm booking',
        'start_date'  =>  'Start date',
        'end_date'  =>  'End date',
        'table_text' => 'Show / Edit',
        'note' => 'Notes',
        'form_help5' => '* The owner inform you about details',
        'form_help6' =>   'Log in with:',


    );

    private $book_form_obj;

    private $villa_obj;

    /**
        $this->mode = 'bookshow'; // Не авторизован, календарь, таблица
        $this->mode = 'userbook'; // Авторизован, календарь + Форма + таблица
        $this->mode = 'confirm';  // Авторизован, Форма
        $this->mode = 'save';  // Авторизован, Сохранение
    */
    function getPageData()
    {        

        @session_start();

        $_SESSION['referer'] = $_SERVER['REQUEST_URI'];

        if ($_POST['token']) {
            require_once COMMON_LIB.'DVS/Auth_Loginza.php';
            $auth_obj = new DVS_Auth_loginza();
            $auth_obj->authLoginza();
        }

        $this->mode = DVS::getVar('mode');
        $this->words = $this->{'lang_'.$this->lang};

        // Пользователь авторизован
        if ($_SESSION['_authsession']['registered'] && $_SESSION['_authsession']['username'] && $this->iface == 'i') {
            $this->mode = 'userbook';
            if ($_POST['next']) {
                $this->mode = 'confirm';
            }
        }
        // Пользователь НЕ авторизован
        if (!$this->mode && $this->iface == 'i') {
            $this->mode = 'bookshow';
        }

        //echo $this->mode;

        $villa_id = DVS::getVar('villa_id');
        
        $this->villa_obj = DVS_Dynamic::createDbObj('villa');
        $this->villa_obj->get($villa_id);

        if (!$this->villa_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        $this->villa_id = $villa_id;
        $this->currency = $villa_obj->villa_obj->currency;

        $this->createTemplateObj();
        $nav = $this->villa_obj->showNavigation($this->template_obj, $this->lang);
        $this->template_obj->loadTemplateFile('booking_user.tpl');
        $this->book_form_obj = new Booking_Form($this->words);


        if ($this->mode == 'bookshow') {
            //$page_obj = DVS_Dynamic::createDbObj('pages');
            //$page_obj->get('alias', 'social');
             //$this->words['form_help3'] = $page_obj->body;
            $loginza = file_get_contents(PROJECT_ROOT.'tmpl/loginza.tpl');
            $this->words['form_help3'] .= '<br><br>'.$this->words['form_help6'].str_replace('{TOKEN_URL}', urlencode('http://rurenter.ru/social/'.$villa_id), $loginza);

            $this->bookShow();
        }

        if ($this->mode == 'userbook') {
            $this->userBook();
        }

        if ($this->mode == 'confirm') {
            $this->confirmBook();
        }


       //2 => 'Зарезервировано',
       $this->book_status = 2;
        // Подтверждение - сохранение
        if ($_POST['__submit__']) {
            //DB_DataObject::DebugLevel(1);
                $this->saveBook();
                if ($this->book_status != 1) {
                    return;
                }
        }


        
        
        

        $this->template_obj->setVariable(array('VILLA_NAV' => $nav,));
        
        // Создание календаря
        /*
        $calendar = $book_form_obj->getCalendar($villa_obj, $this->mode);
        $table = $book_form_obj->showObjTable('prices');
        
        if ($table) {
            $table = '<b>'.$this->words['price'].' '.$villa_obj->getPay_periodName($this->lang).'</b><br><br>'.$table;
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
       

        $this->template_obj->setVariable(array(
                'BOOKING_FORM' => $form_text.'<br><br>'.$form.$table,
                'CALENDAR' => $calendar,
            ));
        if ($table_link) {
            $this->template_obj->setVariable(array(
                'TABLE_LINK' => $table_link,
                //'TABLE_TEXT' => $table,
                'TABLE_TEXT' => $this->words['table_text']
                ));
        }
     */
        $page_arr['CENTER_TITLE']   = $this->words['Booking for'].' "'.$this->villa_obj->title.'"'; 
        $page_arr['BODY_CLASS']   = 'property';
        $page_arr['CENTER']         = $this->template_obj->get();
        $page_arr['PAGE_TITLE'] = $this->villa_obj->title_rus.' - Бронирование - '.$this->layout_obj->project_title;

        //$page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        return $page_arr;
    }

    /**
    Таблица цен и календарь
    */
    function calendarPriceTable()
    {
        $calendar = $this->book_form_obj->getCalendar($this->villa_obj, $this->mode);
        $table = $this->book_form_obj->showObjTable('prices');
        
        if ($table) {
            $table = '<b>'.$this->words['price'].' '.$this->villa_obj->getPay_periodName($this->lang).'</b><br><br>'.$table;
        }
        $this->template_obj->setVariable(array(
                'TABLE'    => $table,
                'CALENDAR' => $calendar,
            ));
        if ($this->book_form_obj->legend) {
            foreach ($this->book_form_obj->legend as $k => $arr) {
            $this->template_obj->setVariable(array(
                    'CLASS' => 'event '.$arr[1],
                    'PRICE' => $arr[0],
                ));
                $this->template_obj->parse('COLORS');
            }
        }
    }

    function bookShow()
    {
        $this->template_obj->setVariable(array(
                'BOOKING_TITLE'    => $this->words['form_help3'],
            ));
        $this->calendarPriceTable();
        $this->src_files = array(
            'CSS_SRC'  => array(
                                 '/css/calendar.css',
                               ),
            
        );
    }

    function userBook()
    {
        $this->calendarPriceTable();
        $form = $this->book_form_obj->calendarForm($this->villa_obj->sleeps, $this->villa_obj->currency, $this->villa_obj->getPay_periodName($this->lang));
        $this->src_files = array(
            'JS_SRC' => array(  '/js/pickdate_price.js',
                                        ),
            'CSS_SRC'  => array(
                                 '/css/calendar.css',
                                 '/css/manage-listing.css'
                               ),
            
        );
        $this->template_obj->setVariable(array(
                'BOOKING_FORM' => $form,
                'BOOKING_TITLE'    => $this->words['form_help1'],
            ));
    }

    function confirmBook()
    {
        $start_date = DVS::getVar('start_date', 'word', 'post');
        $end_date = DVS::getVar('end_date', 'word', 'post');
        $price = $this->book_form_obj->getPrice($this->villa_obj, Booking_Form::convertDate($start_date), Booking_Form::convertDate($end_date));
        //echo $price;
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('calendar', '', $_SERVER['REQUEST_URI']);
        $form->addElement('text', 'start_date', $this->words['start_date'].':', array(' readonly' => 'on'));
        $form->addElement('text', 'end_date', $this->words['end_date'].':', array(' readonly' => 'on'));
        $form->addElement('text', 'people', $this->words['people'].': ');
        $form->addElement('textarea', 'note', $this->words['note'].': ');
        if ($price) {
            $form->addElement('text', 'price', $this->words['price'].', '.$this->villa_obj->currency.' :', array('value' => $price));
        }
            $form->addElement('submit', '__submit__', DVS_SAVE);
       $form->freeze();
       $form_html = $form->tohtml();
        $this->template_obj->setVariable(array(
                'BOOKING_FORM' => $form_html,
                'BOOKING_TITLE'    => $this->words['form_help4'],
                'CALENDAR' => $this->words['form_help5'],
            ));
    }

    function saveBook()
    {
        //print_r($_POST);
        $start_date = DVS::getVar('start_date', 'word', 'post');
        $end_date = DVS::getVar('end_date', 'word', 'post');
        $price = DVS::getVar('price', 'int', 'post');
        $currency = DVS::getVar('currency', 'word', 'post');
        $people = DVS::getVar('people', 'int', 'post');
        $note = DVS::getVar('note', 'word', 'post');

        if ($start_date && $end_date) {
            $insert = $this->db_obj->saveBook($this->villa_obj,
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

}

?>