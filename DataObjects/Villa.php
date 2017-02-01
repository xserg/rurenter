<?php
/**
 * Table Definition for villa
 */
require_once 'DB/DataObject.php';

class DBO_Villa extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'villa';                           // table name
    public $_database = 'rurenter';                           // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $user_id;                         // int(4)  
    public $proptype;                        // varchar(255)  
    public $title;                           // varchar(255)  
    public $title_rus;                       // varchar(255)   not_null
    public $summary;                         // mediumtext()  
    public $summary_rus;                     // mediumtext()  
    public $filename;                        // varchar(255)  
    public $location;                        // int(4)  
    public $locations_all;                   // varchar(255)   not_null
    public $address;                         // tinytext()  
    public $minprice;                        // int(4)  
    public $maxprice;                        // int(4)  
    public $breakage;                        // int(4)  
    public $currency;                        // varchar(11)  
    public $sleeps;                          // int(4)  
    public $main_image;                      // varchar(255)   not_null
    public $lon;                             // float()   not_null
    public $lat;                             // float()   not_null
    public $maplink;                         // text()  
    public $extra;                           // text()  
    public $extra_rus;                       // text()  
    public $instantbooking;                  // tinyint(1)   not_null
    public $villarentersindex;               // int(4)   not_null
    public $special_offer;                   // tinyint(1)   not_null
    public $translation_status;              // tinyint(1)   not_null
    public $src_site;                        // varchar(255)   not_null
    public $add_date;                        // datetime()   not_null
    public $add_ip;                          // varchar(64)   not_null
    public $status_id;                       // int(4)   not_null
    public $rooms;                           // mediumint(3)   not_null
    public $pay_period;                      // smallint(2)   not_null default_1
    public $sale;                            // tinyint(1)   not_null
    public $counter;                         // int(4)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Villa',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'user_id' =>  DB_DATAOBJECT_INT,
             'proptype' =>  DB_DATAOBJECT_STR,
             'title' =>  DB_DATAOBJECT_STR,
             'title_rus' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'summary' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'summary_rus' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'filename' =>  DB_DATAOBJECT_STR,
             'location' =>  DB_DATAOBJECT_INT,
             'locations_all' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'address' =>  DB_DATAOBJECT_STR,
             'minprice' =>  DB_DATAOBJECT_INT,
             'maxprice' =>  DB_DATAOBJECT_INT,
             'breakage' =>  DB_DATAOBJECT_INT,
             'currency' =>  DB_DATAOBJECT_STR,
             'sleeps' =>  DB_DATAOBJECT_INT,
             'main_image' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'lon' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'lat' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'maplink' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'extra' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'extra_rus' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_TXT,
             'instantbooking' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
             'villarentersindex' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'special_offer' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
             'translation_status' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
             'src_site' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'add_date' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME + DB_DATAOBJECT_NOTNULL,
             'add_ip' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'status_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'rooms' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'pay_period' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'sale' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_BOOL + DB_DATAOBJECT_NOTNULL,
             'counter' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
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
            'id' => '',
            'main_image' => '',
            'user_id' => '',
            'title' =>  '',
            'status_id' =>'',
            //'summary' =>  '',
            //'prices' => '',
            //'book' => '',
    );

    public $qs_arr = array('loc', 'src_site', 'sale');

    public $prop_type_arr = array(
        'apartment' => 'апартамент',
        'flat'      => 'квартира',
        'villa'     => 'вилла',
        'cottage'   => 'коттедж',
        'dacha'     => 'дача'
    );

    public $prop_type_arr_en = array(
        'apartment' => 'apartment',
        'flat'      => 'flat',
        'villa'     => 'villa',
        'cottage'   => 'cottage',
        'dacha'     => 'dacha'
    );

    public $prop_type_group = array(
        'apartment' => array('apartment', 'flat', 'room/studio'),
        'villa'     => array('villa', 'cottage', 'house', 'chateau', 'country house')
    );

    public $rooms_arr= array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        10 => '10'
    );

    public $sleeps_arr = array(
        2 => '2',
        4 => '4',
        6 => '6',
        8 => '8',
        10 => '10',
        12 => '12',
        14 => '14',
        15 => '15',
        16 => '16',
        18 => '18',
        20 => '20',
        25 => '25',
        30 => '30',
        40 => '40',
    );

    public $status_arr = array(
        1 => 'Новый',
        2 => 'Активный',
        3 => 'Внесены изменения'
    );

    public $currency_arr = array('RUR' => 'Руб.', 'USD' => 'USD', 'EUR' => 'EUR');

    public $pay_period_arr = array(1 => 'в неделю', 2 => 'в сутки');

    public $pay_period_arr_en = array(1 => 'per week', 2 => 'per night');


    public $form_headers = array(
        1 => 'Расположение',
        2 => 'Описание',
        3 => 'Услуги',
        4 => 'Фото',
        5 => 'Цены',
        6 => 'Бронь',
    );

    protected $form_links = array(
        1 => '?op=villa&act=edit&step=1&id=',
        2 => '?op=villa&act=edit&step=2&id=',
        3 => '?op=villa&act=edit&step=3&id=',
        4 => '?op=villa&act=edit&step=4&id=',
        5 => '?op=booking&act=show&mode=price&villa_id=',
        6 => '?op=booking&act=show&mode=bookstat&villa_id=',
    );
/*
    public $prop_type_arr = array(
        'villa' => 'вилла',
        'apartment' => 'аппартамент',
        'cabin' => 'домик',
        'country house' => 'деревенский дом'
    );
*/
    public $default_sort = 'id DESC';

    public $search_arr = array('fields_search' => array('title', 'villa.id'));

    private $translation_status_arr = array( 0 => 'Нет', 1 => 'Новый', 2 => 'Проверен');

    public $perms_arr = array(
        'new' => array('oc' => 1),
        'list' => array('oc' => 1),
        'edit' => array('oc' => 1),
        'delete' => array('oc' => 1),
        //'card' => array('iu' => 1, 'oc' => 1),
    );

/*
    public $nav_arr = array(
        '#wrapper' => 'Наверх',
        '#photos-bar' => 'Фото и описания',
        '#facility-bar' => 'Услуги и оборудование',
        '#location-bar' => 'Расположение и окрестности',
        '/?op=villa&act=book&id={VILLA_ID}' => 'Цены и бронирование',
        '/?op=comments&act=show&villa_id={VILLA_ID}' => 'Отзывы',
        '/?op=query&act=new&villa_id={VILLA_ID}' => 'Задать вопрос'
    );
*/
    public $nav_arr = array(
        '#wrapper' => 'Наверх',
        '#photos-bar' => 'Фото и описания',
        '#facility-bar' => 'Услуги и оборудование',
        '#location-bar' => 'Расположение',
        '#map-bar' => 'Карта',
        '/?op=booking&act=user&villa_id={VILLA_ID}' => 'Цены и бронирование',
        '/?op=comments&act=show&villa_id={VILLA_ID}' => 'Отзывы',
        '/?op=query&act=new&villa_id={VILLA_ID}' => 'Задать вопрос'
    );
    private $nav_arr_en = array(
        '#wrapper' => 'Top',
        '#photos-bar' => 'Photos',
        '#facility-bar' => 'Facilities',
        '#location-bar' => 'Location',
        '#map-bar' => 'Map',
        '/en/?op=booking&act=user&villa_id={VILLA_ID}' => 'Price and avalability',
        '/en/?op=comments&act=show&villa_id={VILLA_ID}' => 'Reviews',
        '/en/?op=query&act=new&villa_id={VILLA_ID}' => 'Send query'
    );

    function tableRow()
    {

        return array(
            'id' =>  '<a href="?op=villa&act=show&id='.$this->id.'">'.$this->id.'</a>',
            'main_image' => '<a href="?op=villa&act=show&id='.$this->id.'"><img src='.self::imageURL().$this->main_image.'></a>',
            //'user_id' => '<a href="?op=villa&user_id='.$this->user_id.'">'.$this->user_id.'</a>',
            'title'      => '<a href="?op=villa&act=show&id='.$this->id.'">'.$this->title.'</a><br>'.substr($this->summary, 0, 64).'...',
            //'summary' => $this->summary,
            //'prices' => '<a href="?op=booking&act=show&mode=price&villa_id='.$this->id.'">Цены</a>',
            //'book' => '<a href="?op=booking&act=show&mode=bookstat&villa_id='.$this->id.'">Моя бронь</a>',
        );
    }

    function createObjs()
    {
        $this->descriptions_obj = DB_DataObject::factory('descriptions');
        $this->user_options_obj = DB_DataObject::factory('user_options');
        $this->images_obj = DB_DataObject::factory('images');
        $this->villa_options_obj = DB_DataObject::factory('villa_options');

        $this->comments_obj = DB_DataObject::factory('comments');
    }

    function preDelete()
    {
        //echo 'preDelete';
        $this->createObjs();
        $this->descriptions_obj->villa_id = $this->id;
        $this->descriptions_obj->delete();
        $this->user_options_obj->villa_id = $this->id;
        $this->user_options_obj->delete();

        $this->images_obj->villa_id = $this->id;
        $this->images_obj->delete();
        
        $this->villa_options_obj->villa_id = $this->id;
        $this->villa_options_obj->delete();

        $this->comments_obj->villa_id = $this->id;
        $this->comments_obj->delete();
        $this->updateCounters( $this->locations_all, '-');
        $this->deleteLetter();
        return true;
    }

    function deleteLetter()
    {
        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = MAIL_ADMIN;
        $data['subject'] = 'villa deleted by user';
        $data['body'] = "\n № объекта ".$this->id
            ."\n № Пользователя ".$this->user_id
            ."\nНазвание".$this->title;
            DVS_Mail::send($data);
    }


    function updateCounters($locations_all, $mode='+')
    {
        //DB_DataObject::DebugLevel(1);
        if ($mode != '+') {
            $mode = '-';
        }
        $locations_arr = explode(':', $locations_all);
        foreach ($locations_arr as $k => $v) {
            if ($v) {
                $str .= ($str ? ',' : '').$v;
            }
        }
        $countries_obj = DB_DataObject::factory('countries');
        $countries_obj->query("UPDATE countries SET counter = counter".$mode."1 WHERE id IN($str)");
    }

    function preGenerateList($lang='ru')
    {
        //$alias = DVS::getVar('alias');
        //echo '<pre>';
        //print_r($_SERVER['REDIRECT_URL']);

        $alias = $_GET['alias'];
        if ($alias && preg_match("/^\/regions\//", $_SERVER['REDIRECT_URL'])) {
            $alias = preg_replace("/^\/regions\//", '', $_SERVER['REDIRECT_URL']);
        }
        //echo $alias;
       
        $location_id = DVS::getVar('loc');
        $keyword = DVS::getVar('keyword');
        $sale = DVS::getVar('sale');

        if (!$sale) {
            $this->sale = 0;
            //print_r($GLOBALS['_DVS']['LANG']['layout']['menu_text']['?op=villa']);
            $this->center_title .= ' / '.$GLOBALS['_DVS']['LANG']['layout']['client']['menu_text']['?op=villa'];
        } else {
            $this->center_title .= ' / '.$GLOBALS['_DVS']['LANG']['layout']['client']['menu_text']['?op=villa&sale=1'];
        }

        


        if ($this->translation_status == '') {
            unset($this->translation_status);
        }

        $st = DVS::getVar('st');

        $src = DVS::getVar('src_site');

//           DB_DataObject::DebugLevel(1);
            /*

        if ($src) {
            //echo $src;
            if ($this->status_id == '') {
                unset($this->status_id);
            }
            if (preg_match("/^homeaway/", $src)) {
                unset($this->src_site);
                $this->whereAdd('src_site LIKE "homeaway%"');
            }
        }
*/
        if ($_GET['search']) {
            //echo 'Поиск';
            if ($this->status_id == '') {
                unset($this->status_id);
            }
            if ($this->src_site == '') {
                unset($this->src_site);
            }
            if (preg_match("/[0-9]+/",$st)) {
                $this->id=intval($st);
            } else {
                $this->whereAdd('title LIKE "%'.$st.'%" OR title_rus LIKE "%'.$st.'%"');
            }
            if (preg_match("/^homeaway/", $src)) {
                unset($this->src_site);
                $this->whereAdd('src_site LIKE "homeaway%"');
            }
        }

        if ($keyword) {

            if (preg_match("/^[0-9]+/",$keyword)) {
                $this->id=intval($keyword);
                 $this->region_title = 'Поиск "'.$keyword.'"';
                return;
            }

            //echo $keyword;
            if (preg_match("/[a-z]+/", $keyword)) {
                $country_field = 'name';
                $title_field = 'title';
            } else {
                $country_field = 'rus_name';
                $title_field = 'title_rus';
            }
            $countries_obj = DB_DataObject::factory('countries');
            $countries_obj->whereAdd('parent_id > 0');
            $countries_obj->whereAdd($country_field.' LIKE "'.$keyword.'%"');
            $countries_obj->find();
            while ($countries_obj->fetch()) {
                //$this->whereAdd('locations_all LIKE "%:'.$countries_obj->id.':%"', 'OR');
                $loc_str .= ($loc_str ? ' OR ' : '').'locations_all LIKE "%:'.$countries_obj->id.':%"';
            }
            if ($loc_str) {
                $this->whereAdd($loc_str);
            }
            if ($countries_obj->N == 0) {
                $this->whereAdd($title_field.' LIKE "%'.$keyword.'%"');
            }
            $this->region_title = 'Поиск "'.$keyword.'"';
        }

        if ($alias == 'world') {
            return;
        }

        if ($alias) {
            if (preg_match("!\/!", urldecode($alias))) {
                $loc_arr = explode('/', $alias);
                $alias = $loc_arr[sizeof($loc_arr) - 2];
            }
            $countries_obj = DB_DataObject::factory('countries');
            $countries_obj->get('name', str_replace(array('_and_', "_", '\\'), array(" & ", " ", ""), $alias));
            $this->location_id = $countries_obj->id;
            $countries_obj->getParent($countries_obj->id, 'regions/', $lang);
            //echo $countries_obj->region_title.'<br>'.$countries_obj->locations_all;
            //$location_id = $countries_obj->id;
            $this->whereAdd('locations_all LIKE ":'.$countries_obj->locations_all.':%"');
            $this->region_title = $countries_obj->region_title_a;
            $this->region_title_t = $countries_obj->region_title_t;
        }

        //exit;
        if ($location_id) {

            $this->whereAdd('locations_all LIKE "%:'.$location_id.':%"');
            $countries_obj = DB_DataObject::factory('countries');
            $countries_obj->getParent($location_id, $lang);
             $this->center_title = $countries_obj->region_title_a;
            /*
            $countries_obj = DB_DataObject::factory('countries');
            $countries_obj->getChilds($location_id);

            //print_r($countries_obj->loc_arr);
            foreach ($countries_obj->loc_arr as $k => $v) {
                $str .= ($str ? ',' : '').$v;
            }
            $this->whereAdd('location IN ('.$str.')');
            */
        }

    }

    function propTypeName($name, $lang='ru')
    {
        if ($lang != 'ru') {
            $arr = $this->{'prop_type_arr_'.$lang};
        }
        if (!$arr) {
            $arr = $this->prop_type_arr;
        }
        if (isset($arr[$name])) {
            return $arr[$name];
        } else {
            return $arr['villa'];
        }
    }

    function deleteVillasByLocation($location_id)
    {
            $countries_obj = DB_DataObject::factory('countries');
            $countries_obj->getParent($location_id);
            $this->whereAdd('translation_status=0');
            $this->whereAdd('locations_all LIKE ":'.$countries_obj->locations_all.':%"');
            $this->find();
            echo $this->N.'<br>';
            while ($this->fetch()) {
                echo $this->id.' '.$this->title.'<br>';
                $this->preDelete();
                echo $this->delete();
            }
    }

    function saveDescr($descr)
    {
        foreach ($descr as $id => $vals) {
            $descriptions_obj = DB_DataObject::factory('descriptions');
            $descriptions_obj->get($id);
            $descriptions_obj->title_rus = $vals['title_rus'];
            $descriptions_obj->body_rus = $vals['body_rus'];
            $descriptions_obj->update();
        }
    }

    //Вывод формы
    function getForm()
    {
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '');
        $form->addElement('header', null, ($this->act == 'new' ? DVS_NEW : DVS_EDIT).' '.$this->head_form);
        $form->addElement('text', 'title', $this->fb_fieldLabels['title'], 'size=50');
        $form->addElement('textarea', 'summary', $this->fb_fieldLabels['summary'], array('rows' => 5, 'cols' => 47));

        $countries_obj = DB_DataObject::factory('countries');
        $countries_arr = $countries_obj->selArray();
        $form->addElement('select', 'location', 'Место расположения', $countries_arr);

        $t1 = $form->createElement('text', 'minprice', '', 'size=5');
        $t2 = $form->createElement('text', 'maxprice', '', 'size=5');
        $t3 = $form->createElement('select', 'currency', '', $this->currency_arr);

        $form->addGroup(array($t1, $t2, $t3), '', 'Цена (в неделю, от и до): ');
        $form->addElement('select', 'sleeps', 'Спальных мест',  array('' => 'Любое') + $this->sleeps_arr);
        $form->addElement('select', 'proptype', 'Тип', array('' => 'Любой') + $this->prop_type_arr);


        /*
        $t1 = $form->createElement('text', 'title', '', 'size=50');
        $t2 = $form->createElement('text', 'title_rus', '', 'size=50');
        $form->addGroup(array($t1, $t2), '', 'Заголовок: ');
        $s1 = $form->createElement('textarea', 'summary', '', array('rows' => 5, 'cols' => 47));
        $s2 = $form->createElement('textarea', 'summary_rus', '', array('rows' => 5, 'cols' => 47));
        $form->addGroup(array($s1, $s2), '', 'Краткое описание: ');
        $e1 = $form->createElement('textarea', 'extra', '', array('rows' => 5, 'cols' => 47));
        $e2 = $form->createElement('textarea', 'extra_rus', '', array('rows' => 5, 'cols' => 47));
        $form->addGroup(array($e1, $e2), '', 'Дополнительно: ');
        $descriptions_obj = DB_DataObject::factory('descriptions');
        //$descriptions_obj->editForm($this->id, $form);
        //$form->addElement('select', 'translation_status', 'Перевод:', array(1 => 'Новый', 2 => 'Проверен', 0 => 'Нет'));
        $form->addElement('checkbox', 'special_offer', 'На главной:');
        */
        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addRule('title', DVS_REQUIRED.' "'.$this->fb_fieldLabels['title'].'"!', 'required', null, 'client');
        $form->addRule('summary', DVS_REQUIRED.' "'.$this->fb_fieldLabels['summary'].'"!', 'required', null, 'client');

        return $form;
    }

    function preProcessForm(&$vals, $fb)
    {
        //print_r($vals);
        $this->saveDescr($vals['descr']);
    }


    function showNavigation($tpl, $lang)
    {
        if ($lang != 'ru') {
            $arr = $this->{'nav_arr_'.$lang};
        }
        if (!$arr) {
            $arr = $this->nav_arr;
        }

        $tpl->loadTemplateFile('villa_nav.tpl');
        $i = 1;
        foreach ($arr as $link => $text) {
            if ($this->sale && ($i == 7 || $i == 6)) {
                $i++;
                continue;
            }
            if (preg_match("/^#/", $link)) {
                $link = '/villa/{VILLA_ID}.html'.$link;
            }
            $link = str_replace('{VILLA_ID}', $this->id, $link);
            $tpl->setVariable(array(
                'nav_link' => $link, 
                'nav_text' => $text,
                'nav_class' => $i == sizeof($arr) ? ' class=last' : ''
                )
            );
            $i++;
            $tpl->parse('NAV_ITEM');
        }
        return $tpl->get();
    }

    public static function currencyRate()
    {
        $cur_file = PROJECT_ROOT.'conf/currency_rate.txt';
        $cur_str = @file_get_contents($cur_file);
        if ($cur_str) {
            preg_match_all("/([A-Z_]+)\t([0-9.]+)/", $cur_str, $cur_arr);
            //print_r($cur_arr);
            foreach ($cur_arr[1] as $k => $v) {
                $rate[strtolower($v)] = $cur_arr[2][$k];
            }
            return $rate;
        }
        return false;
    }

    function usdPrice($price)
    {
        if ($this->currency == "USD") {
            return $price;
        } else {
            $rate = $this->currencyRate();
            if ($rate) {
                if ($this->currency == "RUR") {
                    $price = $price / $rate['usd_rur'];
                } else if ($this->currency == "EUR") {
                    $price = $price * $rate['eur_usd'];
                } else if ($this->currency == "GBP") {
                    $price = $price * $rate['gbp_usd'];
                }
                return round($price);
            }
        }
        return $price;
    }

    function euroPrice($price)
    {
        if ($this->currency == "EUR") {
            return $price;
        } else {
            $rate = $this->currencyRate();
            if ($rate) {
                if ($this->currency == "RUR") {
                    $price = $price / $rate['eur_rur'];
                } else if ($this->currency == "USD") {
                    $price = $price / $rate['eur_usd'];
                } else if ($this->currency == "GBP") {
                    $price = $price / $rate['eur_gbp'];
                }
                return round($price);
            }
        }
        return $price;
    }

    public static function rurPrice($price, $currency, $rate)
    {
        if ($currency == "RUR") {
            return $price;
        } else {
            if ($rate) {
                if ($currency == "USD") {
                    $price = $price * $rate['usd_rur'];
                } else if ($currency == "EUR") {
                    $price = $price * $rate['eur_rur'];
                } else if ($currency == "GBP") {
                    $price = $price * $rate['eur_rur'] / $rate['eur_gbp'];
                }
                return round($price);
            }
        }
        return $price;
    }

    //Форма поиска для страницы администрирования
    function getSearchFormClient($tpl, $lang)
    {
        require_once ("HTML/QuickForm.php");
        $form = new HTML_QuickForm('searchForm', 'POST', '/search/');
        $form->addElement('text', 'keyword', '', array('class' => "text input-keyword default ac_input", 'id' => "searchKeywords") );
        $form->addElement('text', 'minprice', '', array('class' => 'input'));
        $form->addElement('text', 'maxprice', '', array('class' => 'input'));
        $form->addElement('select', 'sleeps', '',  array('' => $this->words['any']) + $this->sleeps_arr);
        $form->addElement('select', 'proptype', '', array('' => $this->words['any']) + $this->getLocalArr('prop_type_arr', $lang));
        $form->addElement('submit', 'submit', 'Register');

        $form->setDefaults($_GET);
        require_once 'HTML/QuickForm/Renderer/ITStatic.php';
        $renderer =& new HTML_QuickForm_Renderer_ITStatic($tpl);
        //$renderer->setRequiredTemplate('{label}<font color="red" size="1">*</font>');
        //$renderer->setErrorTemplate('<font color="red">{error}</font><br />{html}');
        $form->accept($renderer);
        $tpl->setVariable($this->words);
        return $tpl->get();
    }

    function formTabs($tpl, $selected=1)
    {
        if ($this->sale) {
            unset($this->form_headers[5],$this->form_headers[6]);
        }
         //tabtextselected
        foreach ($this->form_headers as $st => $st_name) {
            $tpl->setVariable(array(
                'tab_link' => $this->form_links[$st].$this->id,
                'tab_text' => $st_name,
                'tab_onclick' => ($this->act == 'new' ? 'return false;' : 'return validate_villa_form(document.forms[0]);')
            ));
            if ($st == $selected) {
                $tpl->setVariable(array('tab_class' => ' tabtextselected'));
            }
            $tpl->parse('tabs_group_loop');
        }
    }

    function getVillaByUser($user_id)
    {
            $this->user_id = $user_id;
            $this->find();
            while ($this->fetch()) {
                $str .= ($str ? ',' : '').$this->id;
            }
            return $str ? $str : false;
    }

    function getPay_periodName($lang)
    {
        if ($lang != 'ru') {
            $arr = $this->{'pay_period_arr_'.$lang};
        }
        if (!$arr) {
            $arr = $this->pay_period_arr;
        }
        if (!$this->pay_period) {
            $this->pay_period = 1;
        }
        return $arr[$this->pay_period];
    }

    function getShortSummary()
    {
        $summary = $this->summary_rus ? $this->summary_rus : $this->summary;
        return substr($summary, 0, 256);
    }

    function getPropGroup($type)
    {
        if ($this->prop_type_group[$type]) {
            foreach ($this->prop_type_group[$type] as $k => $v) {
                $str .= ($str ? ',' : '')."'$v'";
            }
            return "proptype IN (".$str.")";
        }
        return false;
    }

    function addPropType()
    {
        $proptype = DVS::getVar('ptype');
        if ($proptype) {
            $this->whereAdd($this->getPropGroup($proptype));
        }
    }

    function showTypeNav(&$tpl, $proptype)
    {
        $i = 0;
        foreach ($this->prop_type_arr as $k => $v) {
            $i++;
                $tpl->setVariable(array(
                    'TYPE_NAME' => $proptype == $k ?  "<b>$v</b>" : $v,
                    'TYPE_LINK' => $k,
                    'TYPE_SEPARATOR' => $i == sizeof($this->prop_type_arr) ? '' : ' | ',
                    'TYPE_ALT' => 'аренда '.$v.' без посредников',
                ));
                $tpl->parse('TYPE_NAV');
        }
    }

    function showTypeNav1(&$tpl, $proptype, $lang)
    {
        $i = 0;
        foreach ($this->prop_type_arr as $k => $v) {
            $i++;
                $tpl->setVariable(array(
                    'TYPE_NAME' => $v,
                    'TYPE_LINK' => 'http://'.($k ? $k.'.'.SERVER : SERVER).($lang != 'ru' ? '/'.$lang.'/' : ''),
                    'TYPE_SEPARATOR' => $i == sizeof($this->prop_type_arr) ? '' : ' | ',
                    'TYPE_ALT' => $this->words['rent'].' '.$v,
                ));
                if ($proptype == $k) {
                    $tpl->parse('TYPE_SEL');
                    $tpl->hideBlock('TYPE_NAV');
                } else {
                    $tpl->hideBlock('TYPE_SEL');
                }
                $tpl->parse('TYPE_ITEM');
        }
    }

    function getLocalField($field, $lang)
    {
        if ($lang == 'en') {
            return $this->{$field};
        }
        return $this->{$field.'_rus'} ? $this->{$field.'_rus'} : $this->{$field};
    }

    function getLocalArr($arr_name, $lang='ru')
    {
        if ($lang != 'ru') {
            $arr = $this->{$arr_name.'_'.$lang};
        }
        if (!$arr) {
            $arr = $this->{$arr_name};
        }
        return $arr;
    }

    function imageURL()
    {
        if ($this->src_site == 'villarenters.com') {
            if (SERVER_TYPE == 'local') {
                $url = 'http://villa/villa_img/';
            } else {
                $url = 'http://villarenters.ru/villa_img/';
            }
        } else {
            $url = VILLARENTERS_IMAGE_URL;
        }
        return $url;
    }
}
