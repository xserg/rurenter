<?php
/**
 * Table Definition for option_values
 */
require_once 'DB/DataObject.php';

class DBO_Option_values extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'option_values';                   // table name
    public $_database = 'rurenter';                   // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $option_id;                       // int(4)   not_null
    public $name;                            // varchar(255)   not_null
    public $rus_name;                        // varchar(255)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Option_values',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'option_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'rus_name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
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

    function getLocalName($lang)
    {
        if ($lang == 'ru') {
            return 'rus_name';
        }
        return 'name';
    }

    function getOptArr($option_id, $lang)
    {
        $this->option_id = $option_id;
        $this->find();
        $ret = array('' => '');
        while ($this->fetch()) {
            //$ret[$this->id] = $this->{self::getLocalName($lang)};
            $ret[$this->name] = $this->{self::getLocalName($lang)};
        }
        return $ret;
    }
}
