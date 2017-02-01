<?php
/**
 * Входящий платеж
 * @package rurenter
 * $Id: Transactions_Pay.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once COMMON_LIB.'DVS/Auth.php';

define('DVS_ERROR_PAY', 'Недостаточная сумма на счету!');
define('PAYED', 'Заявка оплачена!');

class Project_Booking_Paywm extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ou' => 1, 'iu' => 1);

    /**
        LMI_PAYEE_PURSE
        LMI_PAYMENT_AMOUNT
        LMI_PAYMENT_NO
        LMI_MODE
        LMI_SYS_INVS_NO
        LMI_SYS_TRANS_NO
        LMI_SYS_TRANS_DATE
        LMI_SECRET_KEY
        LMI_PAYER_PURSE
        LMI_PAYER_WM
        LMI_HASH
    */


    function getPageData()
    {   
        $this->proccesData();
        $this->logRequest();
        if ($this->error) {
            echo 'ERROR: '.$this->error;
        } else {
            echo 'OK';
        }
        exit;
    }


    function proccesData()
    {   

        $purse = DVS::getVar('LMI_PAYEE_PURSE', 'word', 'post');
        $ammount = DVS::getVar('LMI_PAYMENT_AMOUNT', 'word', 'post');
        $payment_in_id = DVS::getVar('LMI_PAYMENT_NO', 'int', 'post');
        $lmi_mode = DVS::getVar('LMI_MODE', 'int', 'post');
        $invs_no = DVS::getVar('LMI_SYS_INVS_NO', 'word', 'post');
        $trans_no = DVS::getVar('LMI_SYS_TRANS_NO', 'word', 'post');
        $trans_date = DVS::getVar('LMI_SYS_TRANS_DATE', 'word', 'post');
        //$secret_key = DVS::gatVar('LMI_SECRET_KEY', 'word', 'post');
        $payer_purse = DVS::getVar('LMI_PAYER_PURSE', 'word', 'post');
        $payer_wm = DVS::getVar('LMI_PAYER_WM', 'word', 'post');

        $hash = DVS::getVar('LMI_HASH', 'word', 'post');

        $description = DVS::getVar('LMI_PAYMENT_DESC', 'word', 'post');

        if ($purse != WMR_PURSE) {
            $this->error = 'error_purse';
            return;
        }

        $chech_hash = 
            $purse
            .$ammount
            .$payment_in_id
            .$lmi_mode
            .$invs_no
            .$trans_no
            .$trans_date
            .WM_KEY
            .$payer_purse
            .$payer_wm ;

        if (strtoupper(md5($chech_hash)) != $hash) {
            $this->error = 'error_hash';
            return;
        }

        $payments_in_obj = DB_DataObject::factory('payments_in');
        $payments_in_obj->get($payment_in_id);

        if (!$payments_in_obj->N) {
            $this->error = PAYMENT_IN_NOT_FOUND;
            return;
        }

        if ($payments_in_obj->ammount != $ammount) {
            $this->error = 'error_ammount';
            return;
        }

        if ($payments_in_obj->currency != 'RUR') {
            $this->error = 'error_currency';
            return;
        }


            $booking_id = $payments_in_obj->booking_id;
            $this->db_obj->get($booking_id);
            if (!$this->db_obj->N) {
                //$this->msg = BOOKING_NOT_FOUND;
                $this->error = BOOKING_NOT_FOUND;
                return;
            }

            if ($this->db_obj->book_status == 4) {
                $this->error = ALREADY_PAYED;
                //$this->msg = ALREADY_PAYED;
                return;
            }

            $user_id_from = $payments_in_obj->user_id;
            $ammount = $payments_in_obj->ammount;
            $currency = $payments_in_obj->currency;

            $villa_obj = DB_DataObject::factory('villa');
            $villa_obj->get($this->db_obj->villa_id);
            if (!$villa_obj->user_id) {
                $this->error = VILLA_NOT_FOUND;
                //$this->msg = VILLA_NOT_FOUND;
                return;
            }
            $user_id_to = $villa_obj->user_id;

            $transactions_obj = DB_DataObject::factory('transactions');
            $result = $transactions_obj->fromToTransaction($user_id_from, $user_id_to, $ammount, $currency, $this->db_obj->id, $payments_in_obj->id);
            //echo $result;

            if ($result) {
                $this->db_obj->book_status = 4;
                $this->db_obj->update();
                $payments_in_obj->external_id = $trans_no;
                $payments_in_obj->status = 1;
                $payments_in_obj->update();
                $this->msg = PAYED;
                echo 'OK';
                $this->infoLetters($villa_obj, $user_id_from);
                return;
            } else {
                //$this->msg = DVS_ERROR;
                $this->error = SQL_ERROR;
                return;
            }

        exit;

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
        $data['subject'] = 'ruRenter.ru WebMoney payment for #'.$this->db_obj->id;
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

    function logRequest()
    {
        $str = date("Y-m-d H:i:s\n");
        $str .= DVS_Auth::getIp()."\n";

        foreach ($_REQUEST as $k => $v) {
            $str .= "$k => $v\n";
        }
        if ($this->error) {
            $str .= 'ERROR: '.$this->error."\n\n";
        } else {
            $str .= "OK\n\n";
        }
        file_put_contents(PROJECT_ROOT.'logs/webmoney.log', $str, FILE_APPEND);
    }
}

?>