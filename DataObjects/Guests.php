<?php
/**
 * Table Definition for guests
 */
require_once 'DB/DataObject.php';

class DBO_Guests extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'guests';                          // table name
    public $_database = 'rurenter';                          // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $booking_log_id;                  // int(4)   not_null
    public $guest_name;                      // varchar(255)   not_null
    public $guest_lastname;                  // varchar(255)   not_null
    public $age;                             // int(4)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Guests',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'booking_log_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'guest_name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'guest_lastname' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'age' =>  DB_DATAOBJECT_INT,
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

    public $listLabels   = array(
            'booking_log_id' => '',
            'guest_name'     =>  '',
            'guest_lastname'    =>  '',
            'age' => '',
    );

    /**
     * Название столбцов и элементов формы
     */
    public $fb_fieldLabels  = array(
            'booking_log_id' => 'Заявка',
            'guest_name'     =>  'Имя',
            'guest_lastname'    =>  'Фамилия',
            'age' => 'Возраст',        
    );

}
