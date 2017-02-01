<?php
/*////////////////////////////////////////////////////////////////////////////
lib3/DVS/pages
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс создания таблицы
------------------------------------------------------------------------------
$Id: getJson.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_getJson extends DVS_Dynamic
{
    /*
    public $js_content = array(
        array(
            'type' => 'static',
            'file' => 'doDelete.js',
        ),
    );
    */
    public function getPageData()
    {
        //$this->render = 'JsArray';

        $this->db_obj = $this->createDBObj($this->op, $this->act);

        $this->db_obj->doc_type = 'js';

        if (!is_object($this->db_obj)) {
            return false;
        }

        $this->getTable();
    }


    /**
     * Функция выполнения запроса в бд по умолчанию
     */
    protected function getTable()
    {
        /* Взять данные из строки запроса */
        $this->db_obj->setFrom($_GET);

        /* Выполнение операций перед выполнением запроса */
        if (method_exists($this->db_obj, 'preGenerateJson')) {
            $this->db_obj->preGenerateList();
        }


        /* Сортировка */
        if (isset($this->db_obj->default_sort)) {
            $this->db_obj->orderBy($this->db_obj->default_sort);
        }

        /* Выполнение SQL - запроса */
        $this->db_obj->find();
        echo $this->render($this->db_obj);
        exit;
    }

    /**
     * Массив данных для использования в javascript формата JSON
     * @param DB_DataObject $db_obj
     * @return string HTML
     */
    public function render($db_obj)
    {
        $this->createTemplateObj();
        //$this->template_obj->setRoot(COMMON_LIB.'js/tmpl/');
        $this->template_obj->loadTemplateFile('js_array.tmpl',1,1);
        //$this->template_obj->setRoot(COMMON_LIB.'tmpl/');
        $n = 0;
        if ($db_obj->data_array) {
            foreach ($db_obj->data_array as $values) {
                $this->parseJson ($values, $n);
                $n++;
            }
        } else {
            while ($db_obj->fetch()) {
                $values = method_exists($db_obj, 'jsonRow') ? $db_obj->jsonRow() : $db_obj->ToArray();
                $this->parseJson ($values, $n);
                $n++;
            }
        }
        return $this->template_obj->get();
    }

    private function parseJson ($values, $n)
    {
            $m = 0;
            foreach ($values as $name => $val) {
                    $this->template_obj->setVariable(array('ITEM_NAME' => $name, 'ITEM_VALUE' => $val, 'ITEM_SEPARATOR' => $m ? ', ' : ''));
                    $this->template_obj->parse('CELL');
                    $m++;
            }
            $this->template_obj->setVariable(array('ROW_SEPARATOR' => $n ? ', ' : ''));
            $this->template_obj->parse('ROW');
    }
}
?>
