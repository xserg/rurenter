<?php
/**
 * @package villarenters.ru
 *
 * ------------------------
 * $Id: Layout_Admin.php 167 2012-02-14 18:42:11Z xxserg $
 */

require_once PROJECT_ROOT.LAYOUT_FOLDER.'Layout.php';

class Project_Layout_Admin extends Project_Layout
{
    public $iface = 'admin';

    public $iface_url = 'admin';

    public $login_title = 'Администратор';

    /* Меню Администратора */
    public $menu = array();



    /* Массив разрешенных таблиц */
    public $op_arr = array('pages');

    /* Таблица по умолчанию */
    public $op_def = 'villa';

    public $page_title = 'Администратор';

    //public $template_name = 'zoopla.tpl';
    public $template_name = 'index.tpl';

    public $login_info_tpl = 'login_info1.tpl';

    function getPageData($tpl = null)
    {
        $page_arr['PAGE_TITLE']       = $this->page_title.' '.$this->project_title;
        //$page_arr['LOGIN_FORM'] = $this->page_title.' '.$_SESSION['_authsession']['username'];
        $page_arr['COPYRIGHT-YEAR'] = date('Y');
        $this->loginInfo($tpl);
        $this->getMenu($tpl);
        //$this->getTopMenu($tpl);
        //$this->getMenuAdmin($tpl);
        //$this->setSearch($tpl);
        $tpl->setVariable($page_arr);
        return;
    }

    function setSearch($tpl)
    {
        if ($_GET['find']) {
            $find = $_GET['find'];
        } else {
            $find = '';
        }
        $tpl->addBlockFile('NAVIGATION', 'SEARCH', 'search.tpl');
        //$tpl->addBlockfile('SEARCH_FORM', 'SEARCH_FORM', 'search.tpl');
        $tpl->setVariable(array(
            'ACTION' => '',
            'FIND' => $find,
            'SEARCH_IMG' => 'search.png',
            'search_op' => $_GET['op'],
        ));
        $tpl->parse('SEARCH');
    }


}
?>
