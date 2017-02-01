<?php
/**
 * договор аренды
 * @package rurenter
 * $Id: Transactions_Pay.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Booking_Agree extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ou' => 1, 'oc' => 1);

    function getPageData()
    {   
        if (!$this->db_obj->N) {
            //$this->msg = BOOKING_NOT_FOUND;
            //return;
        }

        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($this->db_obj->villa_id);
        if (!$villa_obj->user_id) {
            $this->msg = VILLA_NOT_FOUND;
            return;
        }

        $owner = $this->getUser($villa_obj->user_id);
        $renter = $this->getUser($this->db_obj->user_id);

        $vals = array(
            'owner_name' => $owner->name,
            'owner_lastname' => $owner->lastname,
            'owner_address' => $owner->address.' '.$owner->house_num.' '.$owner->street.' '.$owner->city.' '.$owner->country,

            'renter_name' => $renter->name,
            'renter_lastname' => $renter->lastname,

            'user_name' => $renter->name,
            'user_lastname' => $renter->lastname,

            'user_address' => $renter->address.' '.$renter->house_num.' '.$renter->street.' '.$renter->city.' '.$renter->country,

            'villa_id' => $this->db_obj->villa_id,
            'villa_name' => $villa_obj->title,

            'price' =>  $this->db_obj->price,
            'currency' => $this->db_obj->currency,

            'prepayment' => $this->db_obj->prepay,
            'balance' => $this->db_obj->price - $this->db_obj->prepay,
            'balance_days' => 30,
            'breakage_deposit' => $villa_obj->breakage,

            'start_date' => $this->db_obj->start_date,
            'end_date' => $this->db_obj->end_date,
        );

        $this->createTemplateObj();

        $template = 'renter_agree.tpl';
        if ($this->lang == 'en') {
            $template = 'renter_agree_en.tpl';
        }
        $this->template_obj->loadTemplateFile($template);

        //$this->template_obj->loadTemplateFile('booking_agree.tpl');


/*
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
                    'PAY_SERVICE_ID' => $_POST['pay_service_id'],

                    'PAY_ACTION' => '?op=payments_in&act=request',
                ));
                $this->template_obj->touchBlock('PAY');
 

        $this->template_obj->addBlockFile('RENT_AGREE', 'RENT_AGREE', 'renter_agree.tpl');
*/

        //$this->template_obj->loadTemplateFile('rent_agree.tpl');
        $this->template_obj->setVariable($vals);

        //$page_arr['CENTER_TITLE'] = 'Договор аренды';
        $page_arr['CENTER'] = nl2br($this->template_obj->get());
        return $page_arr;
        
    }

    function getUser($id)
    {
        $user_obj = DB_DataObject::factory('users');
        $user_obj->get($id);
        if ($user_obj->N) {
            return $user_obj;
        }
        return false;
    }

    function rurPrice($price, $currency)
    {
        $rate = DBO_Villa::currencyRate();
        //print_r($rate);
        return DBO_Villa::rurPrice($price, $currency, $rate)*1.02;
    }
}

?>