<?php
require_once PROJECT_ROOT.'DataObjects/Villa_Form2.php';

class DBO_Villa_Client extends DBO_Villa_Form
{

    public $listLabels   = array(
            'id' => '',
            'add_date' => 'align=center',
            'main_image' => '',
            'title' =>  '',
            'status_id' =>'',
    );

    function __construct()
    {
        //print_r($_SESSION);
        $this->user_id = $_SESSION['_authsession']['data']['id'];
    }
}