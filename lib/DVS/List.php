<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс вывода списка
------------------------------------------------------------------------------
$Id: List.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

class DVS_List
{
    // Кол-во колонок
    var $columns_count = 2;

    // Индекс первой записи
    var $start_i = 0;

    // Ширина колонки в %
    var $column_width = 10;

    // Рисовать колонку с кол-вом
    var $use_colomn_cnt = false;

    // Рисуем список
    function buildList($db_obj, $template_obj)
    {
        // Нет записей
        if (!$db_obj->N) { 
            return 'Записей нет';
        }

        // Записей меньше колонок
        if ($db_obj->N < $this->columns_count) {
            $this->columns_count = $db_obj->N;
        }

        // Ширина колонки
        if ($this->columns_count > 1) {
            $this->column_width = ceil(100/$this->columns_count);
        }

        // Количество записей в одной колонке
        $count_column_rows = ceil($db_obj->N/$this->columns_count);

        // Данные
        $data_arr = $this->getDataList(&$db_obj, $count_column_rows);

        // Шаблон
        $template_obj->loadTemplateFile('list.tmpl');
        for ($i = 1; $i <= count($data_arr['name']); $i++) {
            $template_obj->setVariable('NAME', $data_arr['name'][$i]);
            if ($this->use_colomn_cnt) {
                $template_obj->setVariable('CNT', $data_arr['cnt'][$i]);
            }
            // Разделитель между колонками
            if ($i < $this->columns_count) {
                $template_obj->setVariable('IMG_URL', IMAGE_URL);
            }
            $template_obj->parse('CELL');
        }
        $template_obj->setVariable('WIDTH', $this->column_width);
        return $template_obj->get();
    }

    // Получим массив данных из базы
    function getDataList($db_obj, $count_column_rows)
    {
        $col_i = 1;
        while ($db_obj->fetch()) {
            // Значения
            if (method_exists($db_obj, 'listRow')) {
                $values = $db_obj->listRow();
            } else {
                $values = $db_obj->toArray();
            }

            $data_arr['name'][$col_i] .= $values['name'].'<br>';
            $data_arr['cnt'][$col_i]  .= $values['cnt'].'<br>';

            // Кол-во записей в колонке превышает необходимое кол-во
            if (++$this->start_i >= $count_column_rows) {
                // Пересчитаем кол-во записей в текущей колонке
                if ($col_i < $this->columns_count) {
                    $db_obj->N -= $count_column_rows;
                    $count_column_rows = ceil($db_obj->N/($this->columns_count - $col_i));
                }
                $col_i++;
                $this->start_i = 0;
            }
        }
        return $data_arr;
    }
}

?>