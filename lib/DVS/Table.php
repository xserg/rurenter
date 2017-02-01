<?php
/*////////////////////////////////////////////////////////////////////////////
lib/DVS
------------------------------------------------------------------------------
Класс создания таблицы
------------------------------------------------------------------------------
$Id: Table.php 200 2012-03-16 15:17:39Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

class DVS_Table
{
    /* Цвета для таблицы */
    var $table_colors = array('#ffffff','#ECECEC');

    /* Объект Pager */
    var $pager_obj;

    /**
    * Подтверждение удаления javascript
    */
    var $js_delete = true;

    /* Установка параметров для объекта навигации по страницам */
    var $page_params = array(
        //Сепаратор
        'spacesBeforeSeparator' => 1,
        'spacesAfterSeparator'  => 1,
        'separator'             => ' | ',
        //Количество записей на странице и в линейке до/после
        'perPage'               => 20,
        'delta'                 => 10,
        //Не выводить, если одна страница
        'clearIfVoid'           => true,
        'mode'                  => 'Jumping',
        //URL
        'urlVar'                => '_p',
        'append'                => true,
        'fileName'              => '',
        //Всего записей
        'totalItems'            => 0,
        //Листалки
        'prevImg'               => '<<',
        'altPrev'               => 'пред.',
        'altNext'               => 'след.',
        'nextImg'               => '>>',
        'altPage'               => 'стр.',
    );

    // Лимит по страницам
    function setLimitByPage($db_obj, $cnt = 0)
    {
        //==
        if (!isset($_GET['_p'])) {
            $_GET['_p'] = 1;
        }

        //Проверка на существование запрощенной страницы
        if ($cnt && (($_GET['_p'] - 1)*$this->page_params['perPage']) > $cnt) {
            $_GET['_p'] = 1;
        }
        $db_obj->limit(($_GET['_p'] - 1)*$this->page_params['perPage'], $this->page_params['perPage']);
    }

    //Сортировка
    function sortList($db_obj)
    {
        if (isset($_GET['sort']) && isset($db_obj->sortLabels[$_GET['sort']])) {
            $o = $_GET['o'] == 'd' ? ' DESC' : '';
            $db_obj->orderBy($db_obj->sortLabels[$_GET['sort']].$o);
        }

        //Дефолтная сортировка
        if (!isset($_GET['sort']) && isset($db_obj->default_sort)) {
            $db_obj->orderBy($db_obj->default_sort);
        }
    }

    //Изменение настроек Pager, установленных по умолчанию
    function setPager()
    {
        //print_r($_SERVER);
        $this->page_params['append'] = false;
        //Добавим параметры
        $qs = str_replace($_SERVER['REDIRECT_URL'], '', urldecode($_SERVER['REQUEST_URI']));
        //$qs = preg_replace('/_p='.$_GET['_p'].'\&?/', '', $qs);
        $qs = preg_replace('/_p=[0-9]*\&?/', '', $qs);
        if (!preg_match('/[\&\?]$/i', $qs)) {
            $qs .= strstr($qs, '?') ? '&' : '?';
        }

        $this->page_params['path']     = $_SERVER['REDIRECT_URL'];
        $this->page_params['fileName'] = $qs.'_p=%d';
    }

    //Рисуем таблицу
    function buildTable($db_obj, $template_obj, $qs, $role)
    {
        //Установка pager
        $this->setPager();

        $template_obj->loadTemplateFile('table.tpl',1,1);

        $l_edit   = $qs.'&act=edit&';
        $l_delete = $qs.'&act=delete&';
        $l_new    = $qs.'&act=new';
        $l_up     = $qs.'&act=up&';
        $l_down   = $qs.'&act=down&';

        $l_download   = $qs.'&act=download&';


        $b_new = ((isset($db_obj->perms_arr['new'][$role]) && $db_obj->perms_arr['new'][$role] == 1)
            || ($role == 'aa' && !isset($db_obj->perms_arr['new']['aa']))) ? true : false;
        $b_edit = ((isset($db_obj->perms_arr['edit'][$role]) && $db_obj->perms_arr['edit'][$role] == 1)
            || ($role == 'aa' && !isset($db_obj->perms_arr['edit']['aa']))) ? true : false;
        $b_delete = ((isset($db_obj->perms_arr['delete'][$role]) && $db_obj->perms_arr['delete'][$role] == 1)
            || ($role == 'aa' && !isset($db_obj->perms_arr['delete']['aa']))) ? true : false;

        if (isset($db_obj->downolad_list)) {
            $b_download = ((isset($db_obj->perms_arr['download'][$role]) && $db_obj->perms_arr['download'][$role] == 1)
                || ($role == 'aa' && !isset($db_obj->perms_arr['download']['aa']))) ? true : false;
            $template_obj->setVariable(array('DOWNLOAD_BUTTON_URL' => $l_download));
        }


        if (!$b_delete) {
            $this->js_delete = false;
        }

        //Строим таблицу
        if ($db_obj->N) {
            //Установка значений выводимых названий полей _по умолчанию_
            if (!is_array($db_obj->listLabels)) {
                foreach ($db_obj->table() as $key=>$val) {
                    //Поля, выводимые в таблице, и атрибутты столбца array('name' => 'align=center')
                    $db_obj->listLabels[$key] = '';        
                    //Названия полей (используется в форме и при выводе таблицы)
                    $db_obj->fb_fieldLabels[$key] = $key;
                }
            }

            //Добавление столбца с сортировкой
            if (isset($db_obj->field_order)) {
                $db_obj->listLabels['order']  = 'width=45';
                $db_obj->fb_fieldLabels['order'] = '&nbsp;';
            }

            //Добавление столбца с кнопками
            if ($b_edit || $b_delete) { 
                $db_obj->listLabels['button']  = 'width=40';
                $db_obj->fb_fieldLabels['button'] = '&nbsp;';
            }

            //Ссылка для сортировки
            $sort_qs  = preg_replace(array(
                '/sort='.$_GET['sort'].'\&?/',
                '/\o='.$_GET['o'].'\&?/',
                '/_p='.$_GET['_p'].'\&?/',
                '/[\?\&]$/'
            ), '', urldecode($_SERVER['REQUEST_URI']));
            $sort_qs .= strstr($sort_qs, '?') ? '&' : '?';

            //Рисуем заголовки для таблицы
            foreach ($db_obj->listLabels as $key=>$attr) {
                $field = $db_obj->fb_fieldLabels[$key];
                if (isset($db_obj->sortLabels[$key])) { // Поле сортируемое
                    if ($_GET['sort'] == $key) { //Сортировка по тому же полю
                        if ($_GET['o'] == 'd') {
                            $o   = '';
                            $img = '&nbsp;<img src="'.IMAGE_URL.'desc.gif" border=0>';
                        } else {
                            $o   = '&o=d';
                            $img = '&nbsp;<img src="'.IMAGE_URL.'asc.gif" border=0>';
                        }
                    } else { //Сортировка по другому полю
                        $o   = '';
                        $img = '';
                    }
                    $field   = '<a href="'.$sort_qs.'sort='.$key.$o.'">'.$field.$img.'</a>';
                }
                $template_obj->setVariable(array('VAL' => $field, 'TD_ATTR' => $attr));
                $template_obj->parse('TH');
            }

            if (!isset($db_obj->id) && $keys = $db_obj->keys()) {
                $id = $keys[0];
            } else {
                $id = 'id';
            }

            //Цвет строки
            $clr = 0;
            while ($db_obj->fetch()) {

                //Определить массив значений
                $values = method_exists($db_obj, 'tableRow') ? $db_obj->tableRow() : $db_obj->ToArray();

                //Кнопки сортировки
                if (isset($db_obj->field_order)) {
                    //if ($db_obj->{$db_obj->field_order} > $db_obj->first_field_order) {
                    if (++$i > 1) {
                        $template_obj->setVariable(array('IMGS' => IMAGE_URL, 'UP_BUTTON' => $l_up.'id='.$db_obj->$id));
                        $template_obj->parse('UP_BUTTON');
                    }
                    //if ($db_obj->{$db_obj->field_order} < $db_obj->last_field_order) {
                    if ($i < $db_obj->N) {
                        $template_obj->setVariable(array('IMGS' => IMAGE_URL, 'DOWN_BUTTON' => $l_down.'id='.$db_obj->$id));
                        $template_obj->parse('DOWN_BUTTON');
                    }
                    $template_obj->setVariable(array('IMGSS' => IMAGE_URL));
                    $template_obj->parse('SORDER');
                }

                //Кнопка редактирования
                if ($b_edit) {
                    $template_obj->setVariable(array(
                        //'IMGS' => IMAGE_URL,
                    'EDIT_BUTTON' => $l_edit.'id='.$db_obj->$id, 'DVS_EDIT' => DVS_EDIT));
                    $template_obj->parse('EDIT_BUTTON');
                }

                //Кнопка удаления
                if ($b_delete) {
                    $template_obj->setVariable(array(
                        //'IMGS' => IMAGE_URL,
                    'DELETE_BUTTON' => $l_delete.'id='.$db_obj->$id, 'DVS_DELETE' => DVS_DELETE));
                    $template_obj->parse('DELETE_BUTTON');
                }

                //Столбцы со значениями
                foreach ($db_obj->listLabels as $key=>$attr) {
                    if (!in_array($key, array('button', 'order'))) {
                        $template_obj->setVariable(array('VAL' => isset($values[$key]) ? $values[$key] : $this->$key, 'TD_ATTR' => $attr));
                        $template_obj->parse('CELL');
                    }
                }

                //Цвет
                $template_obj->setVariable(array('BG_COLOR' => $this->table_colors[$clr]));
                $template_obj->parse('ROW');
                $clr = $clr ? 0 : 1;
            }

            $this->page_params['totalItems'] = $db_obj->N;
            $this->createPagerObj();
            $links = $this->pager_obj->getLinks();

            $template_obj->setVariable(array('CNT' => $db_obj->N, 'PAGES' => $links['all'], 'DVS_TOTAL' => DVS_TOTAL));
        }else{
            $template_obj->setVariable('NO_TABLE', isset($_GET['search_text']) ? 'По запросу ничего не найдено' : 'Записей нет');
        }

        //Кнопка добавления
        if ($b_new) { 
            $template_obj->setVariable(array('ADD_BUTTON' => $db_obj->head_form, 'ADD_BUTTON_URL' => $l_new));
            $template_obj->parse('ADD_BUTTON');
            $template_obj->setVariable(array('ADD_BUTTON' => $db_obj->head_form, 'ADD_BUTTON_URL' => $l_new));
            $template_obj->parse('ADD_BUTTON1');
        }

        $content = $template_obj->get();
        return $content;
    }

    function createPagerObj()
    {
        if (!$this->pager_obj) {
            require_once 'Pager/Pager.php';
            //$this->pager_obj = &new Pager($this->page_params);
            $this->pager_obj = Pager::factory($this->page_params);
        }
    }

    static function createPager($db_obj)
    {
        require_once 'Pager/Pager.php';
        $cnt = $db_obj->count();
        $page_params = array(
            'spacesBeforeSeparator' => 1,
            'spacesAfterSeparator'  => 1,
            'separator'             => '',
            'curPageLinkClassName'  => 'current',
            'perPage'               => PER_PAGE,
            'delta'                 => 10,
            'clearIfVoid'           => true,
            'mode'                  => 'Jumping',
            'urlVar'                => '_p',
            'append'                => false,
            'fileName'              => '',
            'totalItems'            => $cnt,
            'prevImg'               => '<<',
            'altPrev'               => 'пред.',
            'altNext'               => 'след.',
            'nextImg'               => '>>',
            'altPage'               => 'стр.',
            'path'                  => $_SERVER['REDIRECT_URL'],
        );
        $p = DVS::getVar($page_params['urlVar'], 'int');
        $qs = str_replace($_SERVER['REDIRECT_URL'], '', urldecode($_SERVER['REQUEST_URI']));
        $qs = preg_replace('/'.$page_params['urlVar'].'='.$p.'\&?/', '', $qs);
        if (!preg_match('/[\&\?]$/i', $qs)) {
            $qs .= strstr($qs, '?') ? '&' : '?';
        }
        //echo $qs;
        $page_params['fileName'] = $qs.$page_params['urlVar'].'=%d';
        if (empty($p)) {
            $p = 1;
        }
        //Проверка на существование запрощенной страницы
        if ($cnt && (($p - 1)*$page_params['perPage']) > $cnt) {
            $p = 1;
        }
        $db_obj->limit(($p - 1)*$page_params['perPage'], $page_params['perPage']);    
        $pager_obj = Pager::factory($page_params);
        $links = $pager_obj->getLinks();
        $links['cnt'] = $cnt;
        return $links;
    }

    function sortMenu($tpl, $sort_arr, $active = 'id')
    {
        $i = 0;
        foreach ($sort_arr as $svar => $sname) {
            $tpl->setVariable(array(
                    'SORT_LINK' => $_SERVER['REDIRECT_URL'],
                    'SVAR'      => $svar,
                    'SNAME'     => $sname,
                ));
            $i++;
            if ($i < sizeof($sort_arr)) {
                $tpl->setVariable('SEPARATOR', ' | ');
            }
            if ($svar == $active) {
                $tpl->parse('SAITEM');
            } else {
                $tpl->parse('SITEM');
            }
            $tpl->parse('SORTI');
        }
    }
}
?>