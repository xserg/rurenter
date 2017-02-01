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

require_once PROJECT_ROOT.'../villa/DataObjects/Users.php';

//define('BOOKING_URL_NEW', 'http://www.rentalsystems.com/Booking.aspx');
define('BOOKING_URL_NEW', 'http://www.villarenters.com/Booking.aspx');

class Project_Booking_log_Admincard extends DVS_Dynamic
{
    // Права
    //var $perms_arr = array('' => 1, 'ou' => 1, 'ar' => 1);

    private $lang_ru = array(
        'price' => 'Цена',
        'Sleeps' => 'спальных мест:',
        'people' => 'Кол-во человек',
    );

    private $lang_en = array(
        'price' => 'Price',
        'Sleeps' => 'sleeps:',
        'people' => 'people',
    );


    function getPageData()
    {

        $this->words = $this->{'lang_'.$this->lang};

        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }


        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($this->db_obj->villa_id);

        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->db_obj->user_id);

        $book_form_obj = new Booking_Form($this->words);

        if (!$price =$this->db_obj->price) {
            $price = $book_form_obj->getPrice($villa_obj, $this->db_obj->start_date, $this->db_obj->end_date);
        }

        $start_arr = explode ("-", $this->db_obj->start_date);
        $days = $this->db_obj->daysCount();


        $url = BOOKING_URL_NEW.'?ref='.$this->db_obj->villa_id.'&day='.$start_arr[2].'&mth='.$start_arr[1].'&yr='.$start_arr[0].'&dur='.$days.'&adl='.$this->db_obj->people.'&chd='.$this->db_obj->childs.'&inf='.$this->db_obj->infants.'&rag='.VR_ACCOUNT_NUM.'&rcam=';

        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('booking_log_admincard.tpl');


            $users_age_obj = new DBO_Users;

            $age_arr = $users_age_obj->age_arr;

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
                        'start_day' => $start_arr[2],
                        'start_month' => $start_arr[1],
                        'start_year' => $start_arr[0],
                        'days' => $days,
          
                        'people' =>  $this->db_obj->people,
                        'childs' => $this->db_obj->childs,
                        'infants' => $this->db_obj->infants,
                        'url' => $url,
                        'renter_id' => $this->db_obj->user_id,
                        'user_name' => $users_obj->name,
                        'user_lastname' => $users_obj->lastname,
                        'user_age' => $age_arr[$users_obj->age],
                        'user_home_phone' => $users_obj->home_phone,
                        'user_mobile_phone' => $users_obj->mobile_phone,
                        'user_country' => $users_obj->country,
                        'user_city' => $users_obj->city,
                        'user_street' => $users_obj->street,
                        'user_house_num' => $users_obj->house_num,


                        'user_email' => $users_obj->email,
                        'user_password' => $users_obj->password,

                        
                        'user_reg_date' => $users_obj->reg_date,
                        'user_last_ip' => $users_obj->last_ip,
                        //'days' => $this->db_obj->daysCount(),
                        //'price' => $price,
                        //'currency' => $villa_obj->currency,
                        'note' => 'Примечания: '.$this->db_obj->note.'<br>',
                        'feedback_title' => $feedback_title,
            )
            );

        $this->showGuests($age_arr);
        $this->template_obj->hideBlock("LOGIN");
        if ($_SESSION['_authsession']['data']['id'] ==  $this->db_obj->user_id) {
            $this->template_obj->hideBlock('USER');
        }

        $this->template_obj->setVariable(array('feedback_title' => $feedback_title));

        $page_arr['CENTER_TITLE'] = 'Заявка';
        $page_arr['CENTER'] = $this->template_obj->get();
        return $page_arr;
    }


    function rurPrice($price, $currency)
    {
        $rate = DBO_Villa::currencyRate();
        //print_r($rate);
        return DBO_Villa::rurPrice($price, $currency, $rate)*1.02;
    }

    function showGuests($age_arr)
    {
        //DB_DataObject::DebugLevel(1);
        $guests_obj = DB_DataObject::factory('guests');
        $guests_obj->booking_log_id = $this->db_obj->id;
        $guests_obj->find();
        while ($guests_obj->fetch()) {
            //echo $guests_obj->guest_name;
            $this->template_obj->setVariable(array(
                'guest_name' => $guests_obj->guest_name,
                'guest_last_name' => $guests_obj->guest_lastname,
                'guest_age' => $age_arr[$guests_obj->age],
                ));
            $this->template_obj->parse('GUEST');
        }
    }
}

?>