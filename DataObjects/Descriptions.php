<?php
/**
 * Table Definition for descriptions
 */
require_once 'DB/DataObject.php';

class DBO_Descriptions extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'descriptions';                    // table name
    public $_database = 'rurenter';                    // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)   not_null
    public $title;                           // varchar(255)   not_null
    public $body;                            // text()  
    public $title_rus;                       // varchar(255)   not_null
    public $body_rus;                        // text()   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Descriptions',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'title' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'body' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'title_rus' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'body_rus' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT + DB_DATAOBJECT_NOTNULL,
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


	function descriptionForm($form, $id='')
	{
        $t1 = $form->createElement('text', 'title', '', 'size=50');
        $t2 = $form->createElement('text', 'title_rus', '', 'size=50');
        
        $form->addGroup(array($t1, $t2), 'descr['.$id.']', 'Заголовок: ');
        $s1 = $form->createElement('textarea', 'body', '', array('rows' => 5, 'cols' => 47));
        $s2 = $form->createElement('textarea', 'body_rus', '', array('rows' => 5, 'cols' => 47));
        $form->addGroup(array($s1, $s2), 'descr['.$id.']', 'Описание: ');
	}

	function editForm($villa_id, $form)
	{

        if ($villa_id) {
            $this->villa_id = $villa_id;
            $this->find();
            $i = 1;
            while ($this->fetch()) {
                $this->descriptionForm($form, $this->id);
                $form->setDefaults(array(	'descr['.$this->id.'][title]' => $this->title,
                                            'descr['.$this->id.'][title_rus]' => $this->title_rus,
                                            'descr['.$this->id.'][body]' => $this->body,
                                            'descr['.$this->id.'][body_rus]' => $this->body_rus,	
                ));
                $i++;
            }
        } else {
            for ($i = 1; $i < 4; $i++) {
                $this->descriptionForm($form, $i);
            }
        }
	}


}
