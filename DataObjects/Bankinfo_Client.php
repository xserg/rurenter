<?php
require_once PROJECT_ROOT.'DataObjects/Users.php';

class DBO_Bankinfo_Client extends DBO_Bankinfo
{
    public $role = 'oc';


    function __construct()
    {
        $this->user_id = $_SESSION['_authsession']['data']['id'];
    }

    function preGenerateForm(&$fb)
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->user_id);
        $this->fb_preDefElements['user_id'] = HTML_QuickForm::createElement('hidden', 'user_id', $this->fb_fieldLabels['user_id'], $this->user_id);
        $this->center_title = $users_obj->name.' '.$users_obj->lastname;

    }

/*
    function getForm()
    {
            $this->qs = '/office/?op=static&act=update_row';

        if ($this->id) {
            $this->get($this->id);
        }
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '');
        //$form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        $this->userForm($form);


        //$form->addElement('submit', '__submit__', DVS_SAVE);
        return $form;
    }
*/
}