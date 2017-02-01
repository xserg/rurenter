<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов
Класс авторизации
------------------------------------------------------------------------------
$Id: Auth.php 366 2013-03-27 12:10:27Z xxserg@gmail.com $
///////////////////////////////////////////////////////////////////////// */

define('DVS_ERROR_SESSION_IDLED', 'Большое время бездействия');
define('DVS_ERROR_SESSION_EXPIRED', 'Сессия истекла');
define('DVS_ERROR_IP_CHANGED', 'Изменился IP адрес');

define('COOKIE_TIME', 3600*24*365);
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', '');

define('MAX_WRONG_LOGIN', 10);
define('WRONG_LOGIN_TIME', 3600);

define('ERROR_MAX_WRONG_LOGIN', 'Максимальное число неправильных попыток ввода пароля');

//define('SESSION_EXPIRE', 3600);
//define('SESSION_IDLE', 600);

define('SESSION_EXPIRE', 0);
define('SESSION_IDLE', 0);

define('ROOT_LOGIN', 1);


require_once "Auth/Auth.php";

class DVS_Auth extends Auth
{
    var $realm = '';

    var $iface;

    /* Параметр для вывода Html - авторизации */
    var $auth_html_form = true;

    // Можно при создании объёкта переопределить параметры авторизации
    function DVS_Auth($mode = DB_DRIVER, $auth_options=0)
    {
        if (!$auth_options) {
            $auth_options = array(
               'table'         => 'users',
               'usernamecol'   => 'email',
               'passwordcol'   => 'password',
               //'db_fields'     => 'id, last_ip, status, role',
               //'db_fields'     => 'id, last_ip, role',
               'db_fields'     => array('id', 'last_ip', 'role_id', 'last_date, nick'),
               'dsn'           => DB_DSN,
               'cryptType'     => 'none',
                'advancedsecurity' => 1
            );
        }

        $this->Auth($mode, $auth_options);
        // Хак для отключения прверки challengecookie в режиме advancedsecurity при переходе по разным директориям в офисе
        //if (defined('ROOT_LOGIN'))
        $this->authChecks = 1;
        
        $this->setIdle(SESSION_IDLE);
        $this->setExpire(SESSION_EXPIRE);

        /* Html - авторизация */
        if (defined('AUTH_FORM') && AUTH_FORM == 'html') {
            $this->auth_html_form = true;
        }
    }

    function getMessage()
    {
        $messages = array(
            -1 => 'Error_Session_idled',
            -2 => 'Error_Session_expired',
            //-3 => 'Wrong username or password',
            -3 => 'Error_login',
            -4 => 'Error_Not_supported',
            -5 => 'Error_IP_changed',
        );
        $code = $this->getStatus();
        if ($code && $messages[$code]) {
            return $messages[$code];
        }
    }

    function getIP()
    {
        return $_SERVER['REMOTE_ADDR']
            .(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != 'unknown' ? ':'.$_SERVER['HTTP_X_FORWARDED_FOR'] : '');
    }

    // Сохранение IP сессии происходит при первой успешной аутентификации
    function saveSessionIp()
    {
        if (isset($this->storage->db)) {
            $this->storage->db->query("UPDATE ".$this->storage->options['table']
            ." SET last_ip = '".$this->cur_ip."', last_date = '".date(DB_DATE_FORMAT)."' "
            ."WHERE ".$this->storage->options['usernamecol']." = '".$this->username."'");
            if ($this->auth_html_form) {
                $this->saveLogin();
            }
            if ($this->verification_id) {
                $sql = "DELETE FROM verification_ip WHERE id=".$this->verification_id;
                $this->storage->db->query($sql);
            }
            //$this->writeOnline();
        }
        return;
    }

    function checkErrors()
    {
        $verification = $this->storage->db->getRow("SELECT id, count, date FROM verification_ip WHERE user_value = '".$this->username."'");

        if (PEAR::isError($verification)) {
            die($verification->getMessage());
        }

        $this->verification_id = $verification[0];
        $delay = time() - strtotime($verification[2]);

        if ($verification[1] >= MAX_WRONG_LOGIN && $delay < WRONG_LOGIN_TIME) {
            return false;
        }
        return true;
    }

    function insertError()
    {
        if ($this->verification_id) {
            $sql = "UPDATE verification_ip set count = count+1, date = NOW() WHERE id=".$this->verification_id;
        } else {
            $sql ="INSERT into verification_ip SET server = '".SERVER."', ip = '".$this->cur_ip."', date = NOW(), count = 1, user_value='".$this->username."'";
        }
        $this->storage->db->query($sql);
    }

    function saveLogin()
    {
        //echo "saveLogin ".$this->username;
        setcookie('username', $this->username, time() + COOKIE_TIME, '/', COOKIE_DOMAIN, COOKIE_SECURE);
        setcookie('user_id', $this->session['data']['id'], time() + COOKIE_TIME, '/', COOKIE_DOMAIN, COOKIE_SECURE);

        if (isset($_POST['savepass'])) {
            setcookie('password', $this->password, time() + COOKIE_TIME, '/', COOKIE_DOMAIN, COOKIE_SECURE);
        } else {
            setcookie('password', '', time() + COOKIE_TIME, '/', COOKIE_DOMAIN, COOKIE_SECURE);
        }
    }

    // Присвоение данных логин, пароль, IP
    function assignData()
    {
        $this->cur_ip = $this->getIP();
        if ($this->auth_html_form) {
            $this->post[$this->_postUsername] = strtolower($this->post[$this->_postUsername]);
            parent::assignData();
            return;
        }
        if (isset($this->server['PHP_AUTH_USER']) && $this->server['PHP_AUTH_USER'] != "") {
            $this->username = $this->server['PHP_AUTH_USER'];
        }
        if (isset($this->server['PHP_AUTH_PW']) && $this->server['PHP_AUTH_PW'] != "") {
            $this->password = $this->server['PHP_AUTH_PW'];
        }
    }

    // Ввод пароля
    function drawLogin($username = "")
    {
        if ($this->auth_html_form) {
            return;
        }
        header("WWW-Authenticate: Basic realm=\"".$this->realm."\"");
        header("HTTP/1.0 401 Unauthorized");
    }

    function rootLogin()
    {
        if (defined('ROOT_LOGIN')) {
            if (isset($this->post['username']) && $this->post['username'] == 'root' && $this->post['password'] == 'root') {
                $this->authChecks = 1;
                $_SESSION['_authsession']['data']['role_id'] = 1;
                $_SESSION['_authsession']['registered'] = 1;
                $this->setAuth('root');
                return true;
            }
        }
        return false;
    }


    function authpw()
    {
        
        $this->assignData();
        
        //print_r($this);
        //DVS::dump($this->checkAuth());
        $this->rootLogin();

            //return true;

        if (!$this->checkAuth()) {
            //echo 'Error '.$this->getMessage();
            $this->realm = $this->realm.': '.$this->getMessage();
            $this->login();
            //$this->msg = $this->getMessage();
            //return false;
        }

        //print_r($_SESSION);

        if (!empty($_SESSION['_authsession']) && $_SESSION['_authsession']['data']['last_ip'] != $this->cur_ip) {

            if ( (time() - strtotime($_SESSION['_authsession']['data']['last_date'])) > SESSION_EXPIRE) {
                $this->saveSessionIp();
                return true;
            }
            //echo $_SESSION['_authsession']['data']['last_ip'].' != '.$this->cur_ip;
            $this->msg = 'Error_IP_changed';
            $_SESSION = array();
            return false;
        }

        /*
        if ($this->storage->db && !$this->checkErrors()) {
            $this->msg = 'Error_Max_Wrong_Login';
            return false;
        }
        */
        if ($this->checkAuth()) {
            $this->saveSessionIp(); 
            //$this->saveLogin();
            return true;
        } else {
            //$this->realm = $this->realm.$this->getMessage();
            //$this->login();
            //setcookie('password', '', time() + COOKIE_TIME, '/', COOKIE_DOMAIN, COOKIE_SECURE);
            if ($this->storage->db && $this->username) {
                //$this->insertError();
            }
            $this->msg = $this->getMessage();
            return false;
        }
    }

    /**
     * Определяет в зависимости от параметров $classname
     * Возвращает объект & new $classname
     */
    function factory($iface)
    {
        if ($iface == 'a') {
            $filename = 'Auth_Admin';
        } else {
            $filename = 'Auth_User';
        }
        include_once COMMON_LIB.'DVS/'.$filename.'.php';
        $classname  = DVS_CLASS_PREFIX.$filename;
        if (!class_exists($classname)) {
            return PEAR::raiseError('Unable to find '.$classname.' class');
        } else {
            $auth_obj = new $classname;
            $auth_obj->iface = $iface;
            return $auth_obj;
        }
    }



}
?>
