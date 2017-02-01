<?php
/**
 * Table Definition for option_groups
 */
require_once 'DB/DataObject.php';

class DBO_Option_groups extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'option_groups';                   // table name
    public $_database = 'rurenter';                   // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $name;                            // varchar(255)  
    public $rus_name;                        // varchar(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Option_groups',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'name' =>  DB_DATAOBJECT_STR,
             'rus_name' =>  DB_DATAOBJECT_STR,
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

    public $fb_linkDisplayFields = array('name');

    public $listLabels   = array(
             'name' =>  '',
             'rus_name' =>  '',
    );

    /**
     * Сортировка таблицы по умолчанию
     */
    public $default_sort = 'name';

    /**
     * Строка данных таблицы
     */
    function tableRow()
    {
        return array(
            'name' => '<a href="?op=options&group_id='.$this->id.'">'.$this->name.'</a>',
            'rus_name'     => $this->rus_name

        );
    }

    function selArray()
    {
        $this->orderBy('name');
        $this->find();
        while ($this->fetch()) {
            $ret[$this->id] = $this->name;
        }
        return $ret;
    }

    function getLocalName($lang)
    {
        if ($lang == 'en') {
            return $this->name;
        }
        return $this->rus_name ? $this->rus_name : $this->name;
    }
}
