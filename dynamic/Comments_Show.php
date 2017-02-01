<?php
/**
 * Карточка кампании
 * @package web2matrix
 * $Id: Comments_Show.php 75 2010-12-19 13:39:18Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Comments_Show extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ar' => 1, 'oc' => 1, 'iu' => 1);

    private $change_content = true;

    private $lang_ru = array(
        'Reviews' => 'Отзывы о ',
        'Review_Submitted' => 'Дата',
        'Rating' => 'Рейтинг'
    );

    public $nav_arr = array(
        '/villa/{VILLA_ID}.html' => 'Назад',
        '/villa/{VILLA_ID}.html#photos-bar' => 'Фото и описания',
        '/villa/{VILLA_ID}.html#facility-bar' => 'Услуги и оборудование',
        '/villa/{VILLA_ID}.html#location-bar' => 'Расположение и окрестности',
        '/?op=villa&act=book&id={VILLA_ID}' => 'Цены и бронирование',
        '/?op=comments&act=show&villa_id={VILLA_ID}' => 'Отзывы',
        '/?op=query&act=new&villa_id={VILLA_ID}' => 'Задать вопрос'
    );

    function getPageData()
    {        
        //print_r($_GET);
        /*
        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }
        */
        //DB_DataObject::DebugLevel(1);
        $this->createTemplateObj();



            $villa_id = DVS::getVar('villa_id');

            if ($this->db_obj->villa_id) {
                $villa_id = $this->db_obj->villa_id;
            }

            $villa_obj = DB_DataObject::factory('villa');
            $villa_obj->get($villa_id);

            if (!$villa_obj->N) {
                //$this->show404();
                $this->nocache = true;
            }
            $villa_obj->nav_arr = $this->nav_arr;
            $nav = $villa_obj->showNavigation($this->template_obj);
        $this->template_obj->loadTemplateFile('comments_show.tpl');


        if (!$this->db_obj->N) {

            //$this->db_obj = DB_DataObject::factory('comments');
            $this->db_obj->villa_id = $villa_id;
             $this->db_obj->status_id = 2;
            $this->db_obj->orderBy('post_date desc');
            $this->db_obj->find();

        } else {
            if ($this->db_obj->status_id != 2) {
                exit;
            }
            //print_r($this->db_obj);            
            //$this->template_obj->loadTemplateFile('comments_show.tpl');
                        $this->setData();

        }



        while ($this->db_obj->fetch()) {
            $this->setData();
            $this->template_obj->parse('COMMENT');
        }

        if (!$this->db_obj->N) {
            $this->template_obj->setVariable(array('NO_COMMENTS' => 'По данному объекту еще нет ни одного отзыва...'));
        }



        $this->template_obj->setVariable(array(
                'Name' => $villa_obj->title_rus,
                'VILLA_NAV' => $nav,
                'VILLA_ID' => $villa_id
            ));

        $this->template_obj->setGlobalVariable($this->lang_ru);
        
        $page_arr['BREADCRUMB'] = $this->showLocation($villa_obj->location);
        $page_arr['CENTER_TITLE']   = 'Отзывы об аренде <a href="/villa/'.$villa_obj->id.'.html">'.$villa_obj->title_rus.'</a>';

        $page_arr['PAGE_TITLE'] = 'Отзывы о '.$villa_obj->title_rus.' - '.$this->region_title_t.' - Аренда вилл - '.$this->layout_obj->project_title;
        $page_arr['BODY_CLASS']   = 'reviews-read';
        $page_arr['CENTER']         = $this->template_obj->get();
        $page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        return $page_arr;
    }

    function setData()
    {
            $this->template_obj->setVariable(
                    array(
                        'TEXT' => $this->db_obj->article,
                        'AUTHOR' => $this->db_obj->author,
                        'CITY' => $this->db_obj->city,
                        'DATE' => $this->db_obj->post_date,
                    )

            );
            if ($this->db_obj->rating) {
                $this->template_obj->setVariable(array(
                    'RATING_NUM' => floor($this->db_obj->rating/2),
                    'Rating' => 'Рейтинг',
                    //'approved' => 'проверено RentalSystems'
                ));
            }          
    }

    function showLocation($id)
    {
        $countries_obj = DB_DataObject::factory('countries');
        $countries_obj->getParent($id);
        $this->region_title_t = $countries_obj->region_title_t;
        return $countries_obj->region_title;
    }
}

?>