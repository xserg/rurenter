<?php
/**
 * XML map
 * @package rurenter
 * $Id:$
 */

define('PER_PAGE', 10);

//define('TEST_DEBUG', 1);

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once COMMON_LIB.'DVS/Table.php';


class Project_Countries_Search extends DVS_Dynamic
{
    // Ïðàâà
    public $perms_arr = array('iu' => 1, 'oc' => 1);


    function getPageData()
    {        
        //print_r($_SERVER['REDIRECT_URL']);
        
        $term = DVS::getVar('term');
        $lang = DVS::getVar('lang');


        $name = 'name';

        //echo iconv("UTF-8", "Windows-1251", $term);

        if (preg_match("/[a-z]+/i", $term) || $lang == 'en') {
            $name = 'name';
        } else {

        //if (preg_match("/[à-ÿ]+/Ui", $term)) {
            $name = 'rus_name';
            $term = iconv("UTF-8", "Windows-1251", $term);
        }

        //echo $term.' '.$name.'<br>';

        $sql = 'select id, parent_id, name, rus_name from countries where '.$name.' like "'.$term.'%" order by '.$name.' limit 0,10';
        
        $this->db_obj->query($sql);
        

        while ($this->db_obj->fetch()) {
            $ñountries_obj = DB_DataObject::factory('countries');
            //$ñountries_obj->getParentName($this->db_obj->parent_id);
            $sql2 = 'select name, rus_name from countries where id='.$this->db_obj->parent_id;
            $ñountries_obj->query($sql2);
            $ñountries_obj->fetch();
            if ($lang == 'ru') {
                $value = iconv( "Windows-1251","UTF-8", $this->db_obj->rus_name.' ('.$ñountries_obj->rus_name.')');
            } else {
                $value =  $this->db_obj->name.' ('.$ñountries_obj->name.')';
            }
            $data[] = array('label' => $value, 'value' => $value, 'id' => $this->db_obj->id);

        }
        echo json_encode($data);

        exit;


        header ("content-type: text/xml; charset=utf-8");




        //$str = utf8_encode ($this->template_obj->get());

        echo iconv( "Windows-1251", "UTF-8",$this->template_obj->get());

        //echo $this->template_obj->get();
        exit;
    }

}
?>