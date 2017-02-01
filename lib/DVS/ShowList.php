<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс создания таблицы
------------------------------------------------------------------------------
$Id: ShowList.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_ShowList extends DVS_Dynamic
{
    /* Объект DVS_Table */
    var $table_obj;

    function getPageData()
    {
        $page_arr = array('CENTER' => '', 'CENTER_TITLE' => '');
        $this->createTemplateObj();

        if (isset($this->db_obj->search_arr)) {
            $page_arr['CENTER'] = $this->getSearchForm();
        }

        if (method_exists($this->db_obj, 'getSearchForm')) {
            $page_arr['CENTER'] = $this->db_obj->getSearchForm();
        }


        if (!isset($this->db_obj->search_arr['not_view_table']) || !$this->db_obj->search_arr['not_view_table'] || isset($_GET['st']) || !isset($this->db_obj->search_arr)) {
            $page_arr['CENTER'] .= $this->showTable();
        }
        if ($this->table_obj->js_delete) {
            $page_arr['JSCRIPT'] = file_get_contents(COMMON_LIB.'lib-common/doDelete.js');
        }
        $page_arr['CENTER_TITLE'] = ($this->db_obj->center_title) ? $this->db_obj->center_title : $this->db_obj->__table;
        return $page_arr;
    }

    function createTableObj()
    {
        if (!$this->table_obj) {
            require_once COMMON_LIB.'DVS/Table.php';
            $this->table_obj =& new DVS_Table;
        }
    }

    //Функция выполнения запроса в бд по умолчанию
    function getTable()
    {
        //Взять данные из строки запроса
        $this->db_obj->setFrom($_GET);

        //Поиск
        if (isset($_GET['st']) && isset($this->db_obj->search_arr)) {
            $this->setSearch();
        }

        //Сортировка
        if (isset($this->db_obj->field_order)) {
            $this->setOrder();
        }

        //Выполнение операций перед выполнение запроса
        if (method_exists($this->db_obj, 'preGenerateList')) { 
            $this->db_obj->preGenerateList();
        }

        //Сортировка
        $this->table_obj->sortList(&$this->db_obj);

        //Выполнение SQL - запроса
        $cnt = $this->db_obj->count();
        if ($cnt > 0) {
            $this->table_obj->setLimitByPage(&$this->db_obj, $cnt);
            $this->db_obj->find();
            $this->db_obj->N = $cnt;
        } else {
            $this->nocache = true;
        }
    }

    function showTable()
    {
        $this->createTableObj();
        $this->getTable();
        return $this->table_obj->buildTable(&$this->db_obj, $this->template_obj, $this->db_obj->qs, $this->role);
    }

    //Форма поиска для страницы администрирования
    function getSearchForm()
    {
        $this->template_obj->loadTemplateFile('form_search.tmpl');
        $this->template_obj->setVariable(array('TEXT' => 'Искать', 'NAME' => 'st', 'VALUE' => $_GET['st'], 'FORM_URL' => $this->db_obj->qs));
        //Скрытые поля
        foreach ($_GET as $k=>$v) {
            if ($v && !in_array($k, array('st', '_p', 'sort', 'o'))) {
                $this->template_obj->setVariable(array('HIDDEN' => $k, 'HIDDEN_VAL' => $v));
                $this->template_obj->parse('HIDDEN');
            }
        }
        return $this->template_obj->get();
    }

    function setSearch()
    {
        if (method_exists($this->db_obj, 'setSearch')) { 
            $this->db_obj->setSearch();
        } else {
            foreach ($this->db_obj->search_arr['fields_search'] as $key=>$val) {
                $where .= $val." LIKE '".$_GET['st']."%' OR ";
            }
            $this->db_obj->whereAdd('('.substr($where, 0, -4).')');
        }
    }

    function setOrder()
    {
        if (method_exists($this->db_obj, 'whereOrder')) { 
            $this->db_obj->whereOrder();
        }
        $this->db_obj->orderBy($this->db_obj->__table.'.'.$this->db_obj->field_order);

        $db_obj = DB_DataObject::factory($this->db_obj->__table);
        $db_obj->orderBy($this->db_obj->field_order.' DESC');
        $db_obj->limit(0,1);
        $db_obj->find(true);
        $this->db_obj->last_field_order = $db_obj->{$this->db_obj->field_order};

        $db_obj = DB_DataObject::factory($this->db_obj->__table);
        $db_obj->orderBy($this->db_obj->field_order.' ASC');
        $db_obj->limit(0,1);
        $db_obj->find(true);
        $this->db_obj->first_field_order = $db_obj->{$this->db_obj->field_order};
    }
}
?>
