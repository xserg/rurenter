<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс создания статических страниц
------------------------------------------------------------------------------
$Id: Static.php 401 2013-04-17 14:25:57Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Page.php';

class DVS_Static extends DVS_Page
{
    private $lang_ru = array(
        'Auth' => 'Авторизация',
        'login_title' => 'Вход для зарегистрированных пользователей',
        'login' => 'Логин',
        'password' => 'Пароль',
        'remember_password' => 'Забыли пароль?',
        'save_pass' => 'Запомнить',
        'registration' => 'Регистрация',
        'log_in_with' => 'или воити через:',
    );



    private $lang_en = array(
        'Auth' => 'Auth',
        'login_title' => 'Sign in for site users',
        'login' => 'Login',
        'password' => 'password',
        'remember_password' => 'Forget password?',
        'save_pass' => 'Save password',
        'registration' => 'Registration',
        'log_in_with' => 'log in with:',

    );

    function showPage()
    {
        $this->createTemplateObj();
        $this->words = $this->{'lang_'.$this->lang};
        if ($_GET['act'] == 'help') {
            $this->page_arr['CENTER_TITLE'] = $this->page_arr['CENTER_SUB_TITLE'] = 'Часто задаваемые вопросы';
            $this->page_arr['CENTER'] = $this->showHelp();
        } else if ($_GET['act'] == 'error_login' && AUTH_FORM == 'html') {
            //$this->hide_blocks = 'RIGHT';
            $this->page_arr['CENTER_TITLE'] = $this->page_arr['CENTER_SUB_TITLE'] = $this->words['Auth'];
            $this->page_arr['CENTER'] = $this->showLogin();
        } else if ($_GET['act'] == 'logout') {
            require_once COMMON_LIB.'DVS/Page.php';
            DVS_Page::logOut();
        } else {
            $filename = PROJECT_ROOT.'inc/'.$this->act.'.inc';
        }

        $this->loadTemplate();
        $this->layout_obj->getPageData(&$this->template_obj);


        if (file_exists($filename)) {
            $this->page_arr['CENTER'] = file_get_contents($filename);
        }

        if (!$this->page_arr['CENTER'] && defined('DVS_'.strtoupper($this->act))) {
            $this->showMessage($this->act);
        }
        if ($this->msg) {
            $this->showMessage($this->msg);
        }
        return $this->parsePage();
    }

    /**
    * Генерирует HTML - форму вводу логин-пароля
    * @return string
    */
    function showLogin()
    {
        $this->src_files = array(
            'CSS_SRC'  => array('/css/form.css')
        );
        $this->template_obj->loadTemplateFile('login_page.tpl', 1, 1);
        /*
        $this->template_obj->setVariable(array(
            'IMAGE_URL' => IMAGE_URL,
            'USERNAME'  => $_POST['username'] ? $_POST['username'] : $_COOKIE['username'],
            'PASSWORD'  => $_COOKIE['password'],
            'CHECKED'   => $_COOKIE['password'] ? ' CHECKED' : '',
        ));
        */
        $this->words['loginza'] = str_replace('{TOKEN_URL}', urlencode('http://rurenter.ru/loginza.php'), file_get_contents(PROJECT_ROOT.'tmpl/loginza.tpl'));
        //print_r($this->layout_obj->loginData());
        $this->template_obj->setVariable($this->layout_obj->loginData());
        $this->template_obj->setGlobalVariable($this->words);
        $this->template_obj->parse();
        return $this->template_obj->get();
    }

    function showHelp()
    {
        $filename  = COMMON_CACHE_FOLDER.'help/'.SERVER.'.inc';
        $cfilename = COMMON_CACHE_FOLDER.'help/all.inc';
        $this->act = 'error_help';
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        if (file_exists($cfilename)) {
            return file_get_contents($cfilename);
        }
        $this->act = 'error_help';
        return;
    }
}
?>
