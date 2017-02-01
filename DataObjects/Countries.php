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
     * Ñîğòèğîâêà òàáëèöû ïî óìîë÷àíèş
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
            'delvilla' => '<a href="?op=villa&act=del&loc='.$this->id.'" onclick="return doDelete();">óäàëèòü âèëëû</a>'
        );
    }


    function preGenerateList()
    {
        if (!isset($this->parent_id)) {
            $this->parent_id = 0;
        } else {
            /*
            $ñountries_obj = DB_DataObject::factory('countries');
            $ñountries_obj->get($this->parent_id);
            $this->center_title .= ' / '.$ñountries_obj->name;
            */
            $this->getParent($this->parent_id);
            $this->center_title .= ' / '.$this->region_title;
            //print_r($ñountries_obj->toArray());
            //$this->qs .= '&adm_unit_type='.$type_obj->adm_unit_type;
        }
    }

    function getParent($pid, $link='regions/', $lang='ru')
    {
        $ñountries_obj = DB_DataObject::factory('countries');
        $ñountries_obj->get($pid);
        if (!$this->region_title_t) {
            $this->region_title_t = $ñountries_obj->getLocalName($lang);
            $this->region_title_a = '<a href="'.($lang != 'ru' ? '/'.$lang : '').'/regions/'.$ñountries_obj->getAlias().'">'.$ñountries_obj->getLocalName($lang).'</a>';

        }
        $this->region_title = '<a href="'.($lang != 'ru' ? '/'.$lang : '').'/regions/'.urlencode($ñountries_obj->getAlias()).'">'.$ñountries_obj->getLocalName($lang).'</a> / '.$this->region_title;
        $this->locations_all = $ñountries_obj->id.($this->locations_all ? ':'.$this->locations_all : '');
        if ($ñountries_obj->parent_id > 0) {
            $this->getParent($ñountries_obj->parent_id, $link, $lang);
        }
    }



    function getChilds($pid)
    {
        $ñountries_obj = DB_DataObject::factory('countries');
        $ñountries_obj->parent_id = $pid;
        $ñountries_obj->find();
        if ($ñountries_obj->N == 0) {
            $this->loc_arr[] = $pid;
        }
        while ($ñountries_obj->fetch()) {
            //$this->childs[$pid][] = $ñountries_obj->id;
            $this->getChilds($ñountries_obj->id);
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
                $this->msg = 'Îøèáêà: Ñóùåñòâóşò çàïèñè '.$cnt;
                return false;
            }
           $ñountries_obj = DB_DataObject::factory('countries');
            $ñountries_obj->get($id);
            $ñountries_obj->delete();
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
        $ñountries_obj = DB_DataObject::factory('countries');
        $ñountries_obj->get($pid);
        $this->parent_name = $ñountries_obj->getLocalName($lang);
        //.' / '.$this->parent_name;
        if ($ñountries_obj->parent_id > 0) {
            //$this->getParentName($ñountries_obj->parent_id, $lang);
        }
    }
}
