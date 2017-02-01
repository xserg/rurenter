<?php
/**
 * Список стран
 * @package villa
 * $Id: Countries_Index.php 448 2013-10-29 13:49:33Z xxserg@gmail.com $
 */


require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Countries_Index extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('iu' => 1, 'mu' => 1, 'oc' => 1);

    private $lang_ru = array(
        'Page_title' => 'Аренда квартир, домов, вилл',
        'Page_title_apartment' => 'Аренда квартир, апартаментов в России',
        'Page_title_villa' => 'Аренда вилл, коттеджей, домов',
        'Header1' => 'Аренда квартир, домов, апартаментов, вилл, коттеджей в России по всему миру',
        'Header1_apartment' => 'Аренда квартир, апартаментов в России по всему миру',
        'Header1_villa' => 'Аренда домов, вилл, коттеджей в России по всему миру',
        'wellcome' => 'ruRenter.ru – Интернет каталог по аренде  квартир, апартаментов, вилл, домов и апартаментов для проживания и отдыха',
        'wellcome_apartment' => 'ruRenter.ru – Предложения по аренда квартир без посредников. Прямая аренда квартир в Москве, Петербурге и по России.',
        'wellcome_villa' => 'ruRenter.ru – Вы найдете предложения по аренде лучших вилл, домов, коттеджей, апартаментов для проживания и отдыха в России и зарубежом напрямую без посредников у хозяев недвижимости',
        'Show_All' => 'Показать все',
        'View_this_property' => 'Подробнее...',
        'Holiday_villas_in' => '',
        'World_Holiday_Destinations' => 'По регионам',
        'Search' => 'Поиск',
        'Advanced' => 'Расширенный',
        'apartment' => 'Квартиры',
        'villa' => 'Дома',
        'all' => 'Все',

        'Page_title_sale' => 'Продажа, вилл, домов, апартаментов, квартир',
        'Page_title_apartment_sale' => 'Продажа квартир, апартаментов',
        'Page_title_villa_sale' => 'Продажа вилл, домов, коттеджей, шале, шато',
        'Header1_sale' => 'Продажа вилл, квартир, домов, апартаментов, коттеджей в России по всему миру',
        'Header1_apartment_sale' => 'Продажа квартир, апартаментов в России по всему миру',
        'Header1_villa_sale' => 'Продажа домов, вилл, коттеджей в России по всему миру',
        'wellcome_sale' => 'ruRenter.ru – Интернет каталог по продаже квартир, апартаментов, вилл, домов и апартаментов для проживания и отдыха',
        'wellcome_apartment_sale' => 'ruRenter.ru – Предложения по продаже квартир без посредников. Прямая продажа квартир',
        'wellcome_villa_sale' => 'ruRenter.ru – Вы найдете предложения по продажее лучших вилл, домов, коттеджей, апартаментов для проживания и отдыха в России и зарубежом напрямую без посредников у хозяев недвижимости',

    );


    private $lang_en = array(
        'Page_title' => 'villas, apartments houses for rent',
        'Page_title_apartment' => 'villas, apartmenta? houses for rent',
        'Page_title_villa' => 'villas, cottages, houses for rent',
        'Header1' => 'Self catering villas, apartments and cottages for rent over the world',
        'Header1_apartment' => 'Apartments and cottages for rent over the world',
        'Header1_villa' => 'Villas, apartments and cottages for rent over the world',
        'wellcome' => 'ruRenter.ru – choose holiday home worlwide',
        'wellcome_apartment' => 'ruRenter.ru – choose holiday home for rent worlwide',
        'wellcome_villa' => 'ruRenter.ru – choose holiday home worlwide',
        'Show_All' => 'Show All',
        'View_this_property' => 'Details...',
        'Holiday_villas_in' => '',
        'World_Holiday_Destinations' => 'Regions',
        'Search' => 'Search',
        'Advanced' => 'Advanced',
        'apartment' => 'Apartments',
        'villa' => 'Houses',
        'all' => 'All',
    );


    private $proptype;

    function getPageData()
    {
        $proptype = DVS::getVar('ptype');
        $sale = DVS::getVar('sale');
        if (!$sale) {
            $sale = 0;
        }
        $this->showSeo($proptype, $sale);
        $words = $this->{'lang_'.$this->lang};

        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('countries_index.tpl');
        $this->villa_obj = DB_DataObject::factory('villa');
        $this->villa_obj->prop_type_arr = array(
                    'apartment' => $words['apartment'],
                    'villa' => $words['villa'],
                    '' => $words['all']
                );
        $this->villa_obj->showTypeNav1($this->template_obj, $proptype, $this->lang);

        $this->db_obj->getList(0, $this->villa_obj->getPropGroup($proptype), $sale);
        while ($this->db_obj->fetch()) {
            $region_name = $this->db_obj->getAlias();
            if ($this->db_obj->counter > 0) {
                $this->template_obj->setVariable(array(
                'REGION_LINK'      => 'regions/'.$region_name.'/',
                'REGION_ALL_LINK' => 'regions/'.$region_name.'/',
                'REGION_NAME' => $this->db_obj->getLocalName($this->lang),
                'REGION_CNT' => $this->db_obj->counter,
                //'REGION_CNT' => $this->db_obj->cnt,
                ));

                $сountries_obj = DB_DataObject::factory('countries');
                $сountries_obj->getList($this->db_obj->id, $this->villa_obj->getPropGroup($proptype), $sale);
                $i = 0;
                while ($сountries_obj->fetch()) {

                    if ($сountries_obj->counter > 0) {
                        $this->template_obj->setVariable(array(
                        //'COUNTRY_LINK'  => '?op=villa&act=index&loc='.$сountries_obj->id,
                        'COUNTRY_LINK'  => 'regions/'.$region_name.'/'.$сountries_obj->getAlias().'/',
                        'COUNTRY_NAME' => $сountries_obj->getLocalName($this->lang),
                        'COUNTRY_CNT' => $сountries_obj->counter,
                        ));
                        $i++;
                        $this->template_obj->parse('COUNTRY_ROW');
                        if ($cnt >= 3) {
                            if (!($i % ($cnt / 3))) {
                                $this->template_obj->parse('COUNTRIES_COLUMN');
                            }
                        } else {
                            $this->template_obj->parse('COUNTRIES_COLUMN');
                        }
                    }
                }
                
                if ($i < 3) {
                    $this->template_obj->setVariable(array(
                    'COLUMN_CLASS' =>'-alfa'));
                    $this->template_obj->parse('COUNTRY_ROW');
                }
                
                $this->template_obj->parse('REGION');
            }
        }

        $img_arr = array(2,3,5,6,7,8,9);

        $this->template_obj->setVariable(array(
            'WELCOME_MESSAGE' => $this->lang_ru['wellcome'],
            'ALL_CNT' => $this->getAllcnt(),
            //'image_header' => '<img src=/img/header/Header'.$img_arr[array_rand($img_arr)].'.jpg alt="аренда вилл" title="Аренда вилл, домов, коттеджей, апартаментов">',

            //'ANNONCE_TITLE' => 'Новости туризма',
            //'news' => file_get_contents(PROJECT_ROOT.'conf/news.txt'),
        ));
        $this->getSpecial();
        //$this->getAnnonce();

        $this->template_obj->setGlobalVariable($words);
        $page_arr['BODY_CLASS']     = 'landing';
        //$page_arr['BODY_CLASS']     = 'homePage';
        //$page_arr['CENTER_TITLE']   = $this->lang_ru['Self-catering_holiday_rentals_around_the_World'];
        $page_arr['PAGE_TITLE'] = $this->lang_ru['Page_title'].' - '.$this->layout_obj->project_title;

        $page_arr['CENTER']         = $this->template_obj->get();
        $page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        return $page_arr;
    }

    function getAllcnt()
    {
            //$this->villa_obj->proptype = $proptype;
            $this->villa_obj->addPropType();
            $this->villa_obj->status_id = 2;
            //$this->template_obj->setGlobalVariable(array('SERVER_URL' => SERVER));
            return $this->villa_obj->count();
    }


    function getSpecial()
    {
        $this->villa_obj->special_offer = 1;
        $this->villa_obj->limit(0,5);
        $this->villa_obj->find();
        $i = 0;
        while ($this->villa_obj->fetch()) {
               $i++;
                $this->template_obj->setVariable(array(
                'TOP_VILLA_LINK'      => ($this->lang != 'ru'? '/'.$this->lang : '').'/villa/'.$this->villa_obj->id.'.html',
                //'TOP_VILLA_LINK'  => '?op=villa&act=show&id='.$this->villa_obj->id,
                'TOP_VILLA_TITLE' => $this->villa_obj->getLocalField('title', $this->lang),
                'TOP_VILLA_IMAGE' => VILLARENTERS_IMAGE_URL.$this->villa_obj->main_image,
                'last' => $i == 5 ? 'last' : ''
                ));
            $this->template_obj->parse('TOP_VILLA');
        }
    }

    function getAnnonce()
    {
        $pages_obj = DB_DataObject::factory('pages');
        $pages_obj->get('alias', 'annonce');
        $this->template_obj->setVariable(array(
        'ANNONCE_TITLE' => $pages_obj->title,
        'ANNONCE' => nl2br($pages_obj->body)));
    }

    function showSeo($proptype, $sale=0)
    {
        if ($proptype) {
            $this->lang_ru['Header1'] = $this->lang_ru['Header1_'.$proptype.($sale == 1 ? '_sale' : '')];
            $this->lang_ru['Page_title'] = $this->lang_ru['Page_title_'.$proptype.($sale ? '_sale' : '')];
            $this->lang_ru['wellcome'] = $this->lang_ru['wellcome_'.$proptype.($sale ? '_sale' : '')];
        } else if ($sale){
            $this->lang_ru['Header1'] = $this->lang_ru['Header1_sale'];
            $this->lang_ru['Page_title'] = $this->lang_ru['Page_title_sale'];
            $this->lang_ru['wellcome'] = $this->lang_ru['wellcome_sale'];
        }
    }
}

?>