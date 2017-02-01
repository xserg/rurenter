<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс авторизации пользователя в интерфейсе admin.php
------------------------------------------------------------------------------
$Id: Auth_Admin.php 83 2011-01-17 18:29:41Z xxserg $
//////////////////////////////////////////////////////////////////////////// */

require_once COMMON_LIB.'DVS/DVS.php';
require_once COMMON_LIB.'DVS/Auth.php';
require_once 'DB.php';

class DVS_Auth_Loginza extends DVS_Auth
{

    public $new_user = false;

    function getResult($token)
    {
        $sig=md5($token.LOGINZA_KEY); //Формируем сигнатуру.
        $kk="http://loginza.ru/api/authinfo?token=".$token."&id=".LOGINZA_ID."&sig=".$sig."";
        $b=file_get_contents($kk); //Получаем данные от Логинзы
        $authresult=json_decode($b,true);
        return $authresult;
    }

    function authLoginza()
    {
        $token = DVS::getVar('token', 'word', 'post');
        if (!$token) {
            $this->msg = 'No Token';
            //echo $this->msg;
            return 'error_login';
        }
        $res = $this->getResult($token);
        //echo '<pre>';
        //print_r($res);
        //exit;
        if ($res['error_type']) {
            $this->msg = $res['error_message'];
            return 'error_login';
        }
        if ($res['identity']) {
            $this->processLoginza($res);
            return true;
        }
    }

    function processLoginza($authresult)
    {
        require_once COMMON_LIB.'DVS/Dynamic.php';
        $users_obj = DVS_Dynamic::createDbObj('users');
        $loginza_obj = DVS_Dynamic::createDbObj('loginza');
        //echo '<pre>';
        //DB_DataObject::DebugLevel(1);
        
        if (!$authresult['email']) {
            header('Location: /reg/');
            exit;
            $authresult['email'] = $authresult['identity'];
        }
        

        $loginza_obj->get('identity', $authresult['identity']);
        if ($loginza_obj->user_id) {
            $users_obj->get($loginza_obj->user_id);
            $this->setAuthLoginza($loginza_obj->user_id, $authresult['email'], $users_obj->role_id);
            if (!$users_obj->role_id) {
                 header("location: /?op=users&act=role");
                 exit;
                 return;
            }
            $users_obj->last_date = date("Y-m-d H:i:s");
            $users_obj->last_ip = $this->getIP();
            $users_obj->update();
            return $loginza_obj->user_id;
        }
        if (!$loginza_obj->id) {
            if($loginza_obj->createIdentity($authresult)) {
                $users_obj->get('email', $authresult['email']);
                if (!$users_obj->id) {
                    $user_id = $this->createUser($users_obj, $authresult);
                } else {
                    $user_id = $users_obj->id;
                }
                $loginza_obj->user_id = $user_id;
                $loginza_obj->update();
            }
        } else {
             $users_obj->get('email', $authresult['email']);
             $user_id = $users_obj->id;
             $role_id = $users_obj->role_id;
            //echo 'Exist';
            //print_r($loginza_obj->toArray());
       }
       $this->setAuthLoginza($user_id, $authresult['email'], $role_id);
       return $user_id;
    }

/*
    function createIdentity($loginza_obj, $authresult)
    {
        $loginza_obj->identity = $authresult['identity'];
        $loginza_obj->provider = $authresult['provider'];
        $loginza_obj->nickname = iconv("UTF-8", "Windows-1251", $authresult['nickname']);
        $loginza_obj->email = $authresult['email'];
        if (is_array($authresult['name'])) {
            
        }
        return $loginza_obj->insert();
    }
*/
    function createUser($users_obj, $authresult)
    {
        $users_obj->email = $authresult['email'];
        $users_obj->lastname = iconv("UTF-8", "Windows-1251", $authresult['name']['last_name']);
        $users_obj->name = iconv("UTF-8", "Windows-1251", $authresult['name']['first_name']);
        $users_obj->reg_date = date("Y-m-d H:i:s");
        if ($_GET['villa_id']) {
            $users_obj->role_id = 'ou';
        }
        $users_obj->password = $users_obj->getPasswordRandom();
        $users_obj->reg_code = md5(uniqid(""));
        $users_obj->status_id = 2;
        if ($user_id = $users_obj->insert()) {
            $users_obj->userLetter();
            $this->new_user = true;
            return $user_id;
        }
    }

    function setAuthLoginza($user_id, $email, $role_id = 'ou')
    {
        // echo "$user_id, $email<br>";
        session_start();
        $this->authChecks = 1;
        $_SESSION['_authsession']['data']['role_id'] = $role_id;
        $_SESSION['_authsession']['registered'] = 1;
        $_SESSION['_authsession']['data']['id'] = $user_id;
        $_SESSION['_authsession']['username'] = $email;
        $this->setAuth($email);
        setcookie('username', $email, time() + COOKIE_TIME, '/', COOKIE_DOMAIN, COOKIE_SECURE);
        setcookie('user_id', $user_id, time() + COOKIE_TIME, '/', COOKIE_DOMAIN, COOKIE_SECURE);
        //print_r($auth_obj);
    }
}
?>
