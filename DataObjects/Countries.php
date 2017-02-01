<?php
/**
 * Table Definition for countries
 */
require_once 'DB/DataObject.php';

class DBO_Countries extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'countries';                       // table name
    public $_database = 'rurenter';                       // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $parent_id;                       // int(4)   not_null
    public $name;                            // varchar(255)   not_null
    public $rus_name;                        // varchar(255)   not_null
    public $counter;                         // int(4)  
    public $country_descr;                   // text()  
    public $popular;                         // tinyint(1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Countries',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'parent_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'rus_name' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'counter' =>  DB_DATAOBJECT_INT,
             'country_descr' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'popular' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL,
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
    /*
    function sequenceKey() // keyname, use native, native name
    {
        return $this->disable_sk ? array() : array('id', false, false);
    }
    */
    public $listLabels   = array(
            'name'      => '',
            'rus_name' => '',
            'counter' => '',
            'delvilla' => ''
    );

    /**
     * ���������� ������� �� ���������
     */
    public $default_sort = 'name';

    public $qs_arr = array('parent_id');


    public $perms_arr = array(
        'json' => array('iu' => 1, 'oc' => 1),
     );

    function tableRow()
    {

        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->whereAdd('locations_all LIKE "%:'.$this->id.':%"');
        $cnt = $villa_obj->count();

        return array(
            'name'      => '<a href="?op=countries&parent_id='.$this->id.'">'.$this->name.'</a>',
            'rus_name' => $this->rus_name,
            'counter' =>  '<a href="?op=villa&loc='.$this->id.'">'.$this->counter.' ('.$cnt.')</a>',
            'delvilla' => '<a href="?op=villa&act=del&loc='.$this->id.'" onclick="return doDelete();">������� �����</a>'
        );
    }


    function preGenerateList()
    {
        if (!isset($this->parent_id)) {
            $this->parent_id = 0;
        } else {
            /*
            $�ountries_obj = DB_DataObject::factory('countries');
            $�ountries_obj->get($this->parent_id);
            $this->center_title .= ' / '.$�ountries_obj->name;
            */
            $this->getParent($this->parent_id);
            $this->center_title .= ' / '.$this->region_title;
            //print_r($�ountries_obj->toArray());
            //$this->qs .= '&adm_unit_type='.$type_obj->adm_unit_type;
        }
    }

    function getParent($pid, $link='regions/', $lang='ru')
    {
        $�ountries_obj = DB_DataObject::factory('countries');
        $�ountries_obj->get($pid);
        if (!$this->region_title_t) {
            $this->region_title_t = $�ountries_obj->getLocalName($lang);
            $this->region_title_a = '<a href="'.($lang != 'ru' ? '/'.$lang : '').'/regions/'.$�ountries_obj->getAlias().'">'.$�ountries_obj->getLocalName($lang).'</a>';

        }
        $this->region_title = '<a href="'.($lang != 'ru' ? '/'.$lang : '').'/regions/'.urlencode($�ountries_obj->getAlias()).'">'.$�ountries_obj->getLocalName($lang).'</a> / '.$this->region_title;
        $this->locations_all = $�ountries_obj->id.($this->locations_all ? ':'.$this->locations_all : '');
        if ($�ountries_obj->parent_id > 0) {
            $this->getParent($�ountries_obj->parent_id, $link, $lang);
        }
    }



    function getChilds($pid)
    {
        $�ountries_obj = DB_DataObject::factory('countries');
        $�ountries_obj->parent_id = $pid;
        $�ountries_obj->find();
        if ($�ountries_obj->N == 0) {
            $this->loc_arr[] = $pid;
        }
        while ($�ountries_obj->fetch()) {
            //$this->childs[$pid][] = $�ountries_obj->id;
            $this->getChilds($�ountries_obj->id);
        }
    }

    function getLocalName($lang)
    {
        if ($lang == 'ru') {
            return $this->rus_name;
        }
        return $this->name;
    }

    function getAlias()
    {
        //return strtolower(str_replace(array(" ", "&"), array("_",'and'), trim($this->name)));
        return strtolower(str_replace(" ", "_", $this->name));
    }

    function getLocationsIdStr($alias)
    {
        $loc_arr = explode('\\', $alias);
    }

    function findLocationByKeyword($keyword)
    {
        $this->whereAdd('parent_id > 0');
        $this->whereAdd('name LIKE "'.$keyword.'%"');
    }

	function selArray($pid=0)
	{
		$this->parent_id = $pid;
		$this->orderby('rus_name');
		$this->find();
		while ($this->fetch()) {
			$ret[$this->id] = $this->rus_name;
		}
		return $ret;
	}

    function preDelete()
    {
        $this->getChilds($this->id);
        foreach ($this->loc_arr as $k => $id) {
            $villa_obj = DB_DataObject::factory('villa');
            $villa_obj->whereAdd('locations_all LIKE "%:'.$id.':%"');
            $cnt = $villa_obj->count();
            if ($cnt) {
                $this->msg = '������: ���������� ������ '.$cnt;
                return false;
            }
           $�ountries_obj = DB_DataObject::factory('countries');
            $�ountries_obj->get($id);
            $�ountries_obj->delete();
        }
        return true;
    }

    function getList($parent_id=0, $proptype=null, $sale=0)
    {
        if ($proptype) {
            //$prop_str = " AND proptype IN ('$proptype')";
            $prop_str = " AND ".$proptype;
        }
        $sql ="SELECT countries.name, rus_name, countries.id as id, COUNT(villa.id) as counter FROM countries JOIN villa ON (villa.locations_all LIKE CONCAT('%:', countries.id, ':%')) WHERE parent_id=$parent_id AND villa.status_id=2 AND sale=$sale$prop_str group by countries.id order by rus_name";
        $this->query($sql);
    }

    function getParentName($pid, $lang='ru')
    {
        $�ountries_obj = DB_DataObject::factory('countries');
        $�ountries_obj->get($pid);
        $this->parent_name = $�ountries_obj->getLocalName($lang);
        //.' / '.$this->parent_name;
        if ($�ountries_obj->parent_id > 0) {
            //$this->getParentName($�ountries_obj->parent_id, $lang);
        }
    }
}
