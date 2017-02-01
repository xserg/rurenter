<?php
/**
 * Список вилл
 * @package villarenters
 * $Id: Villa_Index.php 537 2015-04-09 10:15:57Z xxserg $
 */

define('PER_PAGE', 10);
define('DEBUG', 1);

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once COMMON_LIB.'DVS/Table.php';
//require (PROJECT_ROOT.'/layout/getXML.php');

class Project_Villa_Index extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('iu' => 1, 'mu' => 1,'oc' => 1);

    private $lang_url;

    private $lang_ru = array(
        'Rates' => 'Цены',
        'week' => 'в неделю',
        'Sleeps' => 'спальных мест',
        'details' => 'Подробнее...',
        'booking' => 'Забронировать',
        'list_title' => 'Предложения по аренде лучших вилл, домов, коттеджей, апартаментов без посредников',
        'page' => 'стр.',
        'Search' => 'Поиск',
        'map'    => 'На карте',
        'list' => 'Список',
        'regions' => 'Регионы',
        'in_all' => 'Всего',
        'index' => 'Индекс',
    );

    private $lang_en = array(
        'Rates' => 'Rates',
        'week' => 'per week',
        'Sleeps' => 'Sleeps',
        'details' => 'details...',
        'booking' => 'booking',
        'list_title' => 'Self catering villas, apartments and cottages for rent over the world',
        'page' => 'page',
        'Search' => 'Search',
        'map'    => 'Map view',
        'list' => 'List view',
        'regions' => 'Regions',
        'in_all' => 'In all',
        'index' => 'Index',

    );

    function getPageData()
    {

        $sale = DVS::getVar('sale');

        if (!$sale) {
            $sale = 0;
        } else {
            $sale = 1;
        }
        $this->db_obj->sale = $sale;

        //$map = DVS::getVar('map');
        if(isset($_GET['map'])) {
            $this->map = 1;
        }
        $this->words = $this->{'lang_'.$this->lang};
        if ($this->lang != 'ru') {
            //$this->db_obj->lang = $this->lang;
            $this->lang_url = '/'.$this->lang;
        }
        /**
        * Поиск по keyword preGenerateList
        */
        $sale = DVS::getVar('sale');
        
        $keyword = DVS::getVar('keyword');
        $minprice = DVS::getVar('minprice', 'int');
        $maxprice = DVS::getVar('maxprice', 'int');
        $start = DVS::getVar('startDateInput');
        $end = DVS::getVar('endDateInput');
        $sleeps = DVS::getVar('sleeps');
        $proptype = DVS::getVar('proptype');

        $this->createTemplateObj();
         $this->template_obj->loadTemplateFile('villa_search2.tpl');

        $search_form = $this->db_obj->getSearchFormClient($this->template_obj, $this->lang);

        $this->template_obj->loadTemplateFile('villa_list.tpl');

        //$this->template_obj->setVariable(array('startDateInput' => $start, 'endDateInput' => $end));

        //

            $rate = $this->db_obj->currencyRate();
            //print_r($rate);
            //DB_DataObject::DebugLevel(1);
            if ($minprice) {
                //$this->db_obj->whereAdd('minprice > '.$minprice);

                $this->db_obj->whereAdd("minprice > CASE currency"
                    ." WHEN \"USD\" THEN ".round($minprice * $rate['eur_usd'])
                    ." WHEN \"GBP\" THEN ".round($minprice * $rate['eur_gbp'])
                    ." WHEN \"EUR\" THEN ".$minprice." END");
                 $this->template_obj->setVariable(array('minprice' => $minprice));
            }

            if ($maxprice) {
                //$this->db_obj->whereAdd('maxprice < '.$maxprice);

                $this->db_obj->whereAdd("maxprice < CASE currency"
                    ." WHEN \"USD\" THEN ".round($maxprice * $rate['eur_usd'])
                    ." WHEN \"GBP\" THEN ".round($maxprice * $rate['eur_gbp'])
                    ." WHEN \"EUR\" THEN ".$maxprice." END");
                 $this->template_obj->setVariable(array('maxprice' => $maxprice));
            }

            if ($sleeps) {
                $this->db_obj->whereAdd('sleeps >= '.$sleeps);
            }

            if ($proptype) {
                $this->db_obj->whereAdd("proptype = '".$proptype."'");
            }

            if ($sale) {
                $this->words['booking'] = '';
                $this->words['week'] = '';
                $this->words['index'] = '';
                //$this->db_obj->whereAdd("sale = 1");
            }



            $this->db_obj->addPropType();
            $this->db_obj->preGenerateList($this->lang);
            $this->db_obj->whereAdd("(status_id = 2 OR status_id = 3)");


        if (!$this->map) {
            $maplink = '<a href="?map"><h2>'.$this->words['map'].'</h2>';
            $links =  DVS_Table::createPager($this->db_obj);

            $this->db_obj->orderBy('src_site');
            $this->db_obj->orderBy('id DESC');
            $this->db_obj->orderBy('villarentersindex DESC');
            $this->db_obj->find();

            while ($this->db_obj->fetch()) {
                $this->template_obj->setVariable(array(
                'LINK'      => $this->lang_url.'/villa/'.$this->db_obj->id.'.html',
                'VILLA_ID' => $this->db_obj->id,
                'TITLE' => $this->db_obj->getLocalField('title', $this->lang),
                'SUMMARY' => $this->db_obj->getShortSummary(),
                'PROPTYPE' => $this->db_obj->words['rent'].': '.$this->db_obj->propTypeName($this->db_obj->proptype, $this->lang),
                'SLEEPS_NUM' => $this->db_obj->sleeps,
                'Rating' => $sale ? '' : '- '.$this->db_obj->villarentersindex,
                'MIN_PRICE' => $this->db_obj->minprice,
                'MAX_PRICE' => $sale ? '' : '- '.$this->db_obj->maxprice,
                'pay_period' => $sale ? '' : '/ '.$this->db_obj->getPay_periodName($this->lang),
                'CURRENCY' => $this->db_obj->currency,
                'M_PHOTO_SRC' => $this->db_obj->imageURL().$this->db_obj->main_image
                ));
                $this->template_obj->parse('VILLA');
            }
        }// map
        else {
            $page_arr['BODY_EVENT']   = ' onload="initialize()"';
            $this->db_obj->find(true);
            $this->showMap();
            $maplink = '<a href="'.$this->lang_url.'/regions/'.$_GET['alias'].'"><h2>'.$this->words['list'].'</h2>';
        }

        //$this->showTypeNav();
        $this->setTitles();

            if ($this->db_obj->location_id) {
                $this->showRegions();
                //$this->showMap();
            }

            $this->template_obj->setVariable(array(
                'CNT' => $links['cnt'] ? $this->words['in_all'].': '.$links['cnt'] : '',
                'PAGES'      =>     $links['all'],
                'SEARCH_FORM' => $search_form,
                'MAP_LINK' => $maplink,
                'HA_LINK' => $this->lang == 'ru' ? '<noindex><a href="http://hotels.rurenter.ru" rel="nofollow">Поиск отелей</a></noindex>' : '<noindex><a href="http://hotels.rurenter.ru" rel="nofollow">Hotels</a></noindex>',
                //'HA_LINK' => $this->lang == 'ru' ? '<a href="http://holidayaway.ru'.$_SERVER['REQUEST_URI'].'">Еще виллы в аренду...</a>' : '',
            ));
        $this->template_obj->setGlobalVariable($this->words);

        //$this->template_obj->setVariable(array('TICKETS' => file_get_contents(PROJECT_ROOT.'tmpl/aviasales.tpl').file_get_contents(PROJECT_ROOT.'tmpl/hotels.tpl')));


        $page_arr['CENTER_TITLE'] = $this->db_obj->words['villa_rentals'].': '.$this->db_obj->region_title;
        $page_arr['PAGE_TITLE'] = $this->db_obj->words['villa_rentals'].' - '.$this->db_obj->region_title_t.' - '.$this->words['page'].
            ($_GET['_p'] ? $_GET['_p'] : 1).' - '.$this->layout_obj->project_title;
        
        $page_arr['DESCRIPTION'] = $this->db_obj->words['villa_rentals'].' '.$this->db_obj->region_title_t.', '.$this->layout_obj->description;

        //$page_arr['CENTER_TITLE']   = $this->db_obj->name;
        //$page_arr['BODY_EVENT']   = ' onload="initialize()"';
        $page_arr['BODY_CLASS']   = 'search-page';
        //$page_arr['CENTER']         = file_get_contents(PROJECT_ROOT.'tmpl/sidebar.tpl').
        $page_arr['CENTER']         = $this->template_obj->get();
        //$page_arr['JSCRIPT']        = $this->datePicker2();
         $page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        return $page_arr;
    }

    function showLocation()
    {
        $countries_obj = DB_DataObject::factory('countries');
        $link = ($this->lang != 'ru' ? $this->lang.'/' : '').'regions/'.($this->map ? '?map' : '');
        $countries_obj->getParent(DVS::getVar('loc'), $link, $this->lang);
        return $countries_obj->region_title;
    }

    function showRegions()
    {
        $this->template_obj->addBlockFile('REGIONS', 'REGIONS', 'region.tpl');
        $proptype = DVS::getVar('ptype');
        
        $countries_obj = DB_DataObject::factory('countries');
        $countries_obj->getList($this->db_obj->location_id, $this->db_obj->getPropGroup($proptype));
        /*
        $countries_obj->parent_id = $this->db_obj->location_id;
        $countries_obj->orderBy('rus_name');
        $countries_obj->find();
        */
        $map_link = $this->map ? '?map' : '';
            while ($countries_obj->fetch()) {
                if ($countries_obj->counter) {
                    $this->template_obj->setVariable(array(
                    //'COUNTRY_LINK'  => '?op=villa&act=index&loc='.$сountries_obj->id,
                    'COUNTRY_LINK'  => '/regions/'.urlencode($countries_obj->getAlias()).'/'.$map_link,
                    'COUNTRY_NAME' => $countries_obj->getLocalName($this->lang),
                    'COUNTRY_CNT' => $countries_obj->counter,
                    ));
                    $i++;
                    $this->template_obj->parse('COUNTRY_ROW');
                }
            }
            $this->template_obj->parse('REGIONS');
            $countries_obj->getParent($this->db_obj->location_id, $map_link, $this->lang);
            $this->template_obj->setVariable(array('LOCATION_NAV' => $countries_obj->region_title));
    }


    function datePicker2()
    {
        $this->src_files = array(
            'JS_SRC' => array(  '/js/jquery-1.3.1.min.js',
                                '/js/datePicker2.js',
                                '/js/date.js',
        ),
            'CSS_SRC'  => array('/css/datePicker.css',
                                '/css/demo1.css',
                                //'/datepicker/css/layout.css',
        )
        );
        //$date_str = $this->showBook();
        //$page_arr['JSCRIPT'] = "

        $js = "
            $(function()
            {
                $('.datepicker').datePicker();
            });";
          return $js;
    }

    function showMap()
    {
        $this->src_files = array(
            'JS_SRC' => array("http://maps.google.com/maps/api/js?sensor=false")
            );
        $this->page_arr['JSCRIPT'] = 'function initialize() {
                    var latlng = new google.maps.LatLng('.$this->db_obj->lat.', '.$this->db_obj->lon.');
                    var myOptions = {
                      zoom: 12,
                      scrollwheel: false,
                      center: latlng,
                      language: "ru",
                      mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    var map = new google.maps.Map(document.getElementById("g-map"), myOptions);
                    var georssLayer = new google.maps.KmlLayer(\''.SERVER_URL.'/?op=villa&act=geo&alias='.$_GET['alias'].'\');
                    georssLayer.setMap(map);

                  }';
        $this->template_obj->touchBlock('LOCATION_MAP');
    }

    function showTypeNav()
    {
        $proptype = DVS::getVar('proptype');
        $this->db_obj->showTypeNav($this->template_obj, $proptype);
        /*
        $i = 0;
        foreach ($this->db_obj->prop_type_arr as $k => $v) {
            $i++;
                $this->template_obj->setVariable(array(
                    'TYPE_NAME' => $proptype == $k ?  "<b>$v</b>" : $v,
                    'TYPE_LINK' => '?proptype='.$k,
                    'TYPE_SEPARATOR' => $i == sizeof($this->db_obj->prop_type_arr) ? '' : ' | ',
                    'TYPE_ALT' => 'аренда '.$v.' без посредников',
                ));
                $this->template_obj->parse('TYPE_NAV');
        }
        */
    }

    function setTitles()
    {
        $this->template_obj->setVariable(array(
            'LIST_TITLE' => $this->words['list_title']));
    }

}

?>