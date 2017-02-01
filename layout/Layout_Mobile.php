<?php
/**
 * @package villarenters.ru
 * ------------------------------------------------------------------------------
 * Настройки интерфейса пользователя
 * ------------------------------------------------------------------------------
 * $Id: Layout_User.php 297 2013-10-09 12:54:04Z xxserg@gmail.com $
 */

/**
 * @package Project_Layout_User
 */

require_once PROJECT_ROOT.LAYOUT_FOLDER.'Layout.php';

class Project_Layout_Mobile extends Project_Layout
{
    public $iface = 'user';

    public $iface_url = 'office';

    /* Меню пользователя */
    public $menu = array(    );

    /* Массив разрешенных таблиц */
    public $op_arr = array('advertisers', 'booking', 'pages', 'users', 'villa', 'countries', 'comments', 'query', 'articles');

    /* Таблица по умолчанию */
    public $op_def = 'users';

    //public $act_def = array('pages' => 'show');

    public $project_title = '';

    public $keywords;

    public $description;

    public $template_name = 'villa_m.tpl';

    function getPageData($tpl = null)
    {
        require_once COMMON_LIB.'DVS/Dynamic.php';
        //$page_arr['PAGE_TITLE']     = $this->project_title;
        $page_arr['KEYWORDS']       = $this->keywords;
        $page_arr['DESCRIPTION']    = $this->description;
        $page_arr['PAGE_TITLE']       = $this->page_title.' '.$this->project_title;
        $page_arr['LANG'] = 'ru';
        $page_arr['FULL_URL'] = SERVER_URL.'?versm=full';

        //$page_arr['LOGIN_FORM'] = $this->page_title.' '.$_SESSION['_authsession']['username'];
        //$this->loginInfo($tpl);
        //$this->getMenu($tpl);
        

        if (SERVER_TYPE == 'remote') {
            $page_arr['GOOGLE_ANAL_CODE'] = file_get_contents(PROJECT_ROOT.'tmpl/ga.tpl').file_get_contents(PROJECT_ROOT.'tmpl/rambler.tpl').file_get_contents(PROJECT_ROOT.'tmpl/ymetrika.tpl');
            
            
            $page_arr['LI_CODE'] = file_get_contents(PROJECT_ROOT.'tmpl/liveinternet.tpl');
       

            //if (preg_match("/villarenters/", $_SERVER['HTTP_HOST'])) {
                $page_arr['RECLAMA'] = $this->getReclama2();
            //}
        }
                //$page_arr['RECLAMA'] = $this->getReclama2();

        //$pages_obj = DVS_Dynamic::createDbObj('pages');
        //$pages_obj->getNewsBlock($tpl, 6);

        $this->getTopMenu($tpl);

        $tpl->setVariable($page_arr);
        return;
    }

    function getReclama2()
    {
        if (!defined('_SAPE_USER')){
           define('_SAPE_USER', '38d37aac66cf19ee43c60a5da7a17934');
        }
            //require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
        include(PROJECT_ROOT."WWW/38d37aac66cf19ee43c60a5da7a17934/sape.php");
        
        //$sape_context = new SAPE_context();
        //return '<div>'.$sape_context->replace_in_text_segment(' ').'</div>';
        //$o[ 'force_show_code' ] = true; 
        $o['host'] = 'villarenters.ru';
        $sape = new SAPE_client($o);
        unset($o);
        return '<div>'.$sape->return_links().'</div>';
    }
}
?>
