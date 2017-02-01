<?php
/**
 * Table Definition for options
 */
require_once 'DB/DataObject.php';

class DBO_Options extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'options';                         // table name
    public $_database = 'rurenter';                         // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $group_id;                        // int(4)   not_null
    public $name;                            // varchar(255)  
    public $rus_name;                        // varchar(255)  
    public $value_type;                      // tinyint(1)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Options',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'group_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'name' =>  DB_DATAOBJECT_STR,
             'rus_name' =>  DB_DATAOBJECT_STR,
             'value_type' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
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
             'name' =>  '',
             'rus_name' =>  '',
    );

    public $value_type_arr = array(1 => 'bool', 2 => 'list', 3 => 'char', 4 => 'num', 4 => 'time');

    public $qs_arr = array('_p', 'group_id');
/*
    function getLocalName()
    {
        if (LANG == 'ru') {
            return $this->rus_name;
        }
        return $this->name;
    }
*/
    function optForm1($form)
    {
        $this->find();
        while ($this->fetch()) {
            $form->addElement('checkbox', 'option['.$this->id.']', null, $this->rus_name);
        }
    }

    function preGenerateForm(&$fb)
    {
        $fb->preDefElements['value_type'] = HTML_QuickForm::createElement('select', 'value_type', 'Òèï', $this->value_type_arr);
        //print_r($fb);
    }

    function optForm($form, $lang)
    {
        //DB_DataObject::DebugLevel(1);
        $option_groups_obj = DB_DataObject::factory('option_groups');
        $this->joinAdd($option_groups_obj);
        $this->selectAs(array('id', 'name', 'rus_name'), '%s', 'options');
        $this->selectAs(array('id', 'name', 'rus_name'), 'group_%s', 'option_groups');
        $this->whereAdd('value_type=1 OR value_type=2');
        //$this->whereAdd('value_type=1');
        $this->whereAdd('options.id < 75');
        $this->orderBy('group_rus_name, options.rus_name');
        $this->find();
        /*
        $villa_opt_obj = DB_DataObject::factory('villa_options');
        $villa_opt_obj->villa_id = $this->db_obj->id;
        $villa_opt_obj->joinAdd($options_obj);
        $villa_opt_obj->selectAs();
        $villa_opt_obj->selectAs(array('id', 'name', 'rus_name'), '%s', 'options');
        $villa_opt_obj->selectAs(array('id', 'name', 'rus_name'), 'group_%s', 'option_groups');
        $villa_opt_obj->orderBy('group_name');
        $villa_opt_obj->find();
        */
        $cur_group = '';
        $i = 1;
        while ($this->fetch()) {
            if ($cur_group != $this->group_name) {
                $i = 1;
                if ($c) {
                    //while (sizeof($c) < 5) {
                        //$c[] = $form->createElement('static', '&nbsp;');
                    //}
                    $form->addGroup($c, 'options', '', '', false);
                }
                $c = array();
                            $form->addElement('static', '', '<hr>');
                $form->addElement('header', null, $this->{'group_'.self::getLocalName($lang)});

                
            }
            $c[] = $this->optElement($form, $lang);
            //$c[] = $form->createElement('checkbox', 'option['.$this->id.']', null, $this->{self::getLocalName($lang)});

            if (!($i % 4)) {
                $form->addGroup($c, 'options', '', '', false);
                $c = array();
            }
            
            //$form->addElement('checkbox', 'option['.$this->id.']', null, $this->rus_name);
            $i++;
            $cur_group = $this->group_name;
        }
        $form->addGroup($c, 'options', '', '', false);
        $form->addElement('static', '', '<hr>');

    }

    function getLocalName($lang)
    {
        if ($lang == 'ru') {
            return 'rus_name';
        }
        return 'name';
    }

    function optElement($form, $lang)
    {
        switch ($this->value_type) {
            default:
            case '1':
                return $form->createElement('checkbox', 'option['.$this->id.']', null, $this->{self::getLocalName($lang)});
                break;
            case '2':
                $option_values_obj = DB_DataObject::factory('option_values');
                $option_arr = $option_values_obj->getOptArr($this->id, $lang);
                return $form->createElement('select', 'option['.$this->id.']', $this->{self::getLocalName($lang)}, $option_arr);
                break;
        }
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
}
