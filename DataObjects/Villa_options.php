<?php
/**
 * Table Definition for villa_options
 */
require_once 'DB/DataObject.php';

class DBO_Villa_options extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'villa_options';                   // table name
    public $_database = 'rurenter';                   // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)   not_null
    public $option_id;                       // int(4)   not_null
    public $value;                           // varchar(255)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Villa_options',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'option_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'value' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
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

    private $options_arr = array(
                    'DoubleBeds' => 1,
                    'TwinBeds' => 2,
                    'SingleBeds' => 3,
                    'Cots' => 4,
                    'Bathrooms' => 5,
                    'TV' => 6,
                    'Satellite' => 7,
                    'Telephone' => 8,
                    'Cooker' => 9,
                    'Microwave' => 10,
                    'Fridge' => 11,
                    'Freezer' => 12,
                    'Internet' => 13,
                    'CentralHeating' => 14,
                    'LinenProvided' => 15,
                    'AirConditioning' => 16,
                    'Traffic' => 17,
                    'Seaside' => 18,
                    'SightSeeing' => 19,
                    'Tourist' => 20,
                    'Romantic' => 21,
                    'Relaxing' => 22,
                    'Activity' => 23,
                    'Pets' => 24,
                    'OpenFire' => 25,
                    'Garden' => 26,
                    'Patio' => 27,
                    'Balcony' => 28,
                    'PrivatePool' => 29,
                    'SharedPool' => 30,
                    'TennisCourt' => 31,
                    'Parking' => 32,
                    'Airport' => 33,
                    'Beach' => 34,
                    'Sailing' => 35,
                    'Swimming' => 36,
                    'Walking' => 37,
                    'Cycling' => 38,
                    'HorseRiding' => 39,
                    'Skiing' => 40,
                    'Golf' => 41,
                    'Tennis' => 42,
                    'Climbing' => 43,
                    'Nonesmoking' => 44,
                    'AirportDistance' => 45,
 );
    

    function insertOptions($villa_id, $obj)
    {
        $this->villa_id = $villa_id;
        $search_arr == array_flip($this->options_arr);
        foreach ($this->options_arr as $name => $id) {
            $this->option_id = $id;
            $this->value = $this->convertVals($obj->$name);
            $this->insert();
        }
    }

    function convertVals($val)
    {
        $val = strval($val);
        switch ($val){
            case 'false':
                return 0;
            break;
            case 'true':
                return 1;
            break;
            default:
                return $val;
            break;

        }
    }

    function getOptions($villa_id, $lang='en')
    {
        $this->villa_id = $villa_id;
        $this->find();
        while ($this->fetch()) {
            $ret[$this->option_id] = $this->value;
        }
        return $ret;
    }

    function getLocalField($field, $lang)
    {
        if ($lang == 'en') {
            return $this->{$field};
        }
        return $this->{'rus_'.$field} ? $this->{'rus_'.$field} : $this->{$field};
    }

    function insertOptionsHA($villa_id, $option_id)
    {
        $this->villa_id = $villa_id;
        $this->option_id = $option_id;
        $this->value = 1;
        //$this->value = $this->convertVals($obj->$name);
        $this->insert();
    }
}
