<?php
require_once PROJECT_ROOT.'DataObjects/Users.php';

class DBO_Users_Admin extends DBO_Users
{
    public $role = 'aa';

    public $search_arr = array('fields_search' => array('email', 'name', 'users.id'));

    public $listLabels   = array(
            'name'     =>  'class="td-name"',
            'id' => '',
            'email'    =>  '',
            //'lastname'    =>  '',
            //'address' =>  '',
            'home_phone'    =>  '',
            //'work_phone'    =>  '',
            //'mobile_phone' => '',
            'reg_date' =>  'class="td-date"',
            'status_id' =>  '',
            'role_id'     =>  ''
    );

    function preGenerateList()
    {
        $villa_obj = DB_DataObject::factory('villa');
        $this->joinAdd($villa_obj, 'LEFT');
        $this->groupBy('users.id');
        $this->selectAs();
        $this->selectAdd('COUNT(villa.id) as villa_cnt');
        //$this->selectAs(array('role_name'), '%s', 'user_roles');
    }

        //Вывод формы
    function getForm()
    {
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '');
        //$form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        $this->userForm($form);

        $form->addElement('select', 'role_id', $this->fb_fieldLabels['role_id'].': ', $this->user_roles);
        $form->addElement('select', 'status_id', $this->fb_fieldLabels['status_id'].': ', $this->status_arr);

        $form->addElement('submit', '__submit__', DVS_SAVE);
                $form->addElement('static', null, '</div></div>');

        return $form;
    }

    //Форма поиска для страницы администрирования

    function getSearchForm()
    {
        require_once('HTML/QuickForm.php');
        $form =& new HTML_QuickForm('search_form', 'get', $this->qs, '', 'class="search"');
        $form->addElement('hidden', 'op', $this->__table);
        $text = HTML_QuickForm::createElement('text', 'st', 'Искать: ', array('size' => '30'));
        $submit = HTML_QuickForm::createElement('submit', 'search', '>>');
        $role = HTML_QuickForm::createElement('select', 'role_id', '', array('' => '') + $this->user_roles);
        $status = HTML_QuickForm::createElement('select', 'status_id', '', array('' => '') + $this->status_arr);
        $form->addGroup(array($text, $role, $status,  $submit), '', 'Поиск:', array(' роль:', ' статус:', '&nbsp;'));
        
        return $form->toHtml().'<br><br>';
    }

    function setSearch()
    {
        $st = DVS::getVar('st');
        $role_id = DVS::getVar('role_id');
        $status_id = DVS::getVar('status_id');
        unset($this->role_id);
        unset($this->status_id);
        if ($st) {
            foreach ($this->search_arr['fields_search'] as $key=>$val) {
                $where .= $val." LIKE '".$_GET['st']."%' OR ";
            }
            $this->whereAdd('('.substr($where, 0, -4).')');
        }
        if ($role_id) {
            $this->whereAdd("role_id='".$role_id."'");
        }
        if ($status_id) {
            $this->whereAdd("users.status_id='".$status_id."'");
        }
    }

    function preGenerateCard()
    {
        print_r($this->id);
        $loginza_obj = DB_DataObject::factory('loginza');
        if ($loginza_obj->get('user_id', $this->id)) {
            $this->card_fields['provider'] = $loginza_obj->provider;
            $this->card_fields['identity'] = '<a href='.$loginza_obj->identity.' target=_blank>Профиль</a>';
            $this->card_fields += $this->toArray();
            //print_r($loginza_obj->toArray());
        }
        $bankinfo_obj = DB_DataObject::factory('bankinfo');
        $bankinfo_obj->get('user_id', $this->id);

        if (!$this->card_fields) {
            $this->card_fields = $this->toArray();
        }
        $this->card_fields +=   array('bank' => '<a href="?op=bankinfo&user_id='.$this->id.'">Банк</a>');
    }
}