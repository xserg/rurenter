<?php
require_once PROJECT_ROOT.'DataObjects/Smsinfo.php';

class DBO_Smsinfo_Client extends DBO_Smsinfo
{
    public $role = 'oc';


    function __construct()
    {
        $this->user_id = $_SESSION['_authsession']['data']['id'];
    }

}