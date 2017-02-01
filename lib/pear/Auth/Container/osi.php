<?php

//include_once('XML/RPC.php');

class Auth_Container_Osi {
   
    function fetchData($username, $password)
    {
            $this->activeUser = $username;
            return true;
    }
    
}
?>