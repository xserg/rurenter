<?php
/**
 * Входящий платеж
 * @package rurenter
 * $Id: $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
include PROJECT_ROOT.'WWW/onpay/onpay_functions.php';

class Project_Payments_in_Request extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ou' => 1);

    private $lang_ru = array(
        'PayPal_payment' => 'Платеж через PayPal',
        'Payment_for_booking' => 'Оплата заявки на аренду',
        'property' => 'объекта',
        'submit_text' => 'Перейти к оплате',
        'help_paypal' => 'Для оплаты картой, на следующей странице выберите в правой части "У Вас нет счета PayPal"<br>При оплате взимается комиссия сервиса 3%',
    );

    private $lang_en = array(
        'PayPal_payment' => 'PayPal payment',
        'Payment_for_booking' => 'Payment for booking',
        'property' => 'property',
        'submit_text' => 'Go to payment gateway',
    );


    function getPageData()
    {   
        $this->words = $this->{'lang_'.$this->lang};

        // Цена в валюте заявки
        $booking_id = DVS::getVar('pay_booking_id', 'int', 'post');
        $pay_service_id = DVS::getVar('pay_service_id', 'int', 'post');
        $payment_in_id = DVS::getVar('payment_in_id', 'int', 'post');
        $user_id = $_SESSION['_authsession']['data']['id'];
        
        $ammount = DVS::getVar('pay_sum', 'int', 'post');
        $currency = DVS::getVar('pay_currency', 'word', 'post');

        $booking_obj =  DB_DataObject::factory('booking');
        $booking_obj->get($booking_id);
        
        if (!$booking_obj->id) {
            echo 'error booking id';
            return;
        }

        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($booking_obj->villa_id);

        $this->createTemplateObj();

        $currency = $booking_obj->currency;

        if ($_POST['pay_prepay']) {
            $ammount = $booking_obj->prepay;
        }
        if ($_POST['pay_total']) {
            $ammount = $booking_obj->price;
        }

        //echo "$ammount $currency";

        if ($_POST['invoice']) {
            $this->invoice($payment_in_id, $ammount, $currency, $booking_id, $email, $user_id);
            exit;
        }



        $payment_in_id = $this->db_obj->newPayment($user_id, $ammount, $currency, $booking_id, $pay_service_id, $about);

        $_SESSION['payment_in_id'] = $payment_in_id;


        $description = $this->words['Payment_for_booking']." #".$booking_id." <br>".$this->words['property']." #".$booking_obj->villa_id." \"".$villa_obj->getLocalField('title', $this->lang)."\"";




        if ($payment_in_id) {
            switch ($pay_service_id) {
                case 1:
                    // onpay
                    $form = $this->process_first_step($payment_in_id, $ammount, $currency, $booking_id, $email);
                    //break;
                case 2:
                    // Вычисление цены в рублях
                    $price_rur = $this->rurPrice($ammount, $currency);
                    $purse_num = WMR_PURSE;
                    $description = 'Пополнение счета пользователя '.$email;
                    self::adminLetter('WebMoney', $price_rur, 'RUR', $booking_id, $email);
                    $this->webmoneyForm($payment_in_id, $price_rur, 'RUR', $purse_num, $description);
                    $center_title = $this->words['Payment_for_booking'].' Webmoney';
                    break;
                case 3:
                    self::adminLetter('Bank', $ammount, $currency, $booking_id, $email);
                    $form = $this->invoiceInfo($payment_in_id, $ammount, $currency, $booking_id, $_SESSION['_authsession']['username'], $_SESSION['_authsession']['data']['id']);
                    //return $this->invoice($payment_in_id, $ammount, $currency, $booking_id, $email, $id);
                    break;
                case 4:
                    $description_en = "Payment for booking ".$booking_id;
                    //self::adminLetter('WebMoney', $ammount, $currency, $booking_id, $email);
                    $this->payPalForm($payment_in_id, $ammount, $currency, $description_en, $description);
                    $center_title = $this->words['PayPal_payment'];
                    break;
                case 5:
                    // Вычисление цены в рублях
                    $price_rur = $this->rurPrice($ammount, $currency);
                    $description = $this->words['Payment_for_booking'].' '.$booking_id;
                    self::adminLetter('YandexMoney', $price_rur, 'RUR', $booking_id, $email);
                    $this->yandexForm($payment_in_id, $price_rur, 'RUR', $purse_num, $description);
                    $center_title = $this->words['Payment_for_booking'].' #'.$booking_id;
                    break;
                    break;
            }

        }
        $this->template_obj->setGlobalVariable($this->words);

        //$this->db_obj->qs = '?op=booking&id='.$this->db_obj->id;
        //$this->goLocation();
        //$page_arr['BODY_CLASS']   = 'property';
        $page_arr['CENTER_TITLE']         =  $center_title;
        $page_arr['CENTER'] = $this->template_obj->get();
        return $page_arr;
    }

    // функция определения параметров платежной формы
    // к примеру, если необходимо добавить e-mail пользователя, который совершает платеж, то
    // добавляется строка к результату '&user_email=vasia@mail.ru'
    function get_iframe_url_params($operation_id, $sum, $md5check, $currency, $booking_id, $email)
    {
        return "pay_mode=fix&pay_for=$operation_id&price=$sum&currency=$currency&convert=yes&md5=$md5check&url_success=".get_constant('url_success')."&url_fail=".get_constant('url_fail')."&user_email=$email&note=$booking_id";
    }


    function process_first_step($operation_id, $sum, $currency, $booking_id, $email) 
    {
        $output = '';
        $err = '';
            $sumformd5 = to_float($sum); //преобразуем число к числу с плавающей точкой 
                //создаем хеш данных для проверки безопасности
            $md5check = md5("fix;$sumformd5;$currency;$operation_id;yes;".get_constant('private_code')); 
                //создаем строчку для запроса
            $url = "http://secure.onpay.ru/pay/".get_constant('onpay_login')."?".$this->get_iframe_url_params($operation_id, $sum, $md5check, $currency, $booking_id, $email);
                //вывод формы onpay с заданными параметрами
                $output = '<iframe src="'.$url.'" width="300" height="500" frameborder=no scrolling=no></iframe> 
                                 <form method=post action="'.$_SERVER['HTTP_REFERER'].'"><input type="submit" value="Вернуться"></form>';
        return $output;
    }


    /**
    Result URL: 	http://rurenter.ru/wm.php
    Success URL:     http://rurenter.ru/office/pay_success
    Fail URL:   http://rurenter.ru/office/error_pay
    */
    function webmoneyForm($payment_in_id, $ammount, $currency, $purse_num, $description)
    {
        //$this->createTemplateObj();
        $this->template_obj->loadTemplateFile('wmform.tpl');
        $fields = array(
            'ammount' => $ammount,
            'currency' => $currency,
            'description' => $description,
            'payment_in_id' => $payment_in_id,
            'purse_num' => $purse_num,
            'sim_mode' => 0,
        );
        $this->template_obj->setVariable($fields);
        return;
    }

    function yandexForm($payment_in_id, $ammount, $currency, $purse_num, $description)
    {
        $this->template_obj->loadTemplateFile('yandex-money.tpl');
        $fields = array(
            'ammount' => ceil($ammount),
            'currency' => $currency,
            'description' => iconv("Windows-1251", "UTF-8", $description),
            //'%D0%91%D1%80%D0%BE%D0%BD%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5+%D0%B2%D0%B8%D0%BB%D0%BB%D1%8B',
            'payment_in_id' => $payment_in_id,
            'purse_num' => $purse_num,
            'sim_mode' => 0,
        );
        $this->template_obj->setVariable($fields);
        return;
    }



    function invoiceInfo($payment_in_id, $ammount, $currency, $booking_id, $email, $id)
    {
        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('invoice_form.tpl');
        $fields = array(
            'ammount' => $ammount,
            'currency' => $currency,
            'description' => $description,
            'payment_in_id' => $payment_in_id,
            'booking_id' => $booking_id,
            'email' => $email,
        );
        $this->template_obj->setVariable($fields);
        return;
    }

    function invoice($payment_in_id, $ammount, $currency, $booking_id, $email, $id)
    {
        require_once(PROJECT_ROOT.'layout/sumpropis.php');
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($id);
        $booking_obj =  DB_DataObject::factory('booking');
        $booking_obj->get($booking_id);
        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($booking_obj->villa_id);

        /*
        echo '<pre>';
        print_r($users_obj->toArray());
        print_r($booking_obj->toArray());
        print_r($villa_obj->toArray());
        */
        //return file_get_contents(PROJECT_ROOT.TMPL_FOLDER.'invoice.tpl');

        $this->template_obj->loadTemplateFile('invoice.tpl');
        $this->template_obj->setVariable(
                    array(
                        //'id' => $this->db_obj->id,
                            'AMMOUNT' => $ammount,
                            'CURRENCY' => $currency,
                            'EMAIL' => $email,
                            'VILLA_ID' => $booking_obj->villa_id,
                            'VILLA_TITLE' => $villa_obj->title_rus,
                            'VILLA_ADDRESS' => $villa_obj->address,
                            'USER_NAME' => $users_obj->name.' '.$users_obj->lastname,
                            'USER_PHONE' => $users_obj->home_phone,
                            'USER_ADDRESS' => $users_obj->address,
                            'USER_PASSPORT' => $users_obj->passport_series.' '.$users_obj->passport_no,
                            'PAY_ID' => $payment_in_id,
                            'PAY_DATE' => date('Y-m-d'),
                            'START_DATE' => $booking_obj->start_date,
                            'END_DATE' => $booking_obj->end_date,
                            'NUM_STR' => SumProp($ammount),

                        ));
        echo $this->template_obj->get();
        exit;
        return  '<a href="?op=payments_in&act=request&print">Версия для печати</a> '.$this->template_obj->get();
    
    }

    private static function adminLetter($pay_service, $ammount, $currency, $booking_id, $email)
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = MAIL_ADMIN;
        $data['reply-to'] = MAIL_ADMIN;
        $data['subject'] = 'New ruRenter pay '.$booking_id;
        //$data['body'] = DVS_Mail::letter($vals, 'reg_admin.tpl');
        $data['body'] = 
        'Запрос платежа по заявке '.$booking_id."\n"
        .'Е-mail: '.$email."\n"
        .'Дата: '.date("Y-m-d H:i:s")."\n"
        .'Сумма: '.$ammount.' '.$currency."\n\n"
        .'http://rurenter.ru/office/?op=payments_in';
        DVS_Mail::send($data);
    }

    function payPalForm($payment_in_id, $ammount, $currency, $description_en, $description)
    {
        $ammount = $ammount*1.03;
        $this->template_obj->loadTemplateFile('paypal.tpl');
        $fields = array(
            'ammount' => $ammount,
            'currency' => $currency,
            'description' => $description,
            'description_en' => $description_en,
            'payment_in_id' => $payment_in_id,
            //'action' => ($this->lang == 'en' ? '/en' : '').'/office/paypal.php',
            'action' => '/'.$this->lang.'/office/paypal.php',
        );
        $this->template_obj->setVariable($fields);
        return;
    }

    function rurPrice($price, $currency)
    {
        $rate = DBO_Villa::currencyRate();
        //print_r($rate);
        return DBO_Villa::rurPrice($price, $currency, $rate)*1.03;
    }
}

?>