<?php
/**
 * Table Definition for comments
 */
require_once 'DB/DataObject.php';

class DBO_Comments extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'comments';                        // table name
    public $_database = 'rurenter';                        // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $user_id;                         // int(4)   not_null
    public $villa_id;                        // int(4)   not_null
    public $article;                         // text()  
    public $article_rus;                     // text()  
    public $post_date;                       // datetime()   not_null
    public $rating;                          // int(4)   not_null
    public $author;                          // varchar(64)   not_null
    public $city;                            // varchar(128)  
    public $status_id;                       // tinyint(1)   not_null default_1

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Comments',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'article' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'article_rus' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'post_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME + DB_DATAOBJECT_NOTNULL,
             'rating' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'author' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'city' =>  DB_DATAOBJECT_STR,
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

    public $perms_arr = array(
        'new' => array('iu' => 1, 'oc' => 0, 'aa' => 0),
        //'list' => array('iu' => 1, 'oc' => 1),
        //'edit' => array('iu' => 1, 'oc' => 1),
        //'delete' => array('iu' => 1, 'oc' => 1),
        //'card' => array('iu' => 1, 'oc' => 1),
    );

    public $use_project_form_tmpl = true;

    public $form_template_name = 'form_query.tpl';

    public $status_arr = array(
        1 => 'Новый',
        2 => 'Активный',
        3 => 'Заблокированный'
    );

    private $rating_arr = array(3 => 3, 4=>4, 5=> 5, 5 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);

    public $listLabels   = array(
                     'villa_id'     =>  '',
                     'post_date'    =>  '',
                     'author'    =>  '',
                     'article' =>  '',
                     'status_id'     =>  ''
    );

    public $default_sort = 'id DESC';



    public function getForm()
    {
        require_once 'HTML/QuickForm/CAPTCHA/Image.php';
        $options = array(
            'width'        => 250,
            'height'       => 90,
            'callback'     => '/captcha.php?var='.basename(__FILE__, '.php'),
            'sessionVar'   => basename(__FILE__, '.php'),
            'imageOptions' => array(
                'font_size' => 20,
                'font_path' => COMMON_LIB.'fonts/',
                'font_file' => 'cour.ttf')
            );
        $villa_id = DVS::getVar('villa_id');

        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($villa_id);
        if ($villa_obj->N) {
            $this->center_title .= ' по '.$villa_obj->title_rus.' ('.$villa_id.')';
        }
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '', 'class="reg"');
        $form->addElement('hidden', 'villa_id', $villa_id);
        $form->addElement('header', null, '');
        $form->addElement('text', 'author', 'Ваше имя:');
        $form->addElement('text', 'city', 'Ваш город:');

        //$form->addElement('text', 'last_name', $this->fb_fieldLabels['last_name'].': ');
        //$form->addElement('text', 'email', $this->fb_fieldLabels['email'].': ');
        //$form->addElement('text', 'phone', $this->fb_fieldLabels['phone'].': ');
        $form->addElement('static', null, '</div><div class="clear"></div></div>');
        $form->addElement('header', null, 'Отзыв:');
        $form->addElement('textarea', 'article', '', array('rows' => 20, 'col' => 40));
        if ($_SESSION['_authsession']['data']['role_id'] == 'aa') {
            $form->addElement('select', 'status_id', 'Статус:', $this->status_arr);
        }
        $form->addElement('select', 'rating', 'Ваша оценка:', $this->rating_arr);
////////////////////////////////////////////////////////////////
        if (!$_SESSION) {
            @session_start();
        }
        $captcha_question = & $form->addElement('CAPTCHA_Image', 'captcha_question', '', $options);
        $form->addElement('static', null, null, 'Если вы не уверены, кликните на картинку для смены');
        $form->addElement('text', 'captcha', 'Введите текст с картинки');
        $form->addRule('captcha', 'Введите текст с картинки!', 'required', null, 'client');
        $form->addRule('captcha', '<br>Текст не соответствует картинке!', 'CAPTCHA', $captcha_question);
/////////////////////////////////////////

        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addElement('static', null, '</div></div>');
        $form->addRule('author', DVS_REQUIRED.' "'.$this->fb_fieldLabels['author'].'"!', 'required', null, 'client');
        $form->addRule('article', DVS_REQUIRED.' "'.$this->fb_fieldLabels['article'].'"!', 'required', null, 'client');
        //$form->setDefaults( array('article' => $this->center_title));
        return $form;
    }

    function preProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'new') {
            $fb->dateToDatabaseCallback = null;
            $vals['post_date'] = date("Y-m-d H:i:s");
            require_once COMMON_LIB.'DVS/Auth.php';
            $vals['ip']   = DVS_Auth::getIP();
            $this->qs = '/?op=static&act=moder';
            //$this->qs = '/?op=comments&act=show&id='.$this->id;
            //$this->msg = DVS_ADD_ROW;
        }
    }

    function postProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'new') {
            //$this->qs = '/?op=comments&act=show&id='.$this->id;
            //$this->msg = DVS_ADD_ROW;
            $this->adminLetter($vals);
        }

    }

    function adminLetter($vals)
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = MAIL_ADMIN;
        $data['reply-to'] = $vals['email'];
        $data['subject'] = 'New comment '.$vals['villa_id'];
        //$data['body'] = DVS_Mail::letter($vals, 'reg_admin.tpl');
        $data['body'] = 
        'Имя: '.$vals['author']."\n"
        .'Город: '.$vals['city']."\n"
        .'Дата: '.$vals['post_date']."\n"
        .'IP: '.$vals['ip']."\n\n"
        //.'http://villarenters.ru/villa/'.$vals['villa_id'].".html\n"
        ."\n".$vals['article'];
        DVS_Mail::send($data);
    }
}
