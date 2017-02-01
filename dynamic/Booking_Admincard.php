<?php
/**
////////////////////////////////////////////////////////////////////////////
ruRenter
------------------------------------------------------------------------------
Отображение заявок
------------------------------------------------------------------------------
$Id: Pages_Show.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once PROJECT_ROOT.'DataObjects/Booking_Form.php';


class Project_Booking_Admincard extends DVS_Dynamic
{
    // Права
    //var $perms_arr = array('' => 1, 'ou' => 1, 'ar' => 1);

    private $lang_ru = array(
        //'price' => 'Цена',
        'Sleeps' => 'спальных мест:',
        //'people' => 'Кол-во человек',
        'Payment_Details' => 'Информация об оплате',
        'Booking_cost' => 'Стоимость проживания',
        'Payments' => 'Платежи',
        'Deposit' => 'Предоплата',
        'Balance' => 'Полная стоимость',
        'breakage_title' => 'Залог за ущерб',

    );

    private $lang_en = array(
        'price' => 'Price',
        'Sleeps' => 'sleeps:',
        'people' => 'people',
    );


    function getPageData()
    {

        //echo '<pre>';
        //print_r($_POST);

        $this->words = $this->{'lang_'.$this->lang};

        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }
        /*
        if ($_SESSION['_authsession']['data']['role_id'] == 'oc' ) {
            if (!$this->perm_obj->checkPermsByField()) {
                $this->msg = 'error_forbidden3';
                return;
            }
        }
        */
        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($this->db_obj->villa_id);

        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->db_obj->user_id);

        $book_form_obj = new Booking_Form($this->words);

        $price =$this->db_obj->price;

        if (!$price) {
            $price = $book_form_obj->getPrice($villa_obj, $this->db_obj->start_date, $this->db_obj->end_date);
        }

        if ($_GET['confirm']) {
            //print_r($users_obj);
            $this->sendConfirm($villa_obj, $users_obj);
            $this->msg = 'Клиенту отправлено подтверждение бронирования';
        }

        if ($_POST['__submit__']) {
            //DB_DataObject::DebugLevel(1);
                $this->saveQuery();
                $this->qs = '/office/?op=static&act=add_row';
                $this->msg = DVS_ADD_ROW;
                //return;
 
        }

        if ($_POST['change_status'] && $status_id=$_POST['status_id']) {
            /*
            if ($status_id == 5) {
                $this->db_obj->delete();
                $this->msg = DVS_DELETE_ROW;
                return;

            }
            */
            if ($this->db_obj->book_status_arr[$status_id]) {
                $this->db_obj->book_status = $status_id;
                $this->db_obj->update();
                $this->msg = DVS_UPDATE_ROW;

                if ($status_id == 6) {
                    $this->msg = 'Клиенту отправлено приглашение для оплаты';
                    $this->renterInvoiceLetter($villa_obj, $users_obj);
                }

                return;
            }
        }

        if ($_POST['prepay_invoice'] && $_POST['price'] && $_POST['prepay']) {
            $this->msg = "Цена изменена";
            $price = DVS::getVar('price', 'word', 'post');
            $prepay = DVS::getVar('prepay', 'word', 'post');
            $this->createInvoice($price, $villa_obj->currency, $prepay);
        }

        if ($_POST['start_date'] && $_POST['end_date']) {
            //$this->msg = "Цена изменена";
            $start = DVS::getVar('start_date', 'word', 'post');
            $end = DVS::getVar('end_date', 'word', 'post');
            $this->changeDates($start, $end);
        }
        

        if ($_POST['pay']) {
            $this->pay_service_id = DVS::getVar('pay_service_id', 'int', 'post');
            $this->pay();
            //$this->db_obj->qs = '/onpay/request.php';
            //$this->db_obj->qs = '?op=booking&act=pay&booking_id='.$this->db_obj->id;
            $this->goLocation();
        }

        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('booking_admincard.tpl');

            if ($this->role == 'oc') {
                $feedback_title = 'арендатору';
            } else {
                $feedback_title = 'владельцу';
            }

            $this->template_obj->setVariable(
                    array(
                        'id' => $this->db_obj->id,
                        'villa_id' => $this->db_obj->villa_id,
                        'villa_title' => $villa_obj->title,
                        'owner' => $villa_obj->user_id,
                        'post_date' => DVS_Page::RusDate($this->db_obj->post_date),
                        'post_ip' => $this->db_obj->post_ip,
                        'book_status' => $this->db_obj->book_status_arr[$this->db_obj->book_status],
                        'start_date' => DVS_Page::RusDate($this->db_obj->start_date),
                        'end_date' => DVS_Page::RusDate($this->db_obj->end_date),
                        'renter_id' => $this->db_obj->user_id,
                        'user_name' => $users_obj->name,
                        'user_lastname' => $users_obj->lastname,
                        'user_home_phone' => $users_obj->home_phone,
                        'user_email' => $users_obj->email,
                        'user_reg_date' => $users_obj->reg_date,
                        'user_last_ip' => $users_obj->last_ip,
                        'days' => $this->db_obj->daysCount(),
                        'price' => $price,
                        'prepay' => $this->db_obj->prepay,
                        'currency' => $villa_obj->currency,
                        'people' =>  $this->db_obj->people,
                        'note' => 'Примечания: '.$this->db_obj->note.'<br>',
                        'feedback_title' => $feedback_title,
                        'breakage' => $villa_obj->breakage,
                        'Breakage_title' => $this->words['breakage_title'],
            )
            );

        if (!$villa_obj->breakage) {
            $this->template_obj->hideBlock('BREAKAGE');
        }


        if ($_SESSION['_authsession']['data']['id'] ==  $this->db_obj->user_id) {
            $this->template_obj->hideBlock('USER');
        }

        $this->template_obj->setVariable(array('feedback_title' => $feedback_title));
        
        //if ($this->role == 'oc') {
            $this->seltatus();
        //}

        // Для арендатора: Оплатить счет, если есть цена и счет выставлен
        if ($this->db_obj->price) {
            //$this->template_obj->parse('PRICE');
            if ($this->role == 'ou' && $this->db_obj->book_status == 3) {
                $this->selPayService();
                $price_rur = $this->db_obj->price;
                if ($this->db_obj->currency != 'RUR') {
                    $price_rur = $this->rurPrice($this->db_obj->price, $this->db_obj->currency);
                    $this->template_obj->setVariable(
                        array(
                        'price_rur' => $price_rur,
                        ));
                }
                $this->template_obj->setVariable(
                        array(
                    'PAY_SUM' => $price_rur,
                    'PAY_CURRENCY' => 'RUR',
                    //'PAY_CURRENCY' => $this->db_obj->currency,
                    'PAY_BOOKING_ID' => $this->db_obj->id,
                    'PAY_ACTION' => '?op=payments_in&act=request',
                    //'PAY_ACTION' => '?op=booking&act=agree&id='.$this->db_obj->id,

                    ));
                $this->template_obj->touchBlock('PAY');
            }
        }
        

        $this->calendarRange();
        $page_arr['JSCRIPT'] .= $this->page_arr['JSCRIPT'];

        if ($this->db_obj->book_status == 4) {
            $this->template_obj->touchBlock('CONFIRMATION');
            //$this->template_obj->parse('PRICE');
        }

        // Выставить счет
            $this->template_obj->parse('INVOICE');
            //$this->template_obj->touchBlock('INVOICE');
            $this->template_obj->hideBlock('FEEDBACK');
            //$this->template_obj->parse('PRICE');
         $this->showMessages();
        $this->template_obj->setGlobalVariable($this->words);

        $page_arr['CENTER_TITLE'] = 'Заявка';
        $page_arr['CENTER'] = $this->template_obj->get();
        return $page_arr;
    }

    function saveQuery()
    {
        $query_obj = DB_DataObject::factory('query');
        $query_obj->addAnswer($this->db_obj->user_id, $_SESSION['_authsession']['data']['id'], $this->db_obj->villa_id, $_POST['body'], $this->db_obj->id);
    }


    function seltatus()
    {
        $this->db_obj->book_status_arr += array(5 => 'Удалить');
        foreach ($this->db_obj->book_status_arr as $k => $v) {
            $this->template_obj->setVariable(
                    array(
                'S_VALUE' => $k,
                'S_NAME' => $v,
                'S_SELECTED' => $this->db_obj->book_status == $k ? " SELECTED" : ''

            ));
            $this->template_obj->parse('S_OPTION');
        }
    }

    function createInvoice($price, $currency, $prepay='')
    {
        //echo "$price, $currency, $prepay";
        $this->db_obj->price = $price;
        $this->db_obj->prepay = $prepay;
        //$this->db_obj->book_status = 3;
        $this->db_obj->currency = $currency;
        $this->db_obj->update();
        //$this->db_obj->invoiceLetter();
    }

    function changeDates($start, $end)
    {
        //echo "$start, $end";
        $this->db_obj->start_date = self::convertDate($start);
        $this->db_obj->end_date = self::convertDate($end);
        $this->db_obj->update();
        $this->msg = "даты изменены";
    }


    function selPayService()
    {

        $pay_services_obj = DB_DataObject::factory('pay_services');
        $pay_services_arr = $pay_services_obj->selArray();

        foreach ($pay_services_arr as $k => $v) {
            $this->template_obj->setVariable(
                    array(
                'P_VALUE' => $k,
                'P_NAME' => $v,
                'P_SELECTED' => $this->pay_service_id == $k ? " SELECTED" : ''

            ));
            $this->template_obj->parse('P_OPTION');
        }
    }


    function pay()
    {
        $payments_in_obj = DB_DataObject::factory('payments_in');
        $payments_in_obj->newPayment($this->db_obj->user_id, $this->db_obj->price, $this->db_obj->currency, $this->db_obj->id, $this->pay_service_id);
        //$this->db_obj->book_status = 4;
        //$this->db_obj->update();
    }

    function showMessages()
    {
        require_once PROJECT_ROOT.'DataObjects/Query_Admin.php';
        //$query_obj = DB_DataObject::factory('query');
        $query_obj = new DBO_Query_Admin;
        $query_obj->booking_id = $this->db_obj->id;
        //$query_obj->whereAdd("status_id = 1");

        $query_obj->orderBy('id DESC');
        $query_obj->find();

        while ($query_obj->fetch()) {
            
            if ($_SESSION['_authsession']['data']['id'] == $query_obj->user_id) {
                $user_id = 'Я';
            } else {
                $user_id = $query_obj->user_id;
                if (isset($users_obj) && $users_obj->id == $query_obj->user_id) {
                    $author_name = $users_obj->name;
                } else {
                    $users_obj = DB_DataObject::factory('users');
                    $users_obj->get($query_obj->user_id);
                    $author_name = $users_obj->name;
                }

            }
            $this->template_obj->setVariable(
                    array(
                'body' => $query_obj->body,
                'post_date' => $query_obj->post_date,
                'user_id' => $user_id,
                'author_name' => $author_name,
                'status_id' => $query_obj->activeLink(),
            ));
            $this->template_obj->parse('QUERY');
        }
    }

    function rurPrice($price, $currency)
    {
        $rate = DBO_Villa::currencyRate();
        //print_r($rate);
        return DBO_Villa::rurPrice($price, $currency, $rate)*1.02;
    }


    function renterInvoiceLetter($villa_obj, $users_obj)
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'ruRenter.ru booking';
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->db_obj->id;
        $data_arr = array(
            'booking_link' => $booking_link,
            'villa_id' => $this->db_obj->villa_id,
            'villa_name' => $villa_obj->title,
            'user_id' => $this->db_obj->user_id,
            'user_name' => $users_obj->name.' '.$users_obj->lastname,
            'start_date' => $this->db_obj->start_date,
            'end_date' => $this->db_obj->end_date,
            'people' => $this->db_obj->people,
            'price' => $this->db_obj->price,
            'prepay' => $this->db_obj->prepay,
            'currency' => $this->db_obj->currency,
            );
        $data['body'] = DVS_Mail::letter($data_arr, 'renter_invoice_letter.tpl');
        DVS_Mail::send($data);
    }

    function sendConfirm($villa_obj, $users_obj)
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'ruRenter.ru booking confirmation';
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->db_obj->id;
        $data_arr = array(
            'booking_id' => $this->db_obj->id,
            'booking_link' => $booking_link,
            'villa_id' => $this->db_obj->villa_id,
            'villa_name' => $villa_obj->title,
            'user_id' => $this->db_obj->user_id,
            'user_name' => $users_obj->name.' '.$users_obj->lastname,
            'user_email' => $users_obj->email,
            'start_date' => $this->db_obj->start_date,
            'end_date' => $this->db_obj->end_date,
            'days' => $this->db_obj->daysCount(),
            'people' => $this->db_obj->people,
            'price' => $this->db_obj->price,
            'prepay' => $this->db_obj->prepay,
            'balance' => $this->db_obj->price - $this->db_obj->prepay,
            'currency' => $this->db_obj->currency,
            );
        $data['content-type'] = 'text/html; charset="Windows-1251"';
        $data['body'] = DVS_Mail::letter($data_arr, 'booking_confirmation2.htm');
        //echo $data['body'];
        DVS_Mail::send($data);
    }

    function convertDate($str)
    {
        $arr = explode(".", $str);
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
        //return str_replace("/", "-", $str);
    }

    function calendarRange()
    {
        $this->src_files = array(
            'JS_SRC' => array(   //'/js/pickdate_villa.js',
                                 //'/js/jquery-1.10.2.js',
                                 //'/js/jquery-ui-1.10.4.custom.min.js',
                                 //'/js/jquery.ui.datepicker-ru.min.js',
                                'http://code.jquery.com/jquery-1.9.1.js',
                                'http://code.jquery.com/ui/1.10.3/jquery-ui.js',
                                //'/js/datepicker-ru.js'
                                //'/js/date.js',
                                //'/js/date_ru_win1251.js',
                               //'/js/datePicker2.js',
                                //'/js/datepicker-ru.js',

        ),
        
            'CSS_SRC'  => array(
                                //'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css',
                                '/css/jquery-ui.css',
                                '/css/datePicker.css',
                                '/css/plain.css',
        )
        );


        $this->page_arr['JSCRIPT'] .= '

        $(function() {
        $( "#from" ).datepicker({
        dateFormat: "dd.mm.yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 3,
        minDate: 0,
        regional: "ru",
        onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
        }
        });

        $( "#to" ).datepicker({
        dateFormat: "dd.mm.yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 3,
        onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            var start = $("#from").datepicker("getDate");
            var end   = $("#to" ).datepicker("getDate");
            var days   = (end - start)/1000/60/60/24;

            if (days < minp)
                {
                    date_OK = false;
                    alert("Владелец задал минимальный период броирования " + minp + " дней");
                    $("#from").val("");
                    $("#to" ).datepicker( "setDate", "" );
                } else {
                    $("[name=date1]" ).datepicker({ dateFormat: "yy/mm/dd" } );
                    $("[name=date1]").datepicker("setDate", start);
                    $("[name=date2]" ).datepicker({ dateFormat: "yy/mm/dd" });
                    $("[name=date2]").datepicker("setDate", end);
                    
                    $("[name=numdays]").val(days);
                }

        }
        });
        });
        ';
    }

}

?>