<?php
/*////////////////////////////////////////////////////////////////////////////

------------------------------------------------------------------------------
��������� renter
------------------------------------------------------------------------------
$Id: Layout_Loginuser.php 217 2012-04-04 08:03:25Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

require_once PROJECT_ROOT.LAYOUT_FOLDER.'Layout.php';

class Project_Layout_Loginuser extends Project_Layout
{
    public $iface = 'loginuser';

    public $iface_url = 'office';

    public $login_title = '���������';

    /* ���� ������������ */
    public $menu = array(
            array('?op=users&act=edit',             '�������', 'n-reg'),
            array('/office/',                       '�����', 'n-vid'),
            array('/office/?op=v_video&act=new',    '���������', 'n-upl'),
            array('/office/?op=messages&act=inbox',           '���������', 'n-tag'),
            array('/office/?op=friends',            '������', 'n-tag'),
            //array('/tags/',                         '����', 'n-tag'),
            array('/doc/help',                      '������', 'n-hel')
    );

    /* ������ ����������� ������ */
    public $op_arr = array('villa',
                        'users',
                        'prices',
                        'booking',
                        'query',
                        'payments_in',
                        'transactions'
    );

    /* ������� �� ��������� */
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
