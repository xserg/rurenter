<?php
/**
 * Table Definition for booking_log
 */
require_once 'DB/DataObject.php';

class DBO_Booking_log extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'booking_log';                     // table name
    public $_database = 'rurenter';                     // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)   not_null
    public $user_id;                         // int(4)  
    public $start_date;                      // date()  
    public $end_date;                        // date()  
    public $people;                          // tinyint(1)  
    public $childs;                          // tinyint(1)  
    public $infants;                         // tinyint(1)  
    public $book_status;                     // tinyint(1)   not_null
    public $post_date;                       // datetime()   not_null
    public $ip;                              // varchar(255)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Booking_log',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT,
             'start_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE,
             'end_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE,
             'people' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL,
             'childs' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL,
             'infants' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL,
             'book_status' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
             'post_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME + DB_DATAOBJECT_NOTNULL,
             'ip' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
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
    public $center_title = 'Заявки Villarenters';

    /**
     * Заголовок формы (Добавить - Редактировать)
     */
    public $head_form = 'бронь';


    public $default_sort = 'id DESC';

    public $fb_fieldLabels   = array(
                     'id' => '#',
                     'villa_title' => 'Объект',
                     'villa_id'         => 'Вилла',
                     'user_id'          => 'Пользователь',
                     'period' => 'Период',
                     'start_date'       =>  'Дата 1',
                     'end_date'         =>  'Дата 2',
                     'book_status'      =>  'Статус',
                     'price'            =>  'Цена',
                     'currency'         =>  'Валюта',
                    'post_date'       =>  'Дата добавления',
           'people' => 'Человек',
    );

    function daysCount($start='', $end ='')
    {
        if (!$start && !$end) {
            $start = $this->start_date;
            $end = $this->end_date;
        }
        $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
        return $days;
    }
}
