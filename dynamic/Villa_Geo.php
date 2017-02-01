<?php
/**
 * XML map
 * @package rurenter
 * $Id:$
 */

define('PER_PAGE', 10);

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once COMMON_LIB.'DVS/Table.php';


class Project_Villa_Geo extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('iu' => 1, 'oc' => 1);


    function getPageData()
    {        
        //print_r($_SERVER['REDIRECT_URL']);
        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('georss.tpl');
        $this->db_obj->preGenerateList();
        //$links =  DVS_Table::createPager($this->db_obj);
        $this->db_obj->orderBy('villarentersindex DESC');
        $this->db_obj->limit(0,500);
        $this->db_obj->find();


        while ($this->db_obj->fetch()) {
            if ($this->db_obj->lat != 0 && $this->db_obj->lon != 0) {
                $this->template_obj->setVariable(array(
                //'LINK'      => 'http://villa/?op=villa&act=map&alias='.$_GET['alias'],
                'VILLA_LINK' => SERVER_URL.'/villa/'.$this->db_obj->id.'.html',
                'TITLE' => htmlspecialchars($this->db_obj->title),
                'SUMMARY' =>  htmlspecialchars('<a href="'.SERVER_URL.'/villa/'.$this->db_obj->id.'.html"><img src='.VILLARENTERS_IMAGE_URL.$this->db_obj->main_image.' title="'.$this->db_obj->sleeps.' sleeps '.$this->db_obj->proptype.'"><br>'.htmlspecialchars(substr($this->db_obj->summary, 0, 64)).'</a>'),

                //'TITLE' => $this->db_obj->title_rus ? $this->db_obj->title_rus : $this->db_obj->title,
                //'SUMMARY' => $this->db_obj->summary_rus ? $this->db_obj->summary_rus : $this->db_obj->summary,
                
                'PROPTYPE' => 'аренда: '.$this->db_obj->propTypeName($this->db_obj->proptype),
                'SLEEPS_NUM' => $this->db_obj->sleeps,
                'Rating' => $this->db_obj->villarentersindex,
                'MIN_PRICE' => $this->db_obj->minprice,
                'MAX_PRICE' => $this->db_obj->maxprice,
                'CURRENCY' => $this->db_obj->currency,
                'M_PHOTO_SRC' => VILLARENTERS_IMAGE_URL.$this->db_obj->main_image,
                'LAT' => $this->db_obj->lat,
                'LON' => $this->db_obj->lon,
                ));
                $this->template_obj->parse('VILLA');
            }
        }

            $this->template_obj->setVariable(array(
                //'PAGE_TITLE' => htmlspecialchars('Аренда вилл '.$this->db_obj->region_title_t.', '.$this->layout_obj->description),
                'link_self' => SERVER_URL.htmlspecialchars('/?op=villa&act=geo&alias=').$_GET['alias'],
                'link_alt' => SERVER_URL.'/regions/'.$_GET['alias'],

            ));

        header ("content-type: text/xml; charset=utf-8");

        //$str = utf8_encode ($this->template_obj->get());

        echo iconv( "Windows-1251", "UTF-8",$this->template_obj->get());

        //echo $this->template_obj->get();
        exit;
    }

}
?>