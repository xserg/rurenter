<?php
/**
 * Table Definition for pay_services
 */
require_once 'DB/DataObject.php';

class DBO_Pay_services extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pay_services';                    // table name
    public $_database = 'rurenter';                    // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $name;                            // varchar(255)  
    public $status;                          // int(4)  
    public $about;                           // varchar(4000)  
    public $service_type_id;                 // int(4)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Pay_services',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'name' =>  DB_DATAOBJECT_STR,
             'status' =>  DB_DATAOBJECT_INT,
             'about' =>  DB_DATAOBJECT_STR,
             'service_type_id' =>  DB_DATAOBJECT_INT,
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

    /**
     * Заголовок для таблицы
     */
    public $center_title = 'Платежные системы';

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form    = 'систему';

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
            'name'      => '',
            'status'    => '',
            'payment'     =>  'align=center',
            //'type_type_name' => ''
    );

    public $fb_fieldLabels  = array(
            'name'              => 'Название',
            'status'            => 'Статус',
            'about'             => 'Описание',
            'service_type_id'   => 'Тип',
            'type_type_name'    => 'Тип',
            'payment'           => 'Платежи',
        );

    public $fb_fieldsRequired = array('name', 'service_type_id');

    private $status_arr = array(1 => 'Неактивный', 2 => 'Активный');

    public $fb_linkDisplayFields = array('name');

    function selArray($status = 0)
    {
        if ($status > 0) {
            $this->status = $status;
        }
        //$this->orderBy('name');
        $this->orderBy('id DESC');
        $this->find();
        while ($this->fetch()) {
            $ret[$this->id] = $this->name;
        }
        return $ret;
    }

    /**
     * Строка данных таблицы
     */
    function tableRow()
    {
        return array(
            'name'     =>  '<a href="?op=pay_services&act=show&id='.$this->pay_service_id.'">'.$this->name.'</a>',
            'payment'     =>  '<a href="?op=payments_in&pay_service_id='.$this->pay_service_id.'">Платежи</a>',
            'status'    =>  $this->status_arr[$this->status],
            );
    }
/*
    function createObjects()
    {
        $this->service_type_obj = DB_DataObject::factory('pay_service_type');
    }

    function preGenerateList()
    {
        $this->createObjects();
        $this->joinAdd($this->service_type_obj);
        $this->selectAs();
        $this->selectAs($this->service_type_obj, 'type_%s');
    }
*/
    function getForm()
    {
        $this->createObjects();
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI']);
        $form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        $form->addElement('text', 'name', $this->fb_fieldLabels['name'], array('size' => 50));
        $form->addElement('select', 'status', $this->fb_fieldLabels['status'].': ', $this->status_arr);
        $form->addElement('textarea', 'about', $this->fb_fieldLabels['about'], array('rows' => 3, 'cols' => 50));

            //$service_types = $this->service_type_obj->selArray();
            //$form->addElement('select', 'service_type_id', $this->fb_fieldLabels['service_type_id'].': ', $service_types);
/*
        if ($this->act == 'new') {
            $service_types = $this->service_type_obj->selArray();
            $form->addElement('select', 'service_type_id', $this->fb_fieldLabels['service_type_id'].': ', $service_types);
        } else {
            $this->service_type_obj->get($this->service_type_id);
            $form->addElement('select', 'service_type_id', $this->fb_fieldLabels['service_type_id'].': ', array($this->service_type_id => $this->service_type_obj->type_name));
            $form->setDefaults($this->toArray());
            $form->freeze('service_type_id');
            $this->propertiesForm($form);
        }
*/
        $form->addElement('submit', '__submit__', 'Сохранить');
        $form->addRule('name', 'Заполните поле!', 'required');
        return $form;
    }

    function preProcessForm(&$vals, &$fb)
    {
        $this->query('BEGIN');
    }

    function postProcessForm(&$vals, &$fb)
    {
        if ($this->act == 'edit' && isset($this->properties_obj)) {
            $this->properties_obj->setFrom($vals);
            $action = $this->properties_obj->N ? 'update' : 'insert';
            $this->properties_obj->$action();
        }
        if(!$this->_lastError && !$this->properties_obj->_lastError) {
            $this->query('COMMIT');
        } else {
            $this->query('ROLLBACK');
        }
    }

    function propertiesForm($form)
    {
        if ($this->service_type_obj->shortname == 'nal') {
            return;
        }
        $this->properties_obj = DB_DataObject::factory($this->service_type_obj->shortname.'_properties');
        $this->properties_obj->get('pay_service_id', $this->pay_service_id);
        $form->setDefaults($this->properties_obj->toArray());
        $this->properties_obj->formProperties($form);
    }
}
