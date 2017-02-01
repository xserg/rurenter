<?php
require_once PROJECT_ROOT.'DataObjects/Users.php';

class DBO_Booking_Loginuser extends DBO_Booking
{
    public $role = 'ou';


    function __construct()
    {
        if ($_SESSION['_authsession']['data']['id']) {
            $this->user_id = $_SESSION['_authsession']['data']['id'];
        }
    }

    function preGenerateList()
    {
        if (isset($this->book_status) && $this->book_status == '') {
            unset($this->book_status);
        }

        //if (!$this->villa_id) {
            $villa_obj = DB_DataObject::factory('villa');
            $this->selectAs();
            $this->joinAdd($villa_obj, 'LEFT');
            $this->selectAs(array('title','user_id'), 'villa_%s', 'villa');
            //$this->whereAdd("villa.user_id=".$_SESSION['_authsession']['data']['id']);

    }
}