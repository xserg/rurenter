<?php
/**
 * Форма ввода вилл
 * @package rurenter
 * $Id: Villa_Form.php 484 2014-04-18 09:01:50Z xxserg $
 */


class DBO_Villa_Form extends DBO_Villa
{

    private $step;

    private $area_type = 1;

    private $editable = true;
/*
    private $form_help = array(
        1 => 'Введите адрес или название ближайшего населенного пункта, нажмите "Искать"',
        2 => 'Отметьте не более %s жилищных типов. Если не отмечен ни один тип, будут выбраны все адреса в заданной области',
        3 => ''
    );
*/
    private $price_cnt;

    //Вывод формы
    function getForm()
    {
        //echo '<pre>';
        //print_r($_POST);

        $this->src_files = array(
            'CSS_SRC'  => array(
            //'/office_ha_files/masthead.css',
            '/css/manage-listing.css'
            )
        );

        $this->step = DVS::getVar('step', 'int', 'post');
        if (!$this->sale) {
            $this->sale = DVS::getVar('sale', 'int');
        }
        if (empty($this->step)) {
            $this->step = DVS::getVar('step', 'int');
        }
        if (empty($this->step)) {
            $this->step = 1;
        }

        if (empty($this->user_id)) {
            $this->user_id = DVS::getVar('user_id', 'int');
        }

        if ($this->act == 'edit' && $_SESSION['_authsession']['data']['role_id'] != 'aa') {
            $prices_obj = DB_DataObject::factory('prices');
            $this->price_cnt = $prices_obj->checkPrice($this->id);
            if (!$this->price_cnt && !$this->sale) {
                $this->qs = '?op=booking&act=show&mode=price&villa_id='.$this->id;
                $_SESSION['message'] = 'Введите цены!';
                header('Location: '.$this->qs);
            }
        }

        $form =& new HTML_QuickForm('villa_form', 'post', $_SERVER['REQUEST_URI']);
        $form->addElement('hidden', 'sale');
        if ($this->sale) {
            $form->SetConstants(array('sale' => 1));
        }
        $form->addElement('hidden', 'step');
        $form->addElement('hidden', 'user_id', $this->user_id);
        $this->renderer_obj->_tpl->addBlockFile('tabs', 'tabs_group', 'tabs.tpl');

        $this->center_title .= ' / '.$this->title;
        //$this->renderer_obj->setElementBlock('tabs', 'tabs_group');

        //$form->addElement('header', null, ($this->act == 'new' ? 'Добавить' : 'Редактировать').' '.$this->head_form);

        if (!$form_error) {
            if ($_POST['next']) {
                $this->step += 1;
            } else if ($_POST['prev']) {
                $this->step -= 1;
            }
            //echo $this->step;
            //$form->addElement('header', null, $this->form_headers[$this->step]);
            
            $this->formTabs($this->renderer_obj->_tpl, $this->step);
            /*
            if ($this->act == 'edit' && $this->status_id > 1 &&  $this->step > 1) {
                $this->editable = false;
                echo DVS_ERROR_FORBIDDEN5;
                exit;
            }
            */
            $this->{'getFormStep'.$this->step}(&$form);
        }
        $this->setPost($form);
        $this->addButtons($form);
        return $form;
    }

    /**
     * Передача параметров между шагами формы
     * @param HTML_QuickForm object $form
     */
    function setPost($form)
    {
        $props = array(
            'user_id',
            'title_rus',
            'summary_rus',
            'address',
            'location',
            'countryId',
            'regionId',
            'cityId',
            'minprice',
            'maxprice',
            'currency',
            'sleeps',
            'proptype',
            'option',
            'lon',
            'lat',
            'full_adr',
            'rooms',
            'pay_period',
        );
        if ($_POST) {
            foreach ($_POST as $k => $v) {
                if ($v && in_array($k, $props)) {
                    $this->addHidden($form, $k, $v);
                }
            }
        }
    }

    function addHidden($form, $k, $v)
    {
        if (is_array($v)) {
            foreach ($v as $ak => $av) {
                $form->addElement('hidden', $k.'['.$ak.']', $av);
            }
        } else {
            $form->addElement('hidden', $k, $v);
        }
    }

    /**
     * Кнопки формы
     * @param HTML_QuickForm object $form
     */
    function addButtons($form)
    {
        if ($this->buttons == 'disable') {
            return;
        }
        $prev = HTML_QuickForm::createElement('submit', 'prev', '<< '.DVS_PREV);
        $next = HTML_QuickForm::createElement('submit', 'next', DVS_NEXT.' >>', array('id' => 'btnNext'));
        $save = HTML_QuickForm::createElement('submit', '__submit__', DVS_SAVE);
        switch ($this->buttons) {
            case 'prev_next':
                $form->addGroup(array($prev, $next), '', '', '&nbsp;');
                break;
            case 'save_next':
                $form->addGroup(array($save, $next), '', '', '&nbsp;');
                break;
            case 'prev_save':
                $form->addGroup(array($prev, $save), '', '', '&nbsp;');
                break;
            case 'prev_save_next':
                $form->addGroup(array($prev, $save, $next), '', '', '&nbsp;');
                break;            
            case 'save':
                $form->addElement('submit', '__submit__', DVS_SAVE);
                break;

            default:
            case 'next':
                $form->addElement('submit', 'next', DVS_NEXT.' >>');
                break;

        }
    }

    function getFormStep2($form)
    {   
        //echo '<pre>';
        //print_r($_POST);
        if ($this->act == 'edit') {
            $this->buttons = 'prev_save_next';
            $form->setDefaults(array('currency2' => $this->currency));
        } else {
            $this->buttons = 'prev_next';
        }
        //$this->buttons = 'next';
        $form->SetConstants(array('step' => 2));

        //$form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        $form->addElement('text', 'address', $this->fb_fieldLabels['address'], 'size=50');
        $form->addElement('text', 'title_rus', $this->fb_fieldLabels['title'], 'size=50');
        $form->addElement('textarea', 'summary_rus', $this->fb_fieldLabels['summary'], array('rows' => 20, 'cols' => 97));
/*
        $countries_obj = DB_DataObject::factory('countries');
        $countries_arr = $countries_obj->selArray();
        $form->addElement('select', 'countryId', 'Страна', $countries_arr, array('style' => 'width:100%;', 'id' => 'countryId'));
        $form->addElement('select', 'regionId', 'Регион', array(), array('style' => 'width:100%;', 'id' => 'regionId'));
        $form->addElement('select', 'cityId', 'Город', array(), array('style' => 'width:100%;', 'id' => 'cityId'));
*/

        $t4 = $form->createElement('select', 'pay_period', '', $this->pay_period_arr);
        $t1 = $form->createElement('text', 'minprice', '', 'size=5');
        $t2 = $form->createElement('text', 'maxprice', '', 'size=5');

        $t3 = $form->createElement('select', 'currency', '', $this->currency_arr);

        $t5 = $form->createElement('select', 'currency2', '', $this->currency_arr);

        $form->addElement('select', 'rooms', $this->fb_fieldLabels['rooms'],  array('' => '') + $this->rooms_arr);

        $form->addElement('select', 'sleeps', $this->fb_fieldLabels['sleeps'],  array('' => '') + $this->sleeps_arr);
        $form->addElement('select', 'proptype', $this->fb_fieldLabels['proptype'], array('' => '') + $this->getLocalArr('prop_type_arr', $this->lang));

        if (!$this->sale) {
        $form->addGroup(array($t4, $t1, $t2, $t3), 'pr', $this->fb_fieldLabels['price'], '', false);
        $form->addGroupRule('pr', array(
            'minprice' => array(
                //array('Name is letters only', 'lettersonly'),
                array(DVS_REQUIRED.' '.$this->fb_fieldLabels['price'].' !', 'required', null, 'client'),
                //array(DVS_REQUIRED.' '.$this->fb_fieldLabels['price'].' !', 'nonzero', null, 'client'),
                //array(DVS_REQUIRED.' '.$this->fb_fieldLabels['price'].' !', 'numeric', null, 'client'),
                ),
            'maxprice' => array(
                //array('Name is letters only', 'lettersonly'),
                array(DVS_REQUIRED.' '.$this->fb_fieldLabels['price'].' !', 'required', null, 'client'),
                //array(DVS_REQUIRED.' '.$this->fb_fieldLabels['price'].' !', 'nonzero', null, 'client'),
                //array(DVS_REQUIRED.' '.$this->fb_fieldLabels['price'].' !', 'numeric', null, 'client'),
                ),

            ));
        } else {
             $form->addGroup(array($t1, $t3), 'pr', $this->fb_fieldLabels['price'], '', false);
        }
        
        $b1 = $form->createElement('text', 'breakage');
        $form->addGroup(array($b1, $t5), 'br', $this->fb_fieldLabels['breakage'], '', false);
        
        if ($_SESSION['_authsession']['data']['role_id'] == 'aa') {
            $form->addElement('select', 'status_id', 'Статус:', $this->status_arr);
            $form->addElement('advcheckbox', 'special_offer', '', 'На главной');
        }

        $form->addRule('pr', DVS_REQUIRED.' '.$this->fb_fieldLabels['price'].'!', 'required', null, 'client');
        $form->freeze('address');

        $form->addRule('address', DVS_REQUIRED.' '.$this->fb_fieldLabels['address'].'!', 'required', null, 'client');
        $form->addRule('title_rus', DVS_REQUIRED.' '.$this->fb_fieldLabels['title'].'!', 'required', null, 'client');
        $form->addRule('summary_rus', DVS_REQUIRED.' '.$this->fb_fieldLabels['summary'].'!', 'required', null, 'client');
        $form->addRule('sleeps', DVS_REQUIRED.' '.$this->fb_fieldLabels['sleeps'].'!', 'required', null, 'client');
        $form->addRule('proptype', DVS_REQUIRED.' '.$this->fb_fieldLabels['proptype'].'!', 'required', null, 'client');
        


        /*
                $this->src_files = array(
            'JS_SRC' => array(  '/js/jquery.js',
                                '/js/geo.js',
        ));
        */

    }

    function getFormStep3($form)
    {
        $form->SetConstants(array('step' => 3));
        if ($this->act == 'edit') {
            $villa_options_obj = DB_DataObject::factory('villa_options');
            $option = $villa_options_obj->getOptions($this->id);
            //print_r($option);

            if ($option && !$_POST['web_group']) {
                $form->SetConstants(array('option' => $option));
            }
        }
        $this->buttons = 'prev_next';
        //$form->addElement('header', null, $this->form_headers[$this->step]);
        $this->renderer_obj->setElementBlock('options', 'options_group');

        $this->options_obj = DB_DataObject::factory('options');
        $this->options_obj->optForm($form, $this->lang);

    }

    /**
     * Фото
     */
    function getFormStep4($form)
    {
        $this->buttons = 'prev_save';
        //$this->buttons = 'prev_next';
        //$form->addElement('header', null, $this->form_headers[$this->step]);
        $form->SetConstants(array('step' => 4));
        //$this->images_obj = DB_DataObject::factory('images');
        $this->images_obj = DVS_Dynamic::createDbObj('images');
        $this->images_obj->getFormContent($form);
        if ($this->act == 'edit') {
            $this->renderer_obj->_tpl->addBlockFile('footer', 'GALLERY', 'form_photo.tpl');
            $this->images_obj->imagesForm($this->renderer_obj->_tpl, $this->id, $this->main_image);
        } else {
            $form->setDefaults(array('main' => 1));
        }
    }

    /**
     * Форма выбора области на карте
     */
    function getFormStep11($form)
    {
        //$this->buttons = 'prev_save';
        //$this->buttons = 'prev_next';
        //$this->buttons = 'next';
        $this->buttons = 'disable';
        $form->SetConstants(array('step' => 1));

        $disabled = array('id' => 'next');
        if ($this->act == 'new') {
            $disabled = array('id' => 'next', 'disabled');
            $this->lat = '55.755786';
            $this->lon = '37.617633';
        }
/*
        if ($this->act == 'edit') {
            
        } else {
            $this->lat = '55.755786';
            $this->lon = '37.617633';
        }
*/
        $this->src_files += array(
            'JS_SRC' => array(
                                "http://maps.google.com/maps/api/js?v=3.3&sensor=false",
                                //"http://maps.google.com/maps/api/js?v=3.3&sensor=false",
                                //'/js/gmap.js',
            )
        );

        $this->page_arr['BODY_EVENT'] = ' onload=initialize();';

        //$this->page_arr['BODY_CLASS'] = 'property';

        $this->page_arr['JSCRIPT'] .= "var map;
var marker;
var infowindow = new google.maps.InfoWindow();


function initialize() {
  var moscow = new google.maps.LatLng(".$this->lat.", ".$this->lon.");
  var mapOptions = {
    zoom: 12,
    center: moscow,
    scrollwheel: false,
    mapTypeControl: false,
    //disableDefaultUI: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  geocoder = new google.maps.Geocoder();
  map =  new google.maps.Map(document.getElementById(\"map_canvas\"), mapOptions);
  ";

        if ($this->act == 'edit') {
            $this->page_arr['JSCRIPT'] .= "
            marker = new google.maps.Marker({
                                    position: moscow,
                                    map: map
            });";
        }

$this->page_arr['JSCRIPT'] .="}
var clickListener;
function choosePosition() {
  clickListener = google.maps.event.addListener(map, 'click', function(event) {
    deleteMarker();
    addMarker(event.latLng);
  });
  map.setOptions({draggableCursor: \"crosshair\"});
}


function addMarker(location) {
  marker = new google.maps.Marker({
    position: location,
    map: map
  });
    codeLatLng(location);
    document.getElementById(\"lat\").value = location.lat();
    document.getElementById(\"lon\").value = location.lng();
}


function deleteMarker() {
  if (marker) {
      marker.setMap(null);
      infowindow.close();
  }
}

function codeAddress() {
    var address = document.getElementById(\"address\").value;
    if (geocoder) {
      geocoder.geocode( {   'address': address,
                            'language': 'ru'
                         }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
         map.setCenter(results[0].geometry.location);
         deleteMarker();

         //google.maps.event.removeListener(clickListener);
         
         map.setOptions({draggableCursor: \"hand\"});
          marker = new google.maps.Marker({
              map: map, 
              position: results[0].geometry.location
          });
            setResJson(address,results);
            document.getElementById(\"lat\").value = results[0].geometry.location.lat();
            document.getElementById(\"lon\").value = results[0].geometry.location.lng();
            infowindow.setContent('".$this->form_help[5]."');
            infowindow.open(map, marker);
        } else {
          alert(\"Geocode RU was not successful for the following reason: \" + status);
        }
      });
    }
  }

  var full_adr;
  function setResJson(address, results) {
            var separator='';
            var types = '\"types\":[';
            var long_name = '\"long_name\":[';
            for (i = 0; i < results[0].address_components.length; i++) {
                separator = i > 0 ? ',' : '';
                types += separator+'\"'+results[0].address_components[i][\"types\"]+'\"';
                long_name += separator+'\"'+results[0].address_components[i][\"long_name\"]+'\"';
            }
            full_adr ='{'+types+'],'+long_name+'],\"location\":['+results[0].geometry.location.lat()+','+results[0].geometry.location.lng()+']}';
            //alert(full_adr);
            document.getElementById(\"full_adr\").value = full_adr;
            document.getElementById(\"address\").value = results[0].formatted_address;
            document.getElementById(\"next\").disabled = false;
  }

  function codeLatLng(location) {
    geocoder.geocode({'latLng': location, 'language': 'ru'}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
            setResJson(results[0].formatted_address, results);
        }
      } else {
        alert(\"Geocoder failed due to: \" + status);
      }
    });
  }
";

        /*
        $s1 = $form->createElement('text', 'address', '', array('id'=> 'address','size'=> 70));
        $s2 = $form->createElement('button', '', $this->form_help[2], 'onclick="codeAddress()"');
        $form->addGroup(array($s1, $s2), 'adr', $this->fb_fieldLabels['address'], '', false);
        */
        $form->addElement('text', 'address', $this->fb_fieldLabels['address'], array('id'=> 'address','size'=> 70));

        $s3 = $form->createElement('button', '', $this->form_help[3], 'onclick="choosePosition();"');
        
        $s4 = $form->createElement('submit', 'next', DVS_NEXT.' >>', $disabled);

        if ($this->act == 'edit') {
            $s5 = $form->createElement('submit', '__submit__', DVS_SAVE);
            $form->addGroup(array($s3, $s5, $s4), '', '', '', false);
        } else {
            $form->addGroup(array($s3, $s4), '', '', '', false);

        }

        $form->addElement('static', 'map', '<br><div align=center id="map_canvas" style="width:600px; height:400px;"></div>');

        /*
        $form->addRule('adr', DVS_REQUIRED, 'required', null);
        $form->addGroupRule('adr', array(
        'address' => array(
                //array('Name is letters only', 'lettersonly'),
                array(DVS_REQUIRED.' Адрес!', 'required', null, 'client')
            ),
        ));
        */
        $form->addElement('hidden', 'lat', '', array('id' => 'lat'));
        $form->addElement('hidden', 'lon', '', array('id' => 'lon'));
        $form->addElement('hidden', 'full_adr', '', array('id' => 'full_adr'));
        //$form->addElement('hidden', 'full_adr_en', '', array('id' => 'full_adr_en'));

    }

    function dateOptions()
    {
        return array('language' => 'ru',
                    'format'=>'d-F-Y',
                    'minYear'=>date("Y"),
                    'maxYear'=>date("Y")+2,
                    'addEmptyOption' => true
        );
    }


    function englishName($location, $lang='en')
    {
        $json = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?v=3.3&language='.$lang.'&sensor=false&latlng='.$location,'r');
        if ($json) {
            $results = json_decode($json);
            //print_r($results);
            return $results->results[0]->address_components;
        }
    }



    function findLocation($vals)
    {
        $adr_arr = json_decode(utf8_encode($vals['full_adr']));
        if (!$adr_arr) {
            return false;
        }
        if (!$vals['lat'] || !$vals['lon']) {
            $vals['lat'] = str_replace(',', '.', $adr_arr->location[0]);
            $vals['lon'] = str_replace(',', '.', $adr_arr->location[1]);
        }

        $long_name_en = $this->englishName($vals['lat'].','.$vals['lon'], 'en');
        $long_name_ru = $this->englishName($vals['lat'].','.$vals['lon'], 'ru');

        $vals['locations_all'] = ':';
        $parent_id = '';

        foreach (array_reverse($long_name_en, 1) as $k => $v) {
               $rus_name = iconv("UTF-8", "Windows-1251", $long_name_ru[$k]->long_name);
                
                if (in_array("political", $v->types) && $v->long_name) {
                 if ($v->long_name == 'Russian Federation') {
                        $v->long_name = 'Russia';
                    }

                    if ($v == 'Israel' && $adr_arr->types[$k] == 'country,political') {
                        continue;
                    }


                            $countries_obj = DB_DataObject::factory('countries');
                            $countries_obj->whereAdd("name='".mysql_escape_string($v->long_name)."' OR rus_name='".mysql_escape_string($rus_name)."'");

                            if ($parent_id) {
                                $countries_obj->parent_id = $parent_id;
                                 //$vals['locations_all'] .= $countries_obj->parent_id.':';
                            }
                            $countries_obj->find(true);
                            if (!$countries_obj->N) {
                                if ($parent_id) {
                                    $countries_obj->name = $v->long_name;
                                    $countries_obj->rus_name = $rus_name;
                                    $countries_obj->insert();
                                    //echo "Inserted \n";
                                }
                            } else {
                                if (!$parent_id && $countries_obj->parent_id) {
                                    $vals['locations_all'] .= $countries_obj->parent_id.':';
                                }
                            }
                            //echo $countries_obj->rus_name.' '.$countries_obj->id."\n";
                            $parent_id=$countries_obj->id;
                            $vals['locations_all'] .= $countries_obj->id.':';
                }
        }
        $vals['location'] = $countries_obj->id;
    }

    function preProcessForm(&$vals, &$fb)
    {
        /*
        echo '<pre>';
        print_r($vals);
        print_r($_POST);
        */
        
        //DB_DataObject::DebugLevel(1);
        //$this->findLocation(&$vals);
        
        //print_r($vals);
        //exit;
        if ($vals['location']) {
            $countries_obj = DB_DataObject::factory('countries');
            $countries_obj->getParent($vals['location']);
            $vals['locations_all'] = ':'.$countries_obj->locations_all.':';
        }
        if ($this->act == 'new') {
            /*
            $fb->userEditableFields = array(
                'advertiser_id',
            );
            */
            $fb->dateToDatabaseCallback = null;
            $vals['user_id']            = $_SESSION['_authsession']['data']['id'];
            $vals['add_date']           = date(DB_DATE_FORMAT);
            $vals['add_ip']             = DVS_Auth::getIP();
            $vals['status_id']          = 1;
            $vals['villarentersindex']           = 50;
            $vals['src_site']           = 'rurenter.ru';
        } else {
            
            $fb->userEditableFields = array(
            'title',
            'title_rus',
            'summary',
            'summary_rus',
            'address',
            'location',
            'locations_all',
            'minprice',
            'maxprice',
            'currency',
            'sleeps',
            'proptype',
            'option',
            'lon',
            'lat',
            'full_adr',
            'rooms',
            'pay_period',
            'breakage',
            );
            
            if ($_SESSION['_authsession']['data']['role_id'] == 'oc') {
                $vals['status_id'] = 3;
            }
            if ($_SESSION['_authsession']['data']['role_id'] != 'aa') {
                $vals['special_offer']           = $this->special_offer;
            } else {
                $fb->userEditableFields[] = 'status_id';
                $fb->userEditableFields[] = 'special_offer';
            }

        }
        $vals['title']          = $vals['title_rus'];
        $vals['summary']          = $vals['summary_rus'];

    }


    function saveOptions($villa_id, $options_arr)
    {
        //DB_DataObject::DebugLevel(1);
        $villa_options_obj = DB_DataObject::factory('villa_options');
        $villa_options_obj->villa_id = $villa_id;
        $villa_options_obj->delete();
        //print_r($table_conditions);
            if (is_array($options_arr)) {
                foreach ($options_arr as $k => $v) {
                    $villa_options_obj->option_id = $k;
                    $villa_options_obj->value = $v;
                    $villa_options_obj->insert();
                }
            }
    }

    
    /**
     * Пост обработка (проводится после сохранения записи кампании)
     * Ввод условий таргетинга, сохранение файлов рекламы, отправка письма администратору
     */
    function postProcessForm(&$vals, &$fb)
    {
        $this->qs = '?op=villa'.($this->sale ? '&sale=1' : '');
        //print_r($vals);
        if ($vals['option']) {
            $this->saveOptions($this->id, $vals['option']);
        }

        //if (isset($_FILES) && $this->act == 'new') {
        if ($_FILES) {
            $this->images_obj->saveContent($this->id, $vals);
            $this->main_image = $this->images_obj->main_image;
            $this->update();
        }

        if ($this->act == 'new') {
            $vals['subject'] = 'New villa '.$this->id;
            $this->updateCounters($vals['locations_all']);
        } else {
            $vals['subject'] = 'Update villa '.$this->id;

            if ($vals['editCaption'] || $vals['editMain']) {
                if (!$this->images_obj->main_image) {
                    $update_main = true;
                }
                $this->images_obj->editImages($vals);
                if ($update_main) {
                    $this->main_image = $this->images_obj->main_image;
                    $this->update();
                }
            }
            /*
            if ($vals['main']) {
                $this->main_image = $this->images_obj->main_image;
                $this->update();
            }
            */
        }

        if ($_SESSION['_authsession']['data']['role_id'] == 'oc') {
            $this->adminLetter($vals);
        }
        if (!$this->price_cnt && !$this->sale) {
            $this->qs = '?op=booking&act=show&mode=price&villa_id='.$this->id;
            $this->msg = 'Введите цены!';
        }
    }

    function adminLetter($vals)
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = MAIL_ADMIN;
        $data['subject'] = $vals['subject'];
        $data['subject'] = $vals['subject'];
        $data['content-type'] = 'text/html; charset="Windows-1251"';
        $data['body'] = $vals['subject']
            ."<br> № объекта <a href=\"".SERVER_URL."/villa/".$this->id.".html\">".$this->id.'</a>'.($this->sale ? '(ПРОДАЖА)' : '')
            ."<br> № Пользователя ".$this->user_id
            ."<br>Название".$this->title
            ."<br>".$vals['summary']."<br>"
            .'<a href="'.SERVER_URL.'/admin/?op=villa&act=activate&id='.$this->id.($this->sale ? '&sale=1' : '').'">Активировать</a>';

        DVS_Mail::send($data);
    }



    /**
     * Форма выбора региона
     */
    function getFormStep1($form)
    {
        $form->SetConstants(array('step' => 1));
        $this->buttons = 'disable';
        $this->src_files += array(
            'JS_SRC' => array(
                                
                                //"/js/jquery-1.10.2.js",
                                "/js/jquery-1.11.1.min.js",
                                "/js/jquery-ui-1.10.4.custom.min.js",
                                "http://maps.google.com/maps/api/js?v=3.3&sensor=false",
                                //"//code.jquery.com/jquery-1.10.2.js",
                                //"//code.jquery.com/ui/1.10.4/jquery-ui.js",
            ),
        );
        $this->src_files['CSS_SRC'][] = '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css';




        $this->page_arr['JSCRIPT'] = 
'
    $(function() {

    $( "#city" ).autocomplete(
    {
         source:"/?op=countries&act=Search&lang='.$this->lang.'",
          select: function( event, ui ) {
        
        document.getElementById("location").value = ui.item.id;';
        
        if ($this->act == 'new') {
            $this->page_arr['JSCRIPT'] .= '
        document.getElementById("address").value = ui.item.label;
        codeAddress();';
        }
        $this->page_arr['JSCRIPT'] .='}
    })

});';

        if ($this->act == 'edit') {
            /*
            $сountries_obj = DB_DataObject::factory('countries');
            $сountries_obj->database('xserg');
            $сountries_obj->get($this->location);
            */
            $this->city = $this->getCityName($this->location);
            /*
            $sql2 = 'select name, rus_name from xserg.countries where id='.$this->db_obj->parent_id;
            $сountries_obj->query($sql2);
            //$сountries_obj->fetch();
            //$this->city .= $сountries_obj->rus_name;
            */
            $form->setDefaults(array('city' => $this->city));
            //echo $this->city;
        }


        //$this->page_arr['BODY_EVENT'] = ' onload=initialize();';
        /*
        $s1 = $form->createElement('text', 'location', '', array('id'=> 'location','size'=> 70));
        $s2 = $form->createElement('button', '', $this->form_help[2]);
        $form->addGroup(array($s1, $s2), 'loc', $this->fb_fieldLabels['address'], '', false);
        */
        $form->addElement('static', 'help', $this->form_help[0]);
        $form->addElement('text', 'city', $this->fb_fieldLabels['city'], array('id'=> 'city','size'=> 70));
        $form->addElement('hidden', 'location', '', array('id' => 'location'));
        //$form->addElement('static', 'log', '<div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>');
         $form->addRule('city', DVS_REQUIRED, 'required', null);
         $form->addRule('location', DVS_REQUIRED, 'required', null);

        $this->getFormStep11($form);
    }

    function getCityName($id)
    {
            
            $сountries_obj = DB_DataObject::factory('countries');
            $сountries_obj->database('xserg');
            //$сountries_obj->get($id);
            
            $sql2 = 'select name, rus_name from countries where id='.$id;
            $сountries_obj->query($sql2);
            $сountries_obj->fetch();
            
            //$this->city .= $сountries_obj->rus_name;

            if (!$сountries_obj->name) {
                return false;
            }
            return $сountries_obj->rus_name ? $сountries_obj->rus_name : $сountries_obj->name;
    }
}