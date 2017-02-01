<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс изменения порядка сортировки записей
------------------------------------------------------------------------------
$Id: Order.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_Order extends DVS_Dynamic
{
    // Поле сортировки
    var $field_order = 'sorder';

    // Условия поиска
    var $condition_arr = array('up' => '<', 'down' => '>');

    // Сортировка для поиска
    var $order_arr = array('up' => 'DESC', 'down' => 'ASC');

    // Изменяет порядок
    function getPageData()
    {
        // Переопределим поле сортировки
        if ($this->db_obj->field_order) {
            $this->field_order = $this->db_obj->field_order;
        }

        // Проверить порядок
        $this->checkOrder();

        // Обновить данные после проверки
        $this->db_obj->query("SELECT * FROM ".$this->db_obj->__table." WHERE id=".$this->db_obj->id);
        $this->db_obj->fetch();

        // Найти предыдущую(up) или следующую(down) запись
        $db_obj = DB_DataObject::factory($this->db_obj->__table);
        $db_obj->whereAdd($this->field_order.$this->condition_arr[$this->act].$this->db_obj->{$this->field_order});
        $db_obj->orderBy($this->field_order.' '.$this->order_arr[$this->act]);
        $db_obj->limit(0,1);
        $db_obj->find(true);

        // Ошибка
        if (!$db_obj->N) {
            $this->msg = 'error';
            $this->goLocation();
        }

        // Порядок текущей = порядку предыдущей(up) или следующей(down)
        $this->updateOrder($this->db_obj->id, $db_obj->{$this->field_order});

        // Порядок предыдущей(up) или следующей(down) = текущей
        $this->updateOrder($db_obj->id, $this->db_obj->{$this->field_order});

        // Переход на страницу со списком
        $this->msg = 'update_rel';
        $this->goLocation();
    }

    // Проверяет порядок
    function checkOrder()
    {
        $db_obj = DB_DataObject::factory($this->db_obj->__table);
        $db_obj->orderBy($this->field_order);
        $db_obj->find();
        while ($db_obj->fetch()) {
            if (++$number != $db_obj->{$this->field_order}) {
                $this->updateOrder($db_obj->id, $number);
            }
        }
    }

    // Делает update
    function updateOrder($id, $value)
    {
        $this->db_obj->query("UPDATE ".$this->db_obj->__table." SET ".$this->field_order."=".$value." WHERE id=".$id);
    }
}
?>