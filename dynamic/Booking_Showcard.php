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

define('PREPAY_PERCENT', 25);

class Project_Booking_Showcard extends DVS_Dynamic
{
    // Права
    var $perms_arr = array('oc' => 1, 'ou' => 1, 'ar' => 1);

    private $lang_ru = array(
        'lang' => 'ru',
        'price_t' => 'Цена',
        'Sleeps' => 'спальных мест:',
        'people_t' => 'человек',
        'Order' => 'Заявка',
        'Property' => 'Объект',
        'Period' => 'Период',
        'Duration' => 'Длительность',
        'nights' => 'ночей',
        //'people' => 'человек',
        'status' => 'Статус',
        'Payment_Details' => 'Информация об оплате',
        'Booking_cost' => 'Стоимость проживания',
        'Payments' => 'Платежи',
        'Deposit' => 'Предоплата',
        'Balance' => 'Полная стоимость',
        'Select_payment_method' => 'Выберите способ оплаты',
        'terms_and_conditions' => 'Договор кратковременной аренды (УСЛОВИЯ БРОНИРОВАНИЯ)',
        'I_have_read_and_accept' => 'Я прочитал и согласен с условиями',
        'Contact_owner' => 'Послать сообщение',
        'Warning' => 'Важно: в своей переписке, не указывайте свои контактные данные: емэйл, телефон, адрес сайта, название компании. Эти данные будут удалены модератором',
        'Messages_for_the_booking' => 'Cообщения по заявке',
        'pay_deposit' => 'Оплатить предоплату',
        'pay_total' => 'Оплатить полную стоимость',
        'owner' => 'Владелец',
        'renter' => 'арендатор',
        'toowner' => 'Владельцу',
        'torenter' => 'арендатору',
        'breakage_title' => 'Залог за ущерб',
    );

    private $lang_en = array(
        'lang' => 'en',
        'price_t' => 'Price',
        'Sleeps' => 'sleeps:',
        'people_t' => 'people',
        'Order' => 'Booking',
        'Property' => 'Property',
        'Period' => 'Period',
        'Duration' => 'Duration',
        'nights' => 'nights',
        //'people' => 'people',
        'status' => 'status',
        'Payment_Details' => 'Payment Details',
        'Booking_cost' => 'Booking cost',
        'Payments' => 'Payments',
        'Deposit' => 'Deposit',
        'Balance' => 'Balance',

        'Select_payment_method' => 'Select payment method',
        'terms_and_conditions' => 'terms and conditions',
        'I_have_read_and_accept' => 'I have read and accept',
        'Contact_owner' => 'Contact',
        'Warning' => 'Warning: in your messages, don`t write your contacts: email, phone, etc. Its moderated',
        'Messages_for_the_booking' => 'Messages for the booking',
        'pay_deposit' => 'Pay deposit',
        'pay_total' => 'Pay total cost',
        'owner' => 'owner',
        'renter' => 'renter',
        'toowner' => 'owner',
        'torenter' => 'renter',
        'breakage_title' => 'Breakage waiver',
    );


    function getPageData()
    {

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

        if (!$this->perm_obj->checkPermsByField() && $villa_obj->user_id != $_SESSION['_authsession']['data']['id'] && $_SESSION['_authsession']['data']['role_id'] != 'aa') {
                $this->msg = 'error_forbidden3';
                return;
        }

        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->db_obj->user_id);

        $book_form_obj = new Booking_Form($this->words);

        $price =$this->db_obj->price;
        $prepay = $this->db_obj->prepay;
        $currency = $this->db_obj->currency;

        // Если счет не выставлен, вычисляется цена
        if (!$price) {
            $price = $book_form_obj->getPrice($villa_obj, $this->db_obj->start_date, $this->db_obj->end_date);
        }

        if ($_POST['__submit__'] && $_POST['body']) {
            //DB_DataObject::DebugLevel(1);
                $this->saveQuery($villa_obj);
                $this->qs = '/office/?op=static&act=add_row';
                $this->msg = DVS_ADD_ROW;
                //return;
        }

        if ($_POST['cancel'] && $this->role == 'oc') {
                $this->db_obj->book_status = 5;
                $this->db_obj->update();
                $this->msg = DVS_UPDATE_ROW;
                $this->db_obj->statusLetter();
        }

/*
        if ($_POST['change_status'] && $status_id=$_POST['status_id']) {
            /*
            if ($status_id == 5) {
                //$this->db_obj->delete();
                $this->msg = DVS_DELETE_ROW;
                return;
            }
            
            if ($this->db_obj->book_status_arr[$status_id]) {
                $this->db_obj->book_status = $status_id;
                $this->db_obj->update();
                $this->msg = DVS_UPDATE_ROW;
                if ($status_id == 5) {
                    $this->db_obj->statusLetter();
                }
                //return;
            }
        }
*/
        /**
        Выставление счета на оплату
        */
        if ($_POST['invoice'] && $_POST['price']) {
            $price = DVS::getVar('price', 'word', 'post');
            $this->createInvoice($price, $villa_obj->currency);
        }

        if ($_POST['prepay_invoice'] && $_POST['price'] && $_POST['prepay']) {
            $this->msg = "Выставлен счет";
            $price = DVS::getVar('price', 'word', 'post');
            $prepay = DVS::getVar('prepay', 'word', 'post');
            $this->createInvoice($price, $villa_obj->currency, $prepay);
        }


        if ($_POST['pay_prepay'] || $_POST['pay_total']) {
            $this->pay_service_id = DVS::getVar('pay_service_id', 'int', 'post');
            $this->pay();
            //$this->db_obj->qs = '/onpay/request.php';
            //$this->db_obj->qs = '?op=booking&act=pay&booking_id='.$this->db_obj->id;
            $this->goLocation();
        }

        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('booking_show.tpl');

            if ($this->role == 'oc') {
                $feedback_title = $this->words['torenter'];
            } else {
                $feedback_title = $this->words['toowner'];
            }

            $this->template_obj->setVariable(
                    array(
                        'id' => $this->db_obj->id,
                        'villa_id' => $this->db_obj->villa_id,
                        'villa_title' => $villa_obj->getLocalField('title', $this->lang),
                        'post_date' => DVS_Page::RusDate($this->db_obj->post_date),
                        'post_ip' => $this->db_obj->post_ip,
                        'book_status' => $this->db_obj->book_status_arr[$this->db_obj->book_status],
                        'start_date' => DVS_Page::RusDate($this->db_obj->start_date),
                        'end_date' => DVS_Page::RusDate($this->db_obj->end_date),
                        'user_id' => $this->db_obj->user_id,
                        'user_name' => $users_obj->name,
                        'days' => $this->db_obj->daysCount(),
                        'price' => $price,
                        'prepay' => $prepay ? $prepay : floor($price * PREPAY_PERCENT / 100),
                        'currency' => $currency ? $currency : $villa_obj->currency,
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
        
        if ($this->role == 'oc' && ($this->db_obj->book_status < 4 || $this->db_obj->book_status > 5)) {
            //$this->seltatus();
            $this->template_obj->touchBlock('CANCEL');
        }



        // Для арендатора: Оплатить счет, если есть цена и счет выставлен
        if ($this->db_obj->price) {
            //$this->template_obj->parse('PRICE');
            if ($this->role == 'ou' && $this->db_obj->book_status == 6) {
                $this->selPayService();
                //$price_rur = $this->db_obj->price;
                /*
                if ($this->db_obj->currency != 'RUR') {
                    $price_rur = $this->rurPrice($this->db_obj->price, $this->db_obj->currency);
                    $this->template_obj->setVariable(
                        array(
                        'price_rur' => $price_rur,
                        ));
                }
                */
                $this->template_obj->setVariable(
                        array(
                    'PRICE' => $this->db_obj->price,
                    'PRICE_CURRENCY' => $this->db_obj->currency,
                    'PAY_SUM' => $price_rur,
                    'PAY_CURRENCY' => 'RUR',
                    //'PAY_CURRENCY' => $this->db_obj->currency,
                    'PAY_BOOKING_ID' => $this->db_obj->id,
                    'PAY_ACTION' => '?op=payments_in&act=request',
                    //'PAY_ACTION' => '?op=booking&act=agree&id='.$this->db_obj->id,

                    ));
                $this->template_obj->touchBlock('PAY');
                if ($prepay) {
                    $this->template_obj->touchBlock('PREPAY');
                    $this->template_obj->touchBlock('PREPAY_SUBMIT');
                } else {
                    $this->template_obj->hideBlock('PREPAY');
                    $this->template_obj->hideBlock('PREPAY_SUBMIT');
                }
            }
        }
        

        //$this->template_obj->parse('FEEDBACK');

        if ($this->role == 'oc' && $this->db_obj->book_status > 2) {
            $this->template_obj->hideBlock('INVOICE');
            //$this->template_obj->hideBlock('FEEDBACK');
            //$this->template_obj->parse('PRICE');
        }

        // Выставить счет
        if ($this->role == 'oc' && $this->db_obj->book_status == 2) {
            $this->template_obj->parse('INVOICE');
            //$this->template_obj->touchBlock('INVOICE');
            //$this->template_obj->hideBlock('FEEDBACK');
            //$this->template_obj->parse('PRICE');
        }

        //if ($this->role == 'ou') {
            $this->template_obj->touchBlock('FEEDBACK');
        //}

         $this->showMessages();
        $this->template_obj->setGlobalVariable($this->words);

        $page_arr['CENTER_TITLE'] = $this->words['Order'].' #'.$this->db_obj->id;
        $page_arr['CENTER'] = $this->template_obj->get();
        return $page_arr;
    }


    /**
     Сохранение ответа усли пишет арендатор, то нужен емайл владельца
     */
    function saveQuery($villa_obj)
    {
        
        //echo '<pre>';
        //print_r($_SESSION);
        $user_id_to = $this->db_obj->user_id;
        if ($_SESSION['_authsession']['data']['role_id'] == 'ou') {
            $users_obj = DB_DataObject::factory('users');
            $users_obj->get($villa_obj->user_id);
            $user_id_to = $users_obj->id;
        }
        //echo $user_id_to.''.$_SESSION['_authsession']['data']['id'];
        $query_obj = DB_DataObject::factory('query');
        $query_obj->addAnswer($user_id_to, $_SESSION['_authsession']['data']['id'], $this->db_obj->villa_id, $_POST['body'], $this->db_obj->id);
    }


    function seltatus()
    {
        $this->db_obj->book_status_arr += array(5 => 'Удалить');
        unset($this->db_obj->book_status_arr[6]);
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

/**
 Выписка счета на полную сумму или предоплату
*/
    function createInvoice($price, $currency, $prepay='')
    {
        //echo "$price, $currency, $prepay";
        $this->db_obj->price = $price;
        $this->db_obj->prepay = $prepay;
        $this->db_obj->book_status = 3;
        $this->db_obj->currency = $currency;
        $this->db_obj->update();
        $this->db_obj->invoiceLetter();
    }

    function selPayService()
    {

        $pay_services_obj = DB_DataObject::factory('pay_services');
        $pay_services_arr = $pay_services_obj->selArray(2);

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
        $query_obj = DB_DataObject::factory('query');
        $query_obj->booking_id = $this->db_obj->id;
        $query_obj->whereAdd("status_id = 1");

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
}

?>