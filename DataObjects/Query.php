<?php
/**
 * Table Definition for query
 */
require_once 'DB/DataObject.php';

class DBO_Query extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'query';                           // table name
    public $_database = 'rurenter';                           // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)   not_null
    public $user_id;                         // int(4)   not_null
    public $user_id_to;                      // int(4)   not_null
    public $first_name;                      // varchar(255)   not_null
    public $last_name;                       // varchar(255)   not_null
    public $email;                           // varchar(255)  
    public $country_id;                      // int(4)   not_null
    public $phone;                           // varchar(255)   not_null
    public $start_date;                      // date()   not_null
    public $end_date;                        // date()   not_null
    public $quantity;                        // int(4)   not_null
    public $children;                        // int(4)   not_null
    public $body;                            // mediumtext()  
    public $post_date;                       // datetime()  
    public $ip;                              // varchar(255)   not_null
    public $booking_id;                      // int(4)   not_null
    public $status_id;                       // tinyint(1)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Query',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'user_id_to' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'first_name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'last_name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'email' =>  DB_DATAOBJECT_STR,
             'country_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'phone' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'start_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_NOTNULL,
             'end_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_NOTNULL,
             'quantity' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'children' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'body' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'post_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
             'ip' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'booking_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'status_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
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

    //public $fb_fieldsToRender = array('first_name');

    public $use_project_form_tmpl = true;

    public $form_template_name = 'form_query.tpl';

    public $default_sort = 'id desc';
    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
                    'id' => '',
                    // 'first_name'     =>  'class="td-name"',
                    // 'email'    =>  '',
                    // 'phone'    =>  '',
                    'body' => '',
                    'post_date'  => '',

    );

    // Название столбцов и элементов формы
    public $fb_fieldLabels  = array(
        'alias'     => 'Cсылка',
        'title'     => 'Заголовок',
        'body'      => 'Текст',
        'post_date' => 'Дата',
        'status_id' => 'Статус',
        'moderate' => '',
        'booking_id' => 'Бронь',
    );

    public $perms_arr = array(
        'new' => array('iu' => 1, 'oc' => 0, 'ou' => 1),
        'list' => array('oc' => 1, 'ou' => 1),
        'card' => array('oc' => 1, 'ou' => 1),
    );

    public $status_arr = array(
        0 => 'Новый',
        1 => 'Активный',
    );

/*
    public $card_fields= array(
                     'first_name',
                     'email',
                     //'phone'    =>  '',
                    //'post_date'  => '',
        );


    function __construct()
    {
        $this->id = $_SESSION['_authsession']['data']['id'];
    }
*/    
    function tableRow()
    {
        return array(
             'id' =>  '<a href="?op=query&act=show&id='.$this->id.'">'.$this->id.'</a>',
             'first_name' => '<a href="?op=query&act=show&id='.$this->id.'">'.$this->first_name.'</a>',
             'email'    =>  $this->email,
             'phone'    =>  $this->phone,
             'body' => $this->body,
             'post_date'  => DVS_Dynamic::RusDate($this->post_date),
             'status_id' => $this->status_arr[$this->status_id],
            'moderate' => $this->status_id ? $this->status_arr[$this->status_id] : '<a href="/office/?op=query&act=activate&id='.$this->id.'">Активировать</a>' ,
        );
    }

    function preGenerateList()
    {
        if ($_SESSION['_authsession']['data']['role_id'] != 'aa') {
            $this->whereAdd("status_id = 1");
            //$this->whereAdd("user_id > 0");
            if (!$this->villa_id) {
                $villa_obj = DB_DataObject::factory('villa');
                $str = $villa_obj->getVillaByUser($_SESSION['_authsession']['data']['id']);
                if ($str) {
                    //echo $str;
                    $this->whereAdd("villa_id IN(".$str.")");
                }
            }
            //print_r($_SESSION);
            //$this->whereAdd("user_id_to=".$_SESSION['_authsession']['data']['id'], "OR"); 
            $this->whereAdd("user_id_to=".$_SESSION['_authsession']['data']['id']); 

        } else {
              /*$this->listLabels += array(                    'status_id' => '',
                                                            'moderate' => '',
                                        );*/
        }

    }

    public function getForm()
    {



        $villa_id = DVS::getVar('villa_id');
        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($villa_id);
        if ($villa_obj->N) {
            $this->center_title .= ': '.($villa_obj->sale ? 'Продажа ' : '').'"'.$villa_obj->getLocalField('title', $this->lang).'" ('.$villa_id.')';
        }
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '', 'class="reg"');
        $form->addElement('hidden', 'villa_id', $villa_id);
        $form->addElement('header', null, $this->header1);
        $form->addElement('text', 'first_name', $this->fb_fieldLabels['first_name'].': ');
        $form->addElement('text', 'last_name', $this->fb_fieldLabels['last_name'].': ');
        $form->addElement('text', 'email', $this->fb_fieldLabels['email'].': ');
        $form->addElement('text', 'phone', $this->fb_fieldLabels['phone'].': ');
        $form->addElement('static', null, '</div><div class="clear"></div></div>');
        $form->addElement('header', null, $this->head_form);
        $form->addElement('textarea', 'body', '');
        if ($this->act == 'new') {
            DVS_ShowForm::formCaptcha($form, $this->lang);
        }
        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addElement('static', null, '</div></div>');
        $form->addRule('last_name', DVS_REQUIRED.' "'.$this->fb_fieldLabels['last_name'].'"!', 'required', null, 'client');
        $form->addRule('first_name', DVS_REQUIRED.' "'.$this->fb_fieldLabels['first_name'].'"!', 'required', null, 'client');
        $form->addRule('phone', DVS_REQUIRED.' "'.$this->fb_fieldLabels['phone'].'"!', 'required', null, 'client');
        $form->addRule('phone', 'Непроавильный формат поля '.$this->fb_fieldLabels['phone'].'!', 'regex', '/^[0-9-+\(\) ]{7,17}$/', 'client');

        $form->addRule('email', DVS_REQUIRED.' "'.$this->fb_fieldLabels['email'].'"!', 'required', null, 'client');
        $form->addRule('email', 'Неправильный формат "'.$this->fb_fieldLabels['email'].'"!', 'email', null, 'client');

            //print_r($_SESSION);
        if ($_SESSION['_authsession']['data']['id']) {
            $users_obj = DB_DataObject::factory('users');
            $users_obj->get($_SESSION['_authsession']['data']['id']);
            $this->email = $users_obj->email;
            $this->first_name = $users_obj->name;
            $this->last_name = $users_obj->lastname;
            $this->phone = $users_obj->home_phone;
            //print_r($this);
        }
        if ($_SESSION['_authsession']['username']) {
            //echo $_SESSION['_authsession']['username'];
            $this->email = $_SESSION['_authsession']['username'];
        }


        $form->setDefaults( array('body' => $this->center_title, 'email' => $this->email, 'first_name' => $this->first_name, 'last_name' => $this->last_name, 'phone' => $this->phone));
        return $form;
    }

    function preProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'new') {
            $fb->dateToDatabaseCallback = null;
            $vals['email']          = strtolower($vals['email']);
            $vals['post_date']       = date(DB_DATE_FORMAT);
            //$vals['reg_date']       = date('d.m.Y');
            require_once COMMON_LIB.'DVS/Auth.php';
            $vals['ip']   = DVS_Auth::getIP();
            //$this->qs = '/?op=static&act=reg_email';
            //$this->msg = DVS_REG_EMAIL;
        } else {
            //$this->userEditableFields = array(

            //);
            //$this->qs = '/office/';
        }
    }

    function postProcessForm(&$vals, &$fb)
    {

            if ($this->act == 'new') {
                if ($this->iface != 'admin') {
                    $this->adminLetter($vals);
                    $this->qs = '/pages/newquery';
                }
            }
    }

    /**
    Переписка пользователей на странице заявки
    */
    function addAnswer($to, $from, $villa_id, $body, $booking_id)
    {
        $this->user_id_to = $to;
        $this->user_id = $from;
        $this->villa_id = $villa_id;
        $this->body = $body;
        $this->booking_id = $booking_id;
        $this->post_date       = date(DB_DATE_FORMAT);
            require_once COMMON_LIB.'DVS/Auth.php';
            $this->ip   = DVS_Auth::getIP();
        $this->insert();
        $this->qs = '/office/?op=static&act=add_row';
        $this->moderatorLetter($to, $booking_id, $body, $villa_id);
        //$this->userLetter($to);
    }


    function adminLetter($vals)
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = MAIL_ADMIN.',irina@villarenters.ru';
        $data['reply-to'] = $vals['email'];
        $data['subject'] = 'New ruRenter query '.$vals['villa_id'];
        //$data['body'] = DVS_Mail::letter($vals, 'reg_admin.tpl');
        $data['body'] = 
        'Имя: '.$vals['first_name']."\n"
        .'Фамилия: '.$vals['last_name']."\n"
        .'Е-mail: '.$vals['email']."\n"
        .'Телефон: '.$vals['phone']."\n\n"
        .'Дата: '.$vals['post_date']."\n"
        .'IP: '.$vals['ip']."\n\n"
        .'http://rurenter.ru/villa/'.$vals['villa_id'].".html\n"
        ."\n".$vals['body'];
        DVS_Mail::send($data);
    }

    function moderatorLetter($user_id_to, $booking_id, $body, $villa_id)
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = MAIL_ADMIN;
        $data['subject'] = 'New ruRenter User Answer villa '.$villa_id;
        $data['body'] = 
        'Поступило новое сообщение для пользователя '.$user_id_to
        .' по заявке №'.$booking_id.' объект http://rurenter.ru/villa/'.$villa_id.".html\n"
        .$body
        ."\nhttp://rurenter.ru/admin/?op=query \n"
        ."Требуется модерация этого сообщения !";
        DVS_Mail::send($data);
    }


    function userLetter($user_id_to, $booking_id, $body)
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($user_id_to);

        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj->email;
        $data['subject'] = 'ruRenter.ru notification';
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id==".$booking_id;
        $data_arr = array(
            'booking_id' => $booking_id,
            'booking_link' => $booking_link,
            'body' => $body,
            );
        $data['body'] = DVS_Mail::letter($data_arr, 'notification.tpl');
        DVS_Mail::send($data);
        /*
        $data['to'] = MAIL_ADMIN;
        $data['subject'] = 'ruRenter.ru notification';
        $data['body'] = DVS_Mail::letter($data_arr, 'booking_letter.tpl');
        DVS_Mail::send($data);
        */
        
    }

}
