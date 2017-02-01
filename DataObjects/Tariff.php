<?php
/**
 * Table Definition for tariff
 */
require_once 'DB/DataObject.php';

class DBO_Tariff extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'tariff';                          // table name
    public $_database = 'rurenter';                          // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)  
    public $start_date;                      // date()  
    public $end_date;                        // date()  
    public $tariff;                          // float()  
    public $currency;                        // int(4)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Tariff',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT,
             'start_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE,
             'end_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE,
             'tariff' =>  DB_DATAOBJECT_INT,
             'currency' =>  DB_DATAOBJECT_INT,
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
