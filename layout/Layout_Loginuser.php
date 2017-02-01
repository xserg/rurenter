<?php
/*////////////////////////////////////////////////////////////////////////////

------------------------------------------------------------------------------
Интерфейс renter
------------------------------------------------------------------------------
$Id: Layout_Loginuser.php 217 2012-04-04 08:03:25Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

require_once PROJECT_ROOT.LAYOUT_FOLDER.'Layout.php';

class Project_Layout_Loginuser extends Project_Layout
{
    public $iface = 'loginuser';

    public $iface_url = 'office';

    public $login_title = 'Арендатор';

    /* Меню пользователя */
    public $menu = array(
            array('?op=users&act=edit',             'Профиль', 'n-reg'),
            array('/office/',                       'Видео', 'n-vid'),
            array('/office/?op=v_video&act=new',    'Загрузить', 'n-upl'),
            array('/office/?op=messages&act=inbox',           'Сообщения', 'n-tag'),
            array('/office/?op=friends',            'Друзья', 'n-tag'),
            //array('/tags/',                         'Таги', 'n-tag'),
            array('/doc/help',                      'Помощь', 'n-hel')
    );

    /* Массив разрешенных таблиц */
    public $op_arr = array('villa',
                        'users',
                        'prices',
                        'booking',
                        'query',
                        'payments_in',
                        'transactions'
    );

    /* Таблица по умолчанию */
    public $op_def = 'booking';

    public $perms_fieldname = 'user_id';

    function getPageData($tpl = null)
    {
        $page_arr['PAGE_TITLE']       = $this->page_title.' '.$this->project_title;
        $page_arr['COPYRIGHT-YEAR'] = date('Y');
        $page_arr['LANG'] = $this->language;
        $page_arr['CHARSET'] = CHARSET;
        //$page_arr['LOGIN_FORM'] = $this->page_title.' '.$_SESSION['_authsession']['username'];
        $this->loginInfo($tpl);
        $this->getTopMenu($tpl);
        //$this->getMenuAdmin($tpl);
        //$this->setSearch($tpl);
        $tpl->setVariable($page_arr);
        return;
    }

}
?>
