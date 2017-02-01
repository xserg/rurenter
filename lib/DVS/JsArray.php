<?php
/**
 * DVS Framework
 *
 * @category   DVS
 * @package    DVS
 * @version $Id: JsArray.php 21 2010-10-28 06:25:35Z xxserg $
 */

/**
 * Класс рендера
 *
 * @category   DVS
 * @package    DVS
 */

class DVS_JsArray extends DVS_Render
{

    /**
     * Массив данных для использования в javascript формата JSON
     * @param DB_DataObject $db_obj
     * @return string HTML
     */
    public function render($db_obj)
    {
        $this->template_obj->setRoot(COMMON_LIB.'js/tmpl/');
        $this->template_obj->loadTemplateFile('js_array.tmpl',1,1);
        $this->template_obj->setRoot(COMMON_LIB.'tmpl/');
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
