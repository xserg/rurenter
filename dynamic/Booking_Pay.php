<?php
/**
 * Входящий платеж
 * @package rurenter
 * $Id: Transactions_Pay.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
include PROJECT_ROOT.'WWW/onpay/onpay_functions.php';

define('DVS_ERROR_PAY', 'Недостаточная сумма на счету!');
define('PAYED', 'Заявка оплачена!');

class Project_Booking_Pay extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ou' => 1, 'iu' => 1);

    function getPageData()
    {   
        //DB_DataObject::DebugLevel(1);
        $onpay_id 					= $_REQUEST['onpay_id']; 
        $pay_for 						= $_REQUEST['pay_for']; 
        $order_amount 			= $_REQUEST['order_amount']; 
        $order_currency			= $_REQUEST['order_currency']; 
        $balance_amount 		= $_REQUEST['balance_amount']; 
        $balance_currency 	= $_REQUEST['balance_currency']; 
        $exchange_rate 			= $_REQUEST['exchange_rate']; 
        $paymentDateTime 		= $_REQUEST['paymentDateTime']; 
        $md5 								= $_REQUEST['md5']; 


        //$pay_for = DVS::getVar('pay_for', 'int', 'request');

        if (!is_numeric($pay_for)) {
            // 'Если pay_for - не правильный формат'; 
            echo answerpay($_REQUEST['type'], 11, $pay_for, $order_amount, $order_currency, 'Error in parameters data', $onpay_id);
            exit;
        }

        $payments_in_obj = DB_DataObject::factory('payments_in');
        $payments_in_obj->get($pay_for);
        if (!$payments_in_obj->N) {
            echo 'www'.answerpay($_REQUEST['type'], 10, $pay_for, $order_amount, $order_currency, 'Cannot find any pay rows acording to this parameters: wrong payment', $onpay_id);
            exit;
            //$this->msg = PAYMENT_IN_NOT_FOUND;
            //return;
        }

        if ($_REQUEST['type'] == 'check') { 
            //выдаем ответ OK на чек запрос 
            echo answer($_REQUEST['type'],0, $pay_for, $order_amount, $order_currency, 'OK'); 
            exit;
        } 

        if ($_REQUEST['type'] == 'pay') {
            //производим проверки входных данных 
            if (empty($onpay_id)) {$error .="Не указан id<br>";} 
            else {if (!is_numeric(intval($onpay_id))) {$error .="Параметр не является числом<br>";}} 
            if (empty($order_amount)) {$error .="Не указана сумма<br>";} 
            else {if (!is_numeric($order_amount)) {$error .="Параметр не является числом<br>";}} 
            if (empty($balance_amount)) {$error .="Не указана сумма<br>";} 
            else {if (!is_numeric(intval($balance_amount))) {$error .="Параметр не является числом<br>";}} 
            if (empty($balance_currency)) {$error .="Не указана валюта<br>";} 
            else {if (strlen($balance_currency)>4) {$error .="Параметр слишком длинный<br>";}} 
            if (empty($order_currency)) {$error .="Не указана валюта<br>";} 
            else {if (strlen($order_currency)>4) {$error .="Параметр слишком длинный<br>";}} 
            if (empty($exchange_rate)) {$error .="Не указана сумма<br>";} 
            else {if (!is_numeric($exchange_rate)) {$error .="Параметр не является числом<br>";}} 
             if ($error) { 
                    echo answerpay($_REQUEST['type'], 12, $pay_for, $order_amount, $order_currency, 'Error in parameters data: '.$error, $onpay_id); 
                    exit;
             }


            $booking_id = $payments_in_obj->booking_id;
            $this->db_obj->get($booking_id);
            if (!$this->db_obj->N) {
                echo 'www'.answerpay($_REQUEST['type'], 10, $pay_for, $order_amount, $order_currency, 'Cannot find any pay rows acording to this parameters: wrong payment', $onpay_id);
                exit;

                //$this->msg = BOOKING_NOT_FOUND;
                //return;
            }

            if ($this->db_obj->book_status == 4) {
                echo 'www'.answerpay($_REQUEST['type'], 10, $pay_for, $order_amount, $order_currency, 'Cannot find any pay rows acording to this parameters: wrong payment', $onpay_id);
                exit;
                //$this->msg = ALREADY_PAYED;
                //return;
            }

            $user_id_from = $payments_in_obj->user_id;
            $ammount = $payments_in_obj->ammount;
            $currency = $payments_in_obj->currency;

            $villa_obj = DB_DataObject::factory('villa');
            $villa_obj->get($this->db_obj->villa_id);
            if (!$villa_obj->user_id) {
                $this->msg = VILLA_NOT_FOUND;
                return;
            }
            $user_id_to = $villa_obj->user_id;


            $transactions_obj = DB_DataObject::factory('transactions');

            $result = $transactions_obj->fromToTransaction($user_id_from, $user_id_to, $ammount, $currency, $this->db_obj->id, $payments_in_obj->id);


            //echo $result;

            if ($result) {
                $this->db_obj->book_status = 4;
                $this->db_obj->update();
                $payments_in_obj->external_id = $onpay_id;
                $payments_in_obj->status = 1;
                $payments_in_obj->update();
                $this->msg = PAYED;
                $this->infoLetters($villa_obj, $user_id_from);
                echo answerpay($_REQUEST['type'], 0, $pay_for, $order_amount, $order_currency, 'OK', $onpay_id);
                exit;


                //return;
            } else {
                $this->msg = DVS_ERROR;
               echo answerpay($_REQUEST['type'], 9, $pay_for, $order_amount, $order_currency, 'Error in mechant database queries: operation or balance tables error', $onpay_id);
               exit;
                return;
            }

        } // type= pay

        exit;
        /*
        $this->db_obj->query('BEGIN');
        $transaction_id = $this->db_obj->campaignTransaction($campaigns_obj);
        if ($transaction_id < 0) {
            $this->msg = 'ERROR_PAY';
        } else {
            $old_campaigns_obj = $campaigns_obj->clone();
            $campaigns_obj->status_id = 3;
            $result = $campaigns_obj->update($old_campaigns_obj);
            if ($result) {
                $this->msg = 'UPDATE_ROW';
                $this->db_obj->query('COMMIT');
            }
        }
        */
        //$this->db_obj->qs = '?op=campaigns&act=show&cid='.$this->db_obj->campaign_id;


        //$this->db_obj->qs = '?op=booking&id='.$this->db_obj->id;
        //$this->goLocation();
    }

    function infoLetters($villa_obj, $user_id_from)
    {
        //Владелец виллы
        $users_obj_to = DB_DataObject::factory('users');
        $users_obj_to->get($villa_obj->user_id);

        //Владелец виллы
        $users_obj_from = DB_DataObject::factory('users');
        $users_obj_from->get($user_id_from);


        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj_to->email;
        $data['subject'] = 'ruRenter.ru payment for #'.$this->db_obj->id;
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->db_obj->id;
        $data_arr = array(
            'booking_id' => $this->db_obj->id,
            'ammount' => $this->db_obj->price,
            'currency' => $this->db_obj->currency,
            'booking_link' => $booking_link,
            'villa_id' => $villa_obj->id,
            'villa_name' => $villa_obj->title,
            'user_id_from' => $user_id_from,
            'user_name_from' => $users_obj_from->name,
            'start_date' => $this->db_obj->start_date,
            'end_date' => $this->db_obj->end_date,
            );
        $data['body'] = DVS_Mail::letter($data_arr, 'pay_letter.tpl');
        if ($users_obj_to->email) {
            //print_r($data);
            DVS_Mail::send($data);
        }
        $data['to'] = $users_obj_from->email;
        if ($users_obj_from->email) {
            DVS_Mail::send($data);
        }
        $data['to'] = MAIL_ADMIN;
        //$data['subject'] = 'ruRenter.ru booking '.$this->db_obj->id;
        //$data['body'] = DVS_Mail::letter($data_arr, 'pay_letter.tpl');
        DVS_Mail::send($data);
        
    }

    function process_api_request() {
        $rezult = ''; 
        $error = ''; 
        //проверяем чек запрос 
        if ($_REQUEST['type'] == 'check') { 
            //получаем данные, что нам прислал чек запрос 
            $order_amount 	= $_REQUEST['order_amount']; 
            $order_currency = $_REQUEST['order_currency']; 
            $pay_for 				= $_REQUEST['pay_for']; 
            $md5 						= $_REQUEST['md5']; 
            //выдаем ответ OK на чек запрос 
            $rezult = answer($_REQUEST['type'],0, $pay_for, $order_amount, $order_currency, 'OK'); 
        } 

        //проверяем запрос на пополнение 
        if ($_REQUEST['type'] == 'pay') { 
            $onpay_id 					= $_REQUEST['onpay_id']; 
            $pay_for 						= $_REQUEST['pay_for']; 
            $order_amount 			= $_REQUEST['order_amount']; 
            $order_currency			= $_REQUEST['order_currency']; 
            $balance_amount 		= $_REQUEST['balance_amount']; 
            $balance_currency 	= $_REQUEST['balance_currency']; 
            $exchange_rate 			= $_REQUEST['exchange_rate']; 
            $paymentDateTime 		= $_REQUEST['paymentDateTime']; 
            $md5 								= $_REQUEST['md5']; 
        
            //производим проверки входных данных 
            if (empty($onpay_id)) {$error .="Не указан id<br>";} 
            else {if (!is_numeric(intval($onpay_id))) {$error .="Параметр не является числом<br>";}} 
            if (empty($order_amount)) {$error .="Не указана сумма<br>";} 
            else {if (!is_numeric($order_amount)) {$error .="Параметр не является числом<br>";}} 
            if (empty($balance_amount)) {$error .="Не указана сумма<br>";} 
            else {if (!is_numeric(intval($balance_amount))) {$error .="Параметр не является числом<br>";}} 
            if (empty($balance_currency)) {$error .="Не указана валюта<br>";} 
            else {if (strlen($balance_currency)>4) {$error .="Параметр слишком длинный<br>";}} 
            if (empty($order_currency)) {$error .="Не указана валюта<br>";} 
            else {if (strlen($order_currency)>4) {$error .="Параметр слишком длинный<br>";}} 
            if (empty($exchange_rate)) {$error .="Не указана сумма<br>";} 
            else {if (!is_numeric($exchange_rate)) {$error .="Параметр не является числом<br>";}} 
        
            //если нет ошибок 
                if (!$error) { 
                    if (is_numeric($pay_for)) {
                        //Если pay_for - число 
                        $sum = floatval($order_amount); 
                        
                        $rezult = data_get_created_operation($pay_for);
                        if (mysql_num_rows($rezult) == 1) { 

                            //создаем строку хэша с присланных данных 
                            $md5fb = strtoupper(md5($_REQUEST['type'].";".$pay_for.";".$onpay_id.";".$order_amount.";".$order_currency.";".get_constant('private_code'))); 
                            //сверяем строчки хеша (присланную и созданную нами) 
                            if ($md5fb != $md5) {
                                $rezult = answerpay($_REQUEST['type'], 8, $pay_for, $order_amount, $order_currency, 'Md5 signature is wrong. Expected '.$md5fb, $onpay_id);
                            } else { 
                                $time = time(); 
                                $rezult_balance = get_constant('use_balance_table') ? data_update_user_balance($pay_for, $sum) : true;
                                $rezult_operation = data_set_operation_processed($pay_for);
                                //если оба запроса прошли успешно выдаем ответ об удаче, если нет, то о том что операция не произошла 
                                if ($rezult_operation && $rezult_balance) {
                                    $rezult = answerpay($_REQUEST['type'], 0, $pay_for, $order_amount, $order_currency, 'OK', $onpay_id);
                                } else {
                                    $rezult = answerpay($_REQUEST['type'], 9, $pay_for, $order_amount, $order_currency, 'Error in mechant database queries: operation or balance tables error', $onpay_id);
                                } 
                            }
                        } else {
                            $rezult = answerpay($_REQUEST['type'], 10, $pay_for, $order_amount, $order_currency, 'Cannot find any pay rows acording to this parameters: wrong payment', $onpay_id);
                        } 
                    } else {
                        //Если pay_for - не правильный формат 
                        $rezult = answerpay($_REQUEST['type'], 11, $pay_for, $order_amount, $order_currency, 'Error in parameters data', $onpay_id); 
                    } 
                } else {
                    //Если есть ошибки 
                    $rezult = answerpay($_REQUEST['type'], 12, $pay_for, $order_amount, $order_currency, 'Error in parameters data: '.$error, $onpay_id); 
                } 
        } 
        return $rezult;
    }
}

?>