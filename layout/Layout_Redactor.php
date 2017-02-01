<?php
/**
 *
 *
 * -----------------------------------------------------------
 * $Id: Layout_Redactor.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once PROJECT_ROOT.LAYOUT_FOLDER.'Layout.php';

class Project_Layout_Redactor extends Project_Layout
{
    var $iface_url = 'admin';

    var $login_title = 'Редактор';

    /* Меню Администратора */
    var $menu = array(
        array('',                                   'Рекламодатели'),
        array('?op=advertisers&status_id=1',        'Новые'),
        array('?op=advertisers',                    'Все'),

        array('',                                   'Настройки'),
        array('?op=pages',                          'Страницы'),

        //array('?op=adm_units',          'Адм еденицы'),

        array('',                                   'Статистика'),
        array('?op=campaigns',                      'Кампании'),
        array('?op=transactions',                   'Оплата'),


/*
        array('?op=advertiser_status',  'Статус рекламодателей'),
        array('?op=campaign_status',  'Статус кампаний'),
        array('?op=housing_type',       'Типы жилья'),
        array('?op=face_type',          'Типы рекламодателей'),
*/

    );

    /* Массив разрешенных таблиц */
    var $op_arr = array('advertisers', 'campaigns', 'campaigns_statistics', 'content', 'pages', 'transactions');

    /* Таблица по умолчанию */
    var $op_def = 'advertisers';

    var $page_title = 'Администратор';

    function getPageData($tpl = null)
    {
        $page_arr['PAGE_TITLE']       = $this->page_title.' '.$this->project_title;
        //$page_arr['LOGIN_FORM'] = $this->page_title.' '.$_SESSION['_authsession']['username'];
        $this->loginInfo($tpl);
        $this->getMenu($tpl);
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
