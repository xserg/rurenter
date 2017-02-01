<?php
/**
 *
 *
 * -----------------------------------------------------------
 * $Id: Layout_Client.php 518 2014-08-06 14:25:54Z xxserg@gmail.com $
 */

require_once PROJECT_ROOT.LAYOUT_FOLDER.'Layout.php';

class Project_Layout_Client extends Project_Layout
{
    public $iface = 'client';

    public $iface_url = 'office';

    public $login_title = '��������';

    public $perms_fieldname = 'user_id';

    /* ���� �������������� */
    var $menu = array(

        //array('',                                   '����������'),
        array('?op=campaigns',                                  '��������� ��������'),
        array('?op=advertisers&act=show',                       '���������'),
        array('?op=transactions',                               '������ ����'),
        array('?op=pay_services&act=select',                    '��������� ����'),


/*
        array('?op=advertiser_status',  '������ ��������������'),
        array('?op=campaign_status',  '������ ��������'),
        array('?op=housing_type',       '���� �����'),
        array('?op=face_type',          '���� ��������������'),
*/

    );

    /* ������ ����������� ������ */
    var $op_arr = array('villa',
                        'users',
                        'prices',
                        'booking',
                        'query',
                        'transactions',
                        'payments_out',
                        'bankinfo',
                        'smsinfo',
    );

    /* ������� �� ��������� */
    var $op_def = 'booking';

    var $page_title = '�������������';

    function getPageData($tpl = null)
    {
        $page_arr['PAGE_TITLE']       = $this->page_title.' '.$this->project_title;
        //$page_arr['LOGIN_FORM'] = $this->page_title.' '.$_SESSION['_authsession']['username'];
        $page_arr['COPYRIGHT-YEAR'] = date('Y');
        $page_arr['LANG'] = $this->language;
        $page_arr['CHARSET'] = CHARSET;
        $this->loginInfo($tpl);
        $this->getTopMenu($tpl);
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
