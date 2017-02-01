<?php
/**
 * Карточка виллы
 * @package villarenters.ru
 * $Id: Villa_Show.php 536 2015-04-03 09:41:53Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Villa_Show extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ar' => 1, 'oc' => 1, 'ou' => 1, 'iu' => 1, 'mu' => 1,);

    private $change_content = true;

    private $lang_ru = array(
        'Rates' => 'Цены',
        'sale' => 'Продажа',
        'Rates_sale' => 'Цена',
        'Sleeps' => 'спальных мест:',
        'details' => 'Подробнее...',
        'booking' => 'Забронировать',
        'Bathrooms' => 'Ванная комната',
        'Further_details' => 'Подробности',
        'Owner' => 'Владелец',
        'rent' => 'Аренда',
        'type' => 'Тип',
        'prop_num' => 'Номер объекта',
        'price_in_other' => 'Цена в других валютах<br>(авто конвертация)',
        'reviews' => 'Отзывы',
        'book' => 'Забронировать',
        'views' => 'Просмотров',
        //'Top' => 'Наверх',
        //'Send query' => 'Задать вопрос'
    );

    private $lang_en = array(
        'Rates' => 'Rates',
        'sale' => 'Sale',
        'Rates_sale' => 'Price',
        //'week' => 'в неделю',
        'Sleeps' => 'Sleeps:',
        //'Rooms' => 'Комнат:',
        'details' => 'Details...',
        'booking' => 'Book',
        'Bathrooms' => 'Bathrooms',
        //'Facilities' => 'Услуги',
        'Further_details' => 'Further_details',
        'Owner' => 'Owner',
        'rent' => 'Rent',
        'type' => 'Type',
        'prop_num' => 'property ID',
        'price_in_other' => 'Price in other currencies',
        'reviews' => 'Reviews',
        'book' => 'Воок this property',
        'lang' => '/en',
        'views' => 'Views',

        //'Top' => 'Наверх',
        //'Send query' => 'Задать вопрос'
    );

    function getPageData()
    {        

        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        /*
        if ($this->db_obj->status_id != 2 && $this->iface == 'i') {
            $this->show404();
            $this->nocache = true;
            return;
        }
        */
        $this->createTemplateObj();
        $this->words = $this->{'lang_'.$this->lang};
        //$nav = $this->showNavigation();

        $nav = $this->db_obj->showNavigation($this->template_obj, $this->lang);
        
        //$template_name = 'villa_show.tpl';

        if ($this->role != 'mu') {
            $template_name = 'villa_show.tpl';
        } else {
            $template_name = 'villa_show_m.tpl';
        }


        if ($this->db_obj->sale) {
            $template_name = 'villa_show_sale.tpl';
        }

        $this->template_obj->loadTemplateFile($template_name);
        
            $this->template_obj->setVariable(array(
            'VILLA_ID' => $this->db_obj->id,
            'TITLE' => $this->db_obj->getLocalField('title', $this->lang),
            'SUMMARY' => nl2br($this->db_obj->summary_rus),
            'ADDRESS' => $this->db_obj->address,
            'PROPTYPE' => $this->db_obj->propTypeName($this->db_obj->proptype, $this->lang),
            'Rooms' => $this->db_obj->rooms ? 'Комнат:' : '',
            'ROOMS_NUM' => $this->db_obj->rooms ? $this->db_obj->rooms : '',
            'SLEEPS_NUM' => $this->db_obj->sleeps,
            'pay_period' => $this->db_obj->getPay_periodName($this->lang),
            'MIN_PRICE' => $this->db_obj->minprice,
            'MAX_PRICE' => $this->db_obj->maxprice,
            'CURRENCY' => $this->db_obj->currency,
            'COMMENTS_CNT' => $this->commentsCnt(),
            'RATING' => $this->db_obj->villarentersindex,
            'LOCATION_DESCRIPTION' => $this->db_obj->extra_rus ? $this->db_obj->extra_rus : $this->db_obj->extra,
            'VILLA_NAV' => $nav,
            'EURO_PRICE' => $this->db_obj->euroPrice($this->db_obj->minprice).' - '.$this->db_obj->euroPrice($this->db_obj->maxprice),
            'USD_PRICE' => $this->db_obj->usdPrice($this->db_obj->minprice).' - '.$this->db_obj->usdPrice($this->db_obj->maxprice),
            'USER_INFO' => $this-> userInfo(),
            'COUNTER' => $this->db_obj->counter,
            ));

        $this->showDescriptions();
        
        //$this->showUserOptions();
        $this->showOptions();
        $this->showImages();
        
        if ($this->db_obj->lat && $this->db_obj->lon) {
            $this->showMap();
        }
        //$this->showLinks();

        $this->template_obj->setGlobalVariable($this->words);

        //echo $this->iface.' '.$this->role;
        if ($this->role == 'oc') {
            $this->template_obj->setVariable(array(
            'EDIT_ID' => $this->db_obj->id,));
        }

        $this->counter();

        $page_arr['BREADCRUMB'] = $this->showLocation();
        $page_arr['BODY_CLASS']   = 'property';
        $page_arr['BODY_EVENT']   = ' onload="initialize()"';
        $page_arr['PAGE_TITLE'] = $this->db_obj->title_rus.' - '.$this->region_title_t.' - Про вилл - '.$this->layout_obj->project_title;
        $page_arr['CENTER_TITLE']   = $this->db_obj->name;
        $page_arr['CENTER']         = $this->template_obj->get();
        $page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        
        return $page_arr;
    }

    function commentsCnt()
    {
        $comments_obj = DB_DataObject::factory('comments');
        $comments_obj->villa_id = $this->db_obj->id;
        return $comments_obj->count();
    }

    function showLocation()
    {
        $countries_obj = DB_DataObject::factory('countries');
        $countries_obj->getParent($this->db_obj->location, '', $this->lang);
        $this->region_title_t = $countries_obj->region_title_t;
        return $countries_obj->region_title;
    }

    function showDescriptions()
    {
        $this->template_obj->setVariable(array('ZAG' => ''));
        $this->template_obj->parse('ROW');
        
        $description_obj = DB_DataObject::factory('descriptions');
        $description_obj->villa_id = $this->db_obj->id;
        $description_obj->find();
        
        while ($description_obj->fetch()) {
            $this->template_obj->setVariable(array(
                    'DESCRIPTION_TITLE' => $description_obj->title_rus ? $description_obj->title_rus : $description_obj->title,
                    'DESCRIPTION_BODY' => $description_obj->body_rus ? $description_obj->body_rus : $description_obj->body,
                ));
            $this->template_obj->parse('DESRIPTION');
        }
    }


    function showUserOptions()
    {
        //$this->template_obj->setVariable(array('LOCATION_TITLE' => 'Окрестности'));
        //$this->template_obj->parse('ROW');

        $user_options_obj = DB_DataObject::factory('user_options');
        $user_options_obj->villa_id = $this->db_obj->id;
        $user_options_obj->find();
        while ($user_options_obj->fetch()) {
                $this->template_obj->setVariable(array(
                    'LOCATION_NAME' => $user_options_obj->user_location,
                     'DISTANCE' => $user_options_obj->distance,
                    ));
            $this->template_obj->parse('USER_LOCATION');
        }
    }

    function showImages()
    {
        $images_obj = DB_DataObject::factory('images');
        $images_obj->villa_id = $this->db_obj->id;
        $images_obj->orderBy('id');
        $images_obj->find();
        $i = 1;
        while ($images_obj->fetch()) {
            //if (str_replace("200_150", "400_300", $this->db_obj->main_image) == $images_obj->file_name) {
            if ($i == 1) {
                    $this->template_obj->setVariable(array(
                                'M_PHOTO_SRC' => $this->db_obj->imageURL().$images_obj->file_name,
                                'M_ALT_TEXT' => $images_obj->caption,
                                'M_CAPTION' => $images_obj->caption,
                                ));
            }
            $this->template_obj->setVariable(array(
                'PHOTO_SRC' => $this->db_obj->imageURL().$images_obj->file_name,
                'ALT_TEXT' => $images_obj->caption,
                'CAPTION' => $images_obj->caption,
            ));
            $this->template_obj->parse('GALLERY_PHOTO');
            $i++;
            if ($i%2) {
                $this->template_obj->parse('GALLERY_ROW');
            }
            //$this->template_obj->setVariable(array('CONTENT_PATH' => '/'.$images_obj->file_name, 'ALT_LABEL' => $images_obj->caption));
            //$this->template_obj->parse('ROW');
        }
    }

    function showOptions()
    {
        //DB_DataObject::DebugLevel(1);
        $villa_opt_obj = DB_DataObject::factory('villa_options');
        $villa_opt_obj->villa_id = $this->db_obj->id;

        $option_groups_obj = DB_DataObject::factory('option_groups');
        //$option_groups_obj->selectAs(array('name', 'rus_name'), 'group_%s', 'option_groups');

        $options_obj = DB_DataObject::factory('options');
        $options_obj->joinAdd($option_groups_obj);
        $villa_opt_obj->joinAdd($options_obj);
        $villa_opt_obj->selectAs();
        $villa_opt_obj->selectAs(array('id', 'name', 'rus_name'), '%s', 'options');
        $villa_opt_obj->selectAs(array('id', 'name', 'rus_name'), '%s_group', 'option_groups');
        $villa_opt_obj->orderBy('name_group');
        $villa_opt_obj->find();

        $cur_group = '';
        while ($villa_opt_obj->fetch()) {
            //$this->template_obj->setVariable(array('KEY' => $villa_opt_obj->group_name.' '.$villa_opt_obj->name, 'VAL' => $villa_opt_obj->value));
            if ($cur_group != $villa_opt_obj->name_group) {
                $this->template_obj->parse('OPTION_COL');
                $this->template_obj->parse('OPTION_GROUP');
            }
            
            if ($villa_opt_obj->value != 0) {
                $this->template_obj->setVariable(array('GROUP_NAME' => $villa_opt_obj->getLocalField('name_group', $this->lang)));
                $this->template_obj->setVariable(array('OPTION' => $villa_opt_obj->getLocalField('name', $this->lang).' '.($villa_opt_obj->value != 1 ? $villa_opt_obj->value : '')));
                $this->template_obj->parse('OPTION');
            }

            $options_arr[$villa_opt_obj->group_name][$villa_opt_obj->name] = $villa_opt_obj->value;
            $cur_group = $villa_opt_obj->name_group;
            if ($villa_opt_obj->id == 5) {
                $this->template_obj->setVariable(array('BATH' => $villa_opt_obj->value));
            }

            if ($villa_opt_obj->group_id == 4 && $villa_opt_obj->value != 0) {
                $facilities .= $villa_opt_obj->rus_name.'<br>';
            }

        }
        
        //$this->template_obj->setVariable(array('FACILITY' => $facilities));

    }

    function showLinks()
    {

      $this->template_obj->setVariable(array('LINK' => $this->db_obj->qs.'&act=book&id='.$this->db_obj->id, 'LINK_NAME' => 'Бронировать'));
            $this->template_obj->parse('LINK');

      $this->template_obj->setVariable(array('LINK' => '?op=comments&act=show&villa_id='.$this->db_obj->id, 
          'LINK_NAME' => 'Отзывы'));
            $this->template_obj->parse('LINK');


        $this->template_obj->parse('ROW');
    }

    function showNavigation()
    {
        //$this->template_obj->addBlockFile('VILLA_NAV', 'VILLA_NAV', 'villa_nav.tpl');
        if ($this->lang != 'ru') {
            $arr = $this->{'nav_arr_'.$this->lang};
        }
        if (!$arr) {
            $arr = $this->nav_arr;
        }

        $this->template_obj->loadTemplateFile('villa_nav.tpl');
        $i = 1;
        foreach ($arr as $link => $text) {
            $link = str_replace('{VILLA_ID}', $this->db_obj->id, $link);
            $this->template_obj->setVariable(array(
                'nav_link' => $link, 
                'nav_text' => $text,
                'nav_class' => $i == sizeof($arr) ? ' class=last' : ''
                )
            );
            $i++;
            //$this->template_obj->setVariable(array('VILLA_ID' => 1));
            $this->template_obj->parse('NAV_ITEM');
        }
        return $this->template_obj->get();
    }

    function showMap()
    {
        $this->src_files = array(
            'JS_SRC' => array("http://maps.google.com/maps/api/js?sensor=false")
            );
        $this->page_arr['JSCRIPT'] = '				  function initialize() {
					var latlng = new google.maps.LatLng('.$this->db_obj->lat.', '.$this->db_obj->lon.');
					var myOptions = {
					  zoom: 12,
                      scrollwheel: false,
					  center: latlng,
					  mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					var map = new google.maps.Map(document.getElementById("g-map"), myOptions);
                    var beachMarker = new google.maps.Marker({
      position: latlng,
      map: map
  });

				  }';
        $this->template_obj->touchBlock('LOCATION_MAP');
    }

    function userInfo()
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->db_obj->user_id);
        return $users_obj->id.', '.$users_obj->name;
    }

    function counter()
    {
        $this->db_obj->counter = $this->db_obj->counter + 1;
        $this->db_obj->update();
    }
}

?>