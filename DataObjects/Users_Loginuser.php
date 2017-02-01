<?php
require_once PROJECT_ROOT.'DataObjects/Users.php';

class DBO_Users_Loginuser extends DBO_Users
{
    public $role = 'ou';


    function __construct()
    {
        $this->id = $_SESSION['_authsession']['data']['id'];
    }

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

}