<?php
/**
 * Table Definition for pages
 */
require_once 'DB/DataObject.php';

class DBO_Pages extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pages';                           // table name
    public $_database = 'rurenter';                           // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $ptype;                           // int(4)  
    public $alias;                           // varchar(64)  
    public $title;                           // varchar(1000)  
    public $body;                            // mediumtext()  
    public $post_date;                       // date()  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Pages',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'ptype' =>  DB_DATAOBJECT_INT,
             'alias' =>  DB_DATAOBJECT_STR,
             'title' =>  DB_DATAOBJECT_STR,
             'body' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'post_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE,
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

    public $fb_dateElementFormat = 'd.m.y';

    //public $fb_dateFromDatabaseCallback = array('DVS_ShowForm2','date2array');

    //public $fb_dateToDatabaseCallback = array('DVS_ShowForm2','array2date');


    // Заголовок для страницы
    public $center_title = 'Страницы сайта';
    // Названия добавляемого объекта
    public $head_form    = 'страницу';

    public $noStripTags = true;

    // Cтолбцы
    public $listLabels   = array(
        'post_date'      => '',
        'alias'     => '',
        'title'     => '',
    );

    // Название столбцов и элементов формы
    public $fb_fieldLabels  = array(
        'alias'     => 'Cсылка',
        'title'     => 'Заголовок',
        'body'      => 'Текст',
        'post_date' => 'Дата',
        'ptype'     => 'Тип'
    );

    public $default_sort = 'post_date desc';

    public $perms_arr = array(
        'list' => array('ar' => 1),
        'edit' => array('ar' => 1),
        'new' => array('ar' => 1),
        'delete' => array('ar' => 1),
    );


    function tableRow()
    {
        return array(
            'post_date'  => DVS_Dynamic::RusDate($this->post_date),
            'alias' => '<a href="?op=pages&act=show&id='.$this->id.'">'.$this->alias.'</a>',
            'title' => $this->title,
        );
    }

    function getNewsBlock($tpl, $cnt=3)
    {
        //$tpl->loadTemplateFile('news.tpl');
        $tpl->addBlockfile('RIGHT', 'NEWS', 'news.tpl');
        $this->ptype = 1;
        $this->orderBy('post_date DESC');
        $this->limit(0, $cnt);
        $this->find();
        while ($this->fetch()) {
            $tpl->setVariable(array(
                'NDATE' => DVS_Dynamic::RusDate($this->post_date),
                'NLINK' => '/pages/'.$this->alias,
                'NLINK_TEXT' => $this->title
                ));
            $tpl->parse('NEWS_ITEM');
        }
        return;
    }


    //Вывод формы
    function getForm()
    {
        $this->src_files = array(
            //'JS_SRC' => array('/js/jquery.js', '/js/jqUploader/jquery.jqUploader.js', '/js/jquery.flash.js'),
            'CSS_SRC'  => array('/css/form.css')
        );
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '', 'class="reg"');
        //$form->addElement('header', null, strtoupper(($_GET['act'] == 'new' ? 'Добавить' : 'Редактировать').' '.$this->head_form));
        //$form->addElement('select', 'server', 'Сервер: ', array($this->common_server => '') + $this->server_arr);
        $form->addElement('select', 'ptype', $this->fb_fieldLabels['ptype'].': ', array(1 => 'Новость', 2 => 'Текст', 3 => 'Помощь'));
        $form->addElement('text', 'alias', $this->fb_fieldLabels['alias'].': ');
        $form->addElement('text', 'title', $this->fb_fieldLabels['title'].': ');
        $form->addElement('date', 'post_date', $this->fb_fieldLabels['post_date'], $this->dateOptions());

        //$form->addElement(DVS_ShowForm2::createFCK('text'));
        $form->addElement('textarea', 'body', $this->fb_fieldLabels['body'].': ', array('cols' => 60, 'rows' => 30));
        $form->addElement('submit', '__submit__', 'Сохранить');
        $form->addRule('alias', 'Заполните поле '.$this->fb_fieldLabels['alias'].'!', 'required', null, 'client');
        $form->addRule('title', 'Заполните поле '.$this->fb_fieldLabels['title'].'!', 'required', null, 'client');
        $form->addRule('body', 'Заполните поле '.$this->fb_fieldLabels['body'].'!', 'required', null);
        //$this->renderer_obj->setElementBlock('text', 'qf_static_el');
        if (DVS::getVar('act') == 'new') {
            $form->setDefaults(array('post_date' => date('Y-m-d')));
        } else {
            $form->setDefaults($this->toArray());
        }
        return $form;
    }



    function dateOptions()
    {
        return array(   'language' => 'ru',
                        'format'=>'d-F-Y',
                        'minYear'=>date("Y")-1,
                        'maxYear'=>date("Y"),
                        'addEmptyOption' => true
            
        );
    }
}
