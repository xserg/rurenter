<?php
/**
 * @package villarenters.ru
 * ------------------------------------------------------------------------------
 * Класс инициилизирует свойства пректа, общие для всех интерфейсов
 * ------------------------------------------------------------------------------
 * $Id: Layout.php 368 2013-03-29 11:03:38Z xxserg $
 */

class Project_Layout
{
    public $op;

    public $act;

    public $project_name = 'rurenter.ru';

    /* Заголовок страницы проекта */
    public $project_title = 'rurenter.ru';

    public $registered = false;

    public $username;

    public $active_menu;

    public $language;

    private $menu_text;

    public $template_name = 'villa_index.tpl';

    public $login_info_tpl = 'subnav.tpl';

    //public $template_name = 'zoopla.tpl';

    //public $template_name = 'villa_index.tpl';


    /**
     * @param $role string
     * @return Layout Object
     */
    function factory($role = 'iu', $lang = 'ru')
    {
        global $_DVS;
        $project_layout_filename = 'Layout_'.ucfirst($_DVS['CONFIG']['INAMES'][$role]);
        $project_layout_classname = DVS_PROJECT_CLASS_PREFIX.$project_layout_filename;
        DVS::loadClass($project_layout_classname, PROJECT_ROOT.LAYOUT_FOLDER.$project_layout_filename.'.php');
        $project_layout_obj = new $project_layout_classname($lang);
        return $project_layout_obj;
    }

    function __construct($lang)
    {
        $this->language = $lang;
        $lang_file = PROJECT_ROOT.LAYOUT_FOLDER.'Lang_'.$this->language.'.php';
        if (!file_exists($lang_file)) {
            $lang_file = PROJECT_ROOT.LAYOUT_FOLDER.'Lang_ru.php';
        }
        require_once $lang_file;
        $GLOBALS['_DVS']['LANG'] = $lang;
        foreach ($lang['constant'] as $ck => $cv) {
            define(strtoupper($ck), $cv);
        }
        foreach ($GLOBALS['_DVS']['LANG']['layout'][$this->iface] as $ik => $iv) {
            $this->$ik = $iv;
        }
        if ($username = $this->getUsername()) {
            $this->username = $username;
            $this->registered = true;
        }

    }


    function getTopMenu($tpl)
    {
        $op = DVS::getVar('op');
        $tpl->addBlockfile('TOP', 'TOP', 'menu_top.tpl');
        $i = 0;
        $width = round(100/(sizeof($this->menu_text) + 1));
        foreach ($this->menu_text as $href => $text) {
            if ($i == 0) {
                $liclass = 'first';
            }else  if (($i+1) == sizeof($this->menu_text)) {
                $liclass = 'last';
            } else {
                $liclass = '';
            }
            $i++;
                $tpl->setVariable(array(
                    'MENU_HREF' => $href,
                    'MENU_TEXT' => $text,
                    'WIDTH' => $width
                    )
                );
                if ($liclass) {
                      $tpl->setVariable(array('LICLASS' => ' class='.$liclass));
                }
                $tpl->parse('MENU_ITEM');
        }
    }

    function getBottomMenu($tpl)
    {
        foreach ($this->menu_bottom_text as $href => $text) {
                $tpl->setVariable(array(
                    'MENU_BOTTOM_HREF' => $href,
                    'MENU_BOTTOM_TEXT' => $text
                    )
                );
                $tpl->parse('MENU_BOTTOM_ITEM');
        }
    }


/*
    function getMenu($tpl)
    {
        //echo '<pre>';
        //print_r($_SERVER);
        //print_r($_COOKIE);
        //print_r($_SESSION);
        $menu = $this->menu_text;
        $op = DVS::getVar('op');
        $tpl->addBlockfile('NAVIGATION', 'NAVIGATION', 'navigation.tpl');
        foreach ($menu as $href => $text) {
            print_r($arr);
            $tpl->setVariable(array('KEY' => $href, 'VAL' => $text, 'LICLASS' => 'n-reg'));
            //if ($op && preg_match("/".$op."/", $arr[0])) {
            if ($arr[0] == $this->active_menu) {
                $tpl->setVariable('HCLASS', 'class=here');
            }
            $tpl->parse('MENU');
        }
        //$tpl->touchBlock('SEARCH');
        //return $tpl->get();
    }
*/
    function getMenu($tpl)
    {
        //print_r($_COOKIE);
        //print_r($_SESSION);
        //print_r($GLOBALS);
        $op = DVS::getVar('op');
        $tpl->addBlockfile('LEFT', 'LEFT', 'menu.tpl');
        $i = 0;
        foreach ($this->menu_text as $href => $text) {
            //$text = $GLOBALS['_DVS']['LANG']['layout'][$this->iface]['menu'][$i];
            //$text = $this->menu_text[$i];
            $i++;
            //if (empty($arr[0])) {
            if (preg_match("/^zag/", $href)) {
                $tpl->parse('MENU_SECTION');
                $tpl->setVariable(array('MENU_SECTION_NAME' => $text, 'LICLASS' => ' class=o'));
            } else {
                if (isset($this->menu_counters) && in_array($i, array_keys($this->menu_counters))) {
                    $text .= ' ('.$this->menu_counters[$i].')';
                }
                $tpl->setVariable(array(
                    'MENU_HREF' => $href,
                    'MENU_TEXT' => $text
                    //'MENU_TEXT' => $arr[1],
                    //'LICLASS' => ' class=c'
                    )
                );
                $tpl->parse('MENU_ITEM');
            }
        }
    }


    function loginInfo($tpl)
    {
        $tpl->addBlockfile('LOGIN_FORM', 'LOGIN_FORM', $this->login_info_tpl);
        //$tpl->touchBlock('LOGIN_FORM');
        $page_arr['HELLO'] = $this->login_title ? $this->login_title : 'Вход в личный офис';
        $page_arr['USERNAME'] = $this->username;
        $page_arr['SERVER_URL'] = SERVER_URL;
        $page_arr['LOGIN_ALT'] = DVS_LOGIN_ALT;
        $page_arr['LOGIN'] = DVS_LOGIN;
        $page_arr['CONTACT_US'] = DVS_CONTACT_US;
        $page_arr['EXIT'] = DVS_EXIT;
        if (!$page_arr['USERNAME']) {
            $tpl->touchBlock('USER');
            $tpl->hideBlock('OFFICE');
        } else {
            $tpl->hideBlock('USER');
        }
        echo $this->lang;
        if ($this->language != 'ru') {
            $page_arr['SERVER_URL'] = '/'.$this->language;
        }
        if ($this->iface != 'user') {
            $page_arr['OFFICE'] = 'office/';
        }
        //$page_arr['IFACE'] = '/'.$this->iface_url.'/';
        $tpl->setVariable($page_arr);
    }

    function loginData()
    {
        //echo basename  ( $_SERVER["SCRIPT_FILENAME"]);
        $page_arr['USERNAME']  = $_POST['username'] ? $_POST['username'] : ($_COOKIE['username'] ? $_COOKIE['username'] : '');
        $page_arr['PASSWORD']  = $_COOKIE['password'] ? $_COOKIE['password'] : ($_POST['password'] ? '' : '');
        
        //$page_arr['PASSWORD_TYPE'] = $_COOKIE['password'] ? 'password' : 'text';
        $page_arr['CHECKED']   = $_COOKIE['password'] ? ' CHECKED' : '';
        $page_arr['REFERER']   = $_POST['referer'] ? $_POST['referer'] : $_SERVER['HTTP_REFERER'];
        //print_r($page_arr);
        return $page_arr;

    }

    function getUsername()
    {
        //print_r($_SESSION);
        //print_r($_COOKIE);

        return $_COOKIE['username'];
        if ($_COOKIE['PHPSESSID'] || $_SESSION['_authsession']) {
            if (empty($_SESSION['_authsession'])) {
                /*
                @session_start();
                if ($_SESSION['_authsession']['registered'] != 1) {
                    session_destroy();
                    setcookie('PHPSESSID', '', time() + 3600, '/');
                    //setcookie('username', '', time() + 3600, '/');
                    return false;
                }
                */
            }
            if ($_SESSION['_authsession']['registered'] && $_SESSION['_authsession']['username']) {
                return $_SESSION['_authsession']['username'];
            }
            return false;
        }
    }
}
?>
