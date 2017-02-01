<?php
/**
 * Table Definition for adm_types
 */
require_once 'DB/DataObject.php';

class DBO_Adm_types extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'adm_types';                       // table name
    public $_database = 'rurenter';                       // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $name;                            // varchar(255)   not_null
    public $name_rus;                        // varchar(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Adm_types',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'name_rus' =>  DB_DATAOBJECT_STR,
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
}
