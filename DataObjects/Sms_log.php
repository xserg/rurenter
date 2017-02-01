<?php
/**
 * Table Definition for sms_log
 */
require_once 'DB/DataObject.php';

class DBO_Sms_log extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'sms_log';                         // table name
    public $_database = 'rurenter';                         // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $phone;                           // varchar(255)   not_null
    public $text;                            // varchar(156)   not_null
    public $send_time;                       // datetime()   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Sms_log',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'phone' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'text' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'send_time' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME + DB_DATAOBJECT_NOTNULL,
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
