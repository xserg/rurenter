<?php
/**
 * Table Definition for users
 */
require_once 'DB/DataObject.php';

class DBO_Users extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'users';                           // table name
    public $_database = 'rurenter';                           // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null unsigned
    public $email;                           // varchar(255)   not_null
    public $password;                        // varchar(128)   not_null
    public $reg_date;                        // datetime()   not_null
    public $last_date;                       // datetime()  
    public $last_ip;                         // varchar(64)  
    public $role_id;                         // char(2)   not_null
    public $name;                            // varchar(255)  
    public $lastname;                        // varchar(255)  
    public $passport_series;                 // int(4)   not_null
    public $passport_no;                     // int(4)   not_null
    public $passport_date;                   // date()   not_null
    public $passport_given;                  // varchar(255)   not_null
    public $age;                             // varchar(255)  
    public $country;                         // varchar(255)  
    public $city;                            // varchar(255)  
    public $street;                          // varchar(255)  
    public $house_num;                       // varchar(255)  
    public $post_code;                       // int(4)  
    public $address;                         // varchar(255)  
    public $home_phone;                      // varchar(32)  
    public $work_phone;                      // varchar(32)  
    public $mobile_phone;                    // varchar(32)  
    public $company;                         // varchar(255)  
    public $status_id;                       // int(4)  
    public $reg_code;                        // varchar(255)  
    public $balance;                         // float()  
    public $note;                            // tinytext()  
    public $agree;                           // int(4)   not_null
    public $nick;                            // varchar(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Users',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'email' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'password' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'reg_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME + DB_DATAOBJECT_NOTNULL,
             'last_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
             'last_ip' =>  DB_DATAOBJECT_STR,
             'role_id' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'name' =>  DB_DATAOBJECT_STR,
             'lastname' =>  DB_DATAOBJECT_STR,
             'passport_series' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'passport_no' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'passport_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_NOTNULL,
             'passport_given' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'age' =>  DB_DATAOBJECT_STR,
             'country' =>  DB_DATAOBJECT_STR,
             'city' =>  DB_DATAOBJECT_STR,
             'street' =>  DB_DATAOBJECT_STR,
             'house_num' =>  DB_DATAOBJECT_STR,
             'post_code' =>  DB_DATAOBJECT_INT,
             'address' =>  DB_DATAOBJECT_STR,
             'home_phone' =>  DB_DATAOBJECT_STR,
             'work_phone' =>  DB_DATAOBJECT_STR,
             'mobile_phone' =>  DB_DATAOBJECT_STR,
             'company' =>  DB_DATAOBJECT_STR,
             'status_id' =>  DB_DATAOBJECT_INT,
             'reg_code' =>  DB_DATAOBJECT_STR,
             'balance' =>  DB_DATAOBJECT_INT,
             'note' =>  DB_DATAOBJECT_STR,
             'agree' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'nick' =>  DB_DATAOBJECT_STR,
         );
    }

    function keys()
    {
         return array('id');
    }

    function sequenceKey() // keyname, use native, native name
    {
         return array('id', true, false);
    }

    function defaults() // column default values 
    {
         return array(
             '' => null,
         );
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    public $use_project_form_tmpl = true;

    public $form_template_name = 'form_query.tpl';


    /**
     * Заголовок для таблицы
     */
    public $center_title;

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form;

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
            'name'     =>  'class="td-name"',
            'email'    =>  '',
            'id' => '',
            //'lastname'    =>  '',
            //'address' =>  '',
            'home_phone'    =>  '',
            //'work_phone'    =>  '',
            //'mobile_phone' => '',
            'reg_date' =>  'class="td-date"',
            'status_id' =>  '',
            'role_id'     =>  ''
    );

    /**
     * Название столбцов и элементов формы
     */
    public $fb_fieldLabels  = array();

    public $rules = array();

    /**
     * Сортировка таблицы по умолчанию
     */
    public $default_sort = 'users.id DESC';

    public $show_edit = true;

    public $act;

    public $qs_arr = array('role_id', 'status_id');

    public $fb_linkDisplayFields = array('name');


    public $sortLabels   = array(
                'name'              =>  'name',
                'reg_date'       =>  'reg_date',
                'email'    =>  'email',
    );
/*
    public $fieldsToRender   = array(
                     'name',
                     'email',
                     //'lastname'    =>  '',
                   //'address' =>  '',
                        //'home_phone'    =>  '',
                     //'work_phone'    =>  '',
                    //'mobile_phone' => '',
                   
                     'last_ip',
                     'last_date',
                     'reg_date',
                     'note',
                     //'role_id'
    );
*/
/*
    public $user_roles = array(
        'owner',
        'renter',
        'redactor',
        'admin'
    );
*/

    public $user_roles = array(
        'aa' => 'Администратор',
        'ar' => 'Редактор',
        'oc' => 'Владелец',
        'ou' => 'Турист'
    );


    public $status_arr = array(
        1 => 'Новый',
        2 => 'Активный',
        3 => 'Заблокированный'
    );

    ////////////////////////////////////////////////////////////////////////// DEBUG
    public $perms_arr = array(
        'new' => array('iu' => 1, 'mu' => 1, 'oc' => 1),
        'list' => array('iu' => 0, 'oc' => 0),
        'edit' => array('iu' => 0, 'oc' => 1, 'ou' => 1),
        'delete' => array('iu' => 0, 'oc' => 0),
        'card' => array('iu' => 0, 'oc' => 0),
    );
    ////////////////////////////////////////////////////////////////////////// DEBUG

    /**
     * Строка данных таблицы
     */
    function tableRow()
    {
            return array(
                 'name'     =>  '<a href="?op=users&act=card&id='.$this->id.'">'.$this->id.' '.$this->name.'</a>',
                 //'email'     =>  '<a href="?op=users&act=card&id='.$this->id.'">'.$this->email.'</a>',
                 'email'    =>  $this->email,
                    'id' =>  '<a href="?op=villa&user_id='.$this->id.'">Виллы('.$this->villa_cnt.')</a>',
                 'home_phone'    =>  $this->home_phone,
                 'reg_date' =>  $this->reg_date,
                 'status_id' =>  $this->status_arr[$this->status_id],
                 'role_id'     =>  $this->user_roles[$this->role_id],
            );
    }

    function preGenerateList()
    {
        /*
            $role_id = DVS::getVar('role_id', 'int');
            
            $this->whereAdd("users.role_id < 3");
            $roles_obj = DB_DataObject::factory('user_roles');
            if ($role_id) {
                $roles_obj->get($role_id);
                $this->center_title = $roles_obj->role_name;//.'ы';
            }
            $this->joinAdd($roles_obj);
            //$this->selectAdd();
            $this->selectAs();
            $this->selectAs(array('role_name'), '%s', 'user_roles');
            */
    }

    /**
     * Генерирование случайного пароля
     */
    function getPasswordRandom()
    {
        return substr(rand(1234567, 100000000), 0, 7);
    }

    /**
     * TODO: Проверка е-мыл при редактировании
     */
    function userForm($form)
    {

        $this->smsinfo_obj = DB_DataObject::factory('smsinfo');
        $this->smsinfo_obj->user_id = $_SESSION['_authsession']['data']['id'];
        $this->smsinfo_obj->find('true');

        if ($this->smsinfo_obj->phone) {
            $this->mobile_phone = $this->smsinfo_obj->phone;
            $form->freeze('mobile_phone');
        }

        $this->center_title = $this->words['form_title'];

        $form->addElement('header', null, $this->words['form_title2']);

        if ($_GET['msg']) {
             $form->addElement('static', null, '<div class="clear"><span class="required">'.DVS_REQUIRED.' "'.$this->fb_fieldLabels['nick'].'"!'.'</span></div><br><br>');
        }

        $form->addElement('text', 'email', $this->fb_fieldLabels['email'].': ');
        //$form->addElement('text', 'password', $this->fb_fieldLabels['password'].': ');
        $form->addElement('text', 'name', $this->fb_fieldLabels['name'].': ');
        $form->addElement('text', 'lastname', $this->fb_fieldLabels['lastname'].': ');
        $form->addElement('text', 'company', $this->fb_fieldLabels['company'].': ');


        $form->addElement('text', 'address', $this->fb_fieldLabels['address'].': ');

        $form->addElement('text', 'home_phone', $this->fb_fieldLabels['home_phone'].': ');
        //$form->addElement('text', 'work_phone', $this->fb_fieldLabels['work_phone'].': ');
        $form->addElement('text', 'mobile_phone', $this->fb_fieldLabels['mobile_phone'].': ');

        if ($this->role == 'oc') {

        if ($this->smsinfo_obj->status_id == 2) {
            //$sms_link = ' <a href="/'.$this->lang.'/office/?op=smsinfo&act=edit">'.$this->words['smsinfo'].'</a><br class="clear"><br>';
            $form->freeze('mobile_phone');
            $form->addElement('advcheckbox', 'send_new', '', $this->words['send_new'],  array('class' => 'radio'));
            $form->setDefaults(array('send_new' => $this->smsinfo_obj->send_new));
        } else {
            $sms_link = ' <a href="/'.$this->lang.'/office/?op=smsinfo&act=activate">'.$this->words['smsinfo_activate'].'</a><br class="clear"><br>';
            $form->addElement('static', null, null, $sms_link);
        }
        
        }

        //$form->addElement('text', 'nick', $this->fb_fieldLabels['nick'].': ');

        if (!$this->agree) {
        $form->addElement('checkbox', 'agree', '', $this->words['agree'],  'class=radio');
        }

        $form->addElement('static', null, null, ' <a href="/'.$this->lang.'/office/?op=users&act=agree" target="_blank">'.$this->words['agreement'].'</a><br><br class="clear">');

        $form->addElement('static', null, '</div><div class="clear"></div></div>');

        $form->addElement('header', null, $this->fb_fieldLabels['note'].': ');
        $form->addElement('textarea', 'note', $this->fb_fieldLabels['note'].': ');

        $form->addElement('static', null, '<a href="?op=bankinfo">'.$this->words['bank_details'].'</a><br><br>');


        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addElement('static', null, '</div></div>');

        $form->freeze('email');
        $form->addRule('name', DVS_REQUIRED.' "'.$this->fb_fieldLabels['name'].'"!', 'required', null, 'client');
        $form->addRule('lastname', DVS_REQUIRED.' "'.$this->fb_fieldLabels['lastname'].'"!', 'required', null, 'client');

        //$form->addRule('address', DVS_REQUIRED.' "'.$this->fb_fieldLabels['address'].'"!', 'required', null, 'client');
        //$form->addRule('home_phone', DVS_REQUIRED.' "'.$this->fb_fieldLabels['home_phone'].'"!', 'required', null, 'client');
        //$form->addRule('home_phone', $this->rules['phone'][0], 'regex', '/^[0-9-+\(\) ]{10,17}$/');
        $form->addRule('home_phone', $this->rules['phone'][0], 'regex', '/^[0-9]{10,13}$/');
        $form->addRule('mobile_phone', $this->rules['phone'][0], 'regex', '/^[0-9]{10,13}$/');

        $form->addRule('email', DVS_REQUIRED.' "'.$this->fb_fieldLabels['email'].'"!', 'required', null, 'client');
        $form->addRule('agree', $this->words['disagree'], 'required', null, 'client');

        if ($_GET['msg']) {
            $form->addRule('nick', DVS_REQUIRED.' "'.$this->fb_fieldLabels['nick'].'"!', 'required', null, 'client');
        }
        
        /*
        $form->registerRule('checkNick', 'callback', 'checkNick', 'DBO_Users');
        $form->addRule('nick', $this->rules['nick'][0], 'checkNick');
        */

        //$form->addRule('email', $this->rules['email'][0].' '.$this->fb_fieldLabels['email'].'"!', 'email', null, 'client');
        $pattern = "/^[+]?[0-9] ?[(]?[0-9]{3,3}[)] *[0-9]{3,3}[-]?[0-9]{2,2}[-]?[0-9]{2,2}$/";
        //$pattern = '/[0-9 \-]+/';
        //$form->addRule('phone', $this->rules['phone'][0], 'regex', $pattern, 'client');

        $form->applyFilter('email', 'strtolower');
        if ($this->act == 'new') {
            $form->registerRule('checkEmail', 'callback', 'checkEmail', 'DBO_Users');
            $form->addRule('email', $this->rules['email'][1], 'checkEmail');
        }

        $form->registerRule('checkMobile', 'callback', 'checkMobile', 'DBO_Users');
        $form->addRule('mobile_phone', $this->rules['mobile_phone'][0], 'checkMobile');

    }

    function checkEmail($email)
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->email = strtolower($email);
        if ($users_obj->count()) {
            return false;
        }
        return true;
    }


    function checkMobile($phone)
    {
        $users_obj = DB_DataObject::factory('smsinfo');
        $users_obj->phone = $phone;
        $users_obj->find(true);
        if ($users_obj->user_id) {
            if ($users_obj->user_id == $_SESSION['_authsession']['data']['id']) {
                return true;
            }
            return false;
        }
        return true;
    }

    function checkNick($nick)
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->nick = $nick;
        if ($users_obj->count()) {
            return false;
        }
        return true;
    }

    //Вывод формы
    function getForm()
    {
        //$loginza = file_get_contents(PROJECT_ROOT.'tmpl/loginza_iframe.tpl');
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '', 'class="reg"');
        //$form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        if ($this->act == 'new') {
            $this->regForm($form);
            $form->addElement('submit', '__submit__', DVS_SAVE);
            //$form->addElement('static',  null, 'Или зарегистрироваться через');
            //$form->addElement('static', 'loginza', $loginza);
        } else {
            $this->userForm($form);
            //$form->addElement('submit', '__submit__', DVS_SAVE);
        }

        
//                $form->addElement('static', null, '</div></div>');

        return $form;
    }

    function preProcessForm(&$vals, &$fb)
    {
        //$this->updateMobile($vals);

        if ($this->act == 'new') {

            $this->userEditableFields = array(
                    'email',
                    'password',
                    'name',
                    'phone',
                    'reg_date',
                    'reg_code',
                    'role_id',
                    'status_id'.
                    //'last_date',
                    'last_ip',
                    'note'
            );
            $fb->dateToDatabaseCallback = null;
            $vals['email']          = strtolower($vals['email']);
            $vals['reg_date']       = date(DB_DATE_FORMAT);
            $vals['reg_code']      = md5(uniqid(""));
            $vals['status_id']     = 1;
            
            $vals['role_id']     = $vals['role_id'] == 'oc' ? 'oc' : 'ou';
            //$vals['password']       = $this->getPasswordRandom();
            $vals['password']       = $vals['password1'];
            require_once COMMON_LIB.'DVS/Auth.php';
            $vals['last_ip']   = DVS_Auth::getIP();
            $this->qs = '/?op=static&act=reg_email';
            $this->msg = DVS_REG_EMAIL;

            $this->smsinfo_obj->phone = $vals['mobile_phone'];
            $this->smsinfo_obj->code = mt_rand(1000, 9999);
            $vals['regdate'] = date("Y-m-d H:i:s");
        } else {
            $this->updateMobile($vals);

            /*
            $this->userEditableFields = array(
                    'email',
                    //'password',
                    'name',
                    'phone',
                    'role_id',
                    'note'
            );
            */
            //$this->qs = '/office/';
        }
    }

    function postProcessForm(&$vals, &$fb)
    {

        $_SESSION['_authsession']['data']['nick'] = $vals['nick'];
        if ($_GET['act'] == 'new') {
            $this->userLetter();
        }
    }

    function updateMobile($vals)
    {
        if (!$this->smsinfo_obj->phone) {
            $this->smsinfo_obj->user_id = $_SESSION['_authsession']['data']['id'];
            $this->smsinfo_obj->phone = $vals['mobile_phone'];
            $this->smsinfo_obj->code = mt_rand(1000, 9999);
            $this->smsinfo_obj->regdate = date("Y-m-d H:i:s");
            $this->smsinfo_obj->insert();
            $this->smsinfo_obj->sendSms($this->smsinfo_obj->phone, 'ruRenter.ru registration code: '.$this->smsinfo_obj->code);
            //self::sendSms('79036193987', 'ruRenter.ru registration code: '.$this->code);
        } else {
            if ($this->smsinfo_obj->status_id == 0) {
                if ($this->smsinfo_obj->phone != $vals['mobile_phone']) {
                    $this->smsinfo_obj->phone = $vals['mobile_phone'];
                    $this->smsinfo_obj->code = mt_rand(1000, 9999);
                    $this->smsinfo_obj->update();
                    $this->smsinfo_obj->sendSms($this->smsinfo_obj->phone, 'ruRenter.ru registration code: '.$this->smsinfo_obj->code);
                }
            }
            if ($this->smsinfo_obj->status_id == 2) {
                $this->smsinfo_obj->send_new = $vals['send_new'];
                $this->smsinfo_obj->update();
            }

        }
    }

    function userLetter()
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $this->email;
        $data['cc'] = MAIL_ADMIN;
        $data['bcc'] = MAIL_ADMIN;
        $data['subject'] = 'ruRenter.ru registration';
        $activate_link = SERVER_URL."/activate/?code=".$this->reg_code;
        $data['body'] = DVS_Mail::letter(array('activate_link' => $activate_link) + $this->toArray(), 'reg_user.tpl');
        DVS_Mail::send($data);

        $data['to'] = MAIL_ADMIN;
        $data['subject'] = 'New ruRenter.ru registration';
        $data['body'] = DVS_Mail::letter($this->toArray(), 'reg_user.tpl');
        //DVS_Mail::send($data);

    }

    function preDelete()
    {
        //print_r($_SESSION);
        if ($this->id == $_SESSION['_authsession']['data']['id']) {
            $this->msg = 'ERROR_CANT_DELETE_BY_MYSELF';
            //echo $this->msg;
            //exit;
            return false;
        } else {
            return true;
        }
    }

    function regForm(&$form)
    {

        $this->center_title = $this->words['reg_title'];
        $form->addElement('header', null, $this->words['reg_help']);

        $form->addElement('text', 'email', $this->fb_fieldLabels['email'].': ');
        $form->addElement('password', 'password1', $this->fb_fieldLabels['password'].': ');
        $form->addElement('password', 'password2', $this->fb_fieldLabels['confirm_password'].': ');
        $form->addElement('radio', 'role_id', '<span style="color: #FF0000;">{qf_error}</span><b>'.$this->fb_fieldLabels['role_id_title'].':</b> ', $this->fb_fieldLabels['role_id_label1'], 'oc', 'class=radio');
        $form->addElement('radio', 'role_id', '', $this->fb_fieldLabels['role_id_label2'], 'ou', 'class=radio');

        //$form->addElement('checkbox', 'agree', 'Я согласен с условиями предоставления сервиса ', ' <a href="/pages/terms" target="_blank"><b>(прочитать)</b></a>',  'class=radio');

        //$form->addElement('static', null, null, '<br class="clear">');

/*///////////////////////////////////////////////////////////////
        if (!$_SESSION) {
            @session_start();
        }
        $captcha_question = & $form->addElement('CAPTCHA_Image', 'captcha_question', '', $options);
        $form->addElement('static', null, null, 'Если вы не уверены, кликните на картинку для смены');
        $form->addElement('text', 'captcha', 'Введите текст с картинки');
        $form->addRule('captcha', 'Введите текст с картинки!', 'required', null, 'client');
        $form->addRule('captcha', '<br>Текст не соответствует картинке!', 'CAPTCHA', $captcha_question);
*/////////////////////////////////////////

        DVS_ShowForm::formCaptcha($form, $this->lang);
        $form->addRule('email', DVS_REQUIRED.' "'.$this->fb_fieldLabels['email'].'"!', 'required', null, 'client');
        $form->addRule('email', $this->rules['email'][0].' '.$this->fb_fieldLabels['email'].'"!', 'email', null, 'client');

        $form->addRule('password1', DVS_REQUIRED.' "'.$this->fb_fieldLabels['password'].'"!', 'required', null, 'client');
        $form->addRule('password2', DVS_REQUIRED.' "'.$this->fb_fieldLabels['confirm_password'].'"!', 'required', null, 'client');
        $form->addRule('password1', $this->rules['password'][0], 'minlength', 5, 'client');
        $form->addRule(array('password1', 'password2'), $this->rules['password'][1], 'compare', null, 'client');

        $form->addRule('role_id', DVS_REQUIRED.' "'.$this->fb_fieldLabels['role_id'].'"!', 'required', null, 'client');

        //if ($this->act == 'new') {
            //echo 'checkEmail';
            //DB_DataObject::DebugLevel(1);
            $form->registerRule('checkEmail', 'callback', 'checkEmail', 'DBO_Users');
            $form->addRule('email', $this->rules['email'][1], 'checkEmail');
        //}

        //$form->addRule('agree', 'Вы не согласны с условиями сервиса!', 'required', null, 'client');
    }

    public function checkAuth()
    {
        //if ((!$this->address || !$this->name || !$this->home_phone) && $_GET['act'] != 'agree') {
        if (!$this->name && $_GET['act'] != 'agree') {

            $this->qs = '/office/?op=users&act=edit';
            $_SESSION['message'] = ERROR_CONTACTS;
            $_GET['op'] = 'users';
            $_GET['act'] = 'edit';
            //header('Location: '.$this->qs);
            //exit;
        }
        return true;
    }

    function count()
    {
        return parent::count(distinct);
    }
}
