<?php
//require_once PROJECT_ROOT.'DataObjects/Users.php';

class DBO_Booking_Admin extends DBO_Booking
{
    public $role = 'aa';

    public $listLabels   = array(
                     'id'         => 'width=80',

                    'villa_title'         => '',
                     'post_date'        => '',
                    'period' => '',
                     //'start_date'       =>  '',
                     //'end_date'         =>  '',
                     'book_status'      =>  '',
                     //'price'            =>  '',
                     //'currency'         =>  '',
    );

    function tableRow()
    {
        return array(
            'id'    => '<a href="?op=booking&act=admincard&id='.$this->id.'">Бронь '.$this->id.'</a>',
            'villa_title' =>  '<a href="/villa/'.$this->villa_id.'.html" target=_blank>'.$this->villa_title.'</a>',
            'period' => DVS_Page::RusDate($this->start_date).' - '.DVS_Page::RusDate($this->end_date),
            //'start_date'       =>  $this->start_date,
             //'end_date'         =>  $this->end_date,
             'book_status'      =>  $this->book_status_arr[$this->book_status],
             'post_date'        => DVS_Page::RusDate($this->post_date),
                'price'            =>  $this->price,
             'currency'         =>  $this->currency,
        );
    }

    function preGenerateList()
    {
        if (isset($this->book_status) && $this->book_status == '') {
            unset($this->book_status);
        }
        $villa_obj = DB_DataObject::factory('villa');
        $this->selectAs();
        $this->joinAdd($villa_obj, 'LEFT');
        $this->selectAs(array('title','user_id'), 'villa_%s', 'villa');
    }
}