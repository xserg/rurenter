<?php
/**
 * @package villarenters.ru
 * ------------------------------------------------------------------------------
 * Настройки интерфейса пользователя
 * ------------------------------------------------------------------------------
 * $Id: Layout_User.php 342 2013-03-13 14:24:00Z xxserg@gmail.com $
 */

/**
 * @package Project_Layout_User
 */

require_once PROJECT_ROOT.LAYOUT_FOLDER.'Layout.php';

class Project_Layout_User extends Project_Layout
{
    public $iface = 'user';

    public $iface_url = 'office';

    /* Меню пользователя */
    public $menu = array(    );

    /* Массив разрешенных таблиц */
    public $op_arr = array('pages', 'users', 'villa', 'countries', 'comments', 'query', 'booking');

    /* Таблица по умолчанию */
    public $op_def = 'users';

    //public $act_def = array('pages' => 'show');

    public $project_title = '';

    public $keywords;

    public $description;


    function getPageData($tpl = null)
    {
        //echo "Project_Layout_User";
        require_once COMMON_LIB.'DVS/Dynamic.php';
        //$page_arr['PAGE_TITLE']     = $this->project_title;
        $page_arr['KEYWORDS']       = $this->keywords;
        $page_arr['DESCRIPTION']    = $this->description;
        $page_arr['PAGE_TITLE']       = $this->page_title.' '.$this->project_title;
        $page_arr['LANG'] = $this->language;
        $page_arr['CHARSET'] = CHARSET;
        //$page_arr['CHARSET'] = 'windows-1251';
        $page_arr['COPYRIGHT-YEAR'] = date('Y');

        //$page_arr['LOGIN_FORM'] = $this->page_title.' '.$_SESSION['_authsession']['username'];
        $this->loginInfo($tpl);
        
        //$tpl->touchBlock('USER');
        
        if (SERVER_TYPE == 'remote') {
            $page_arr['GOOGLE_ANAL_CODE'] = file_get_contents(PROJECT_ROOT.'tmpl/rambler.tpl');
            $page_arr['LI_CODE'] = file_get_contents(PROJECT_ROOT.'tmpl/liveinternet.tpl');
            //file_get_contents(PROJECT_ROOT.'tmpl/ga.tpl').file_get_contents(PROJECT_ROOT.'tmpl/rambler.tpl');
            $page_arr['RECLAMA'] = $this->getReclama();
        }
        $this->getTopMenu($tpl);
        $this->getBottomMenu($tpl);
        $tpl->setVariable($page_arr);
        return;
    }

    function getReclama()
    {
        if (!defined('_SAPE_USER')){
           define('_SAPE_USER', '38d37aac66cf19ee43c60a5da7a17934');
        }
        include(PROJECT_ROOT.'WWW/'._SAPE_USER.'/sape.php');
        $o['host'] = 'rurenter.ru';
        $sape = new SAPE_client($o);
        unset($o);
        return '<div>'.$sape->return_links().'</div>';
    }
}
?>
