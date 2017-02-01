<?php
/*////////////////////////////////////////////////////////////////////////////
autoru/lib
------------------------------------------------------------------------------

------------------------------------------------------------------------------
$Id: CheckExist.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

class CheckExist
{

    /* Поля для проверки на дублирование */
    var $_optionDuplicateFields = array();

    /**
    * Проверка данных на нарушение правил, возвращает код ошибки
    * @param $action - string Операция (добавление === new или редактирование)
    * @param $server - string Алиас проекта
    * @param $options - array Массив опций в проекте
    * @param $tableValues - array Массив данных для проверки
    * @return string - код ошибки
    */
    function checkValues($action, $server, $options = array(NULL), $tableValues = array(NULL))
    {
        // Инициализируем параметры
        if (!$this->initOptions($server, $options, $tableValues)) {
            return '';
        }

        if ($action == 'new') {
            // Проверка на дублирование добавленного объявления
            if (!$this->_checkDuplicate()) {
                return 'error_exist';
            }
            // Проверка и блокировка на допустимое кол-во записей от одного пользователя
            if (!$this->_checkCountByUserId()) {
                return 'error_count';
            }
        }
    }

    /**
    * Создать объект DB_DataObject для таблицы предложений
    * @param $server - string Алиас проекта
    * @return object|false - object - объект DB_DataObject, false - нет проекта или не указана таблица в проекте
    */
    function _createCheckTableObj()
    {
        // Инициализируем данные о бд проекта
        if (!isset($this->server_obj)) {
            $this->server_obj = DVS_Dynamic::dbFactory('servers');
            $this->server_obj->get('server', $this->server);

            if (!$this->server_obj->N) {
                return false;
            }

            global $_DB_DATAOBJECT;
            $_DB_DATAOBJECT['CONFIG']['database_'.$this->server_obj->db] = 'mysql://'.DB_USER.':'.DB_PASS.'@'.$this->server_obj->host.'/'.$this->server_obj->db;
            $_DB_DATAOBJECT['CONFIG']['table_'.$this->server_obj->table_sale] = $this->server_obj->db;
            $_DB_DATAOBJECT['CONFIG']['class_location_'.$this->server_obj->db] = PROJECT_ROOT.'../'.$this->server_obj->server.'/DataObjects/';
            $_DB_DATAOBJECT['CONFIG']['class_prefix_'.$this->server_obj->db] = $this->server_obj->class_prefix;
        }

        // Вернем объект DB_DataObject
        if ($this->server_obj->table_sale) {
            return DVS_Dynamic::dbFactory($this->server_obj->table_sale);
        }

        return false;
    }

    /**
    * Проверка на дублирование добавленного объявления
    * @param
    * @return true|false - true - объявление не дублируется, false - дублируется
    */
    function _checkDuplicate()
    {
        $db_obj = $this->_createCheckTableObj();

        if ($this->tableFields[$this->_optionClientField] && $this->tableValues[$this->_optionClientField]) {
            // Проверка для юр лица
            $db_obj->{$this->_optionClientField} = $this->tableValues[$this->_optionClientField];
        } else {
            // Проверка для физ лица
            $db_obj->{$this->_optionUserField} = $this->tableValues[$this->_optionUserField];
        }

        // Проставим значения для проверки
        foreach ($this->_optionDuplicateFields as $field) {
            $db_obj->$field = $this->tableValues[$field];
        }

        // Объявление дублируется
        if ($db_obj->count()) {
            return false;
        }

        return true;
    }

    /**
    * Проверка на кол-во объявлений по user_id
    * @param
    * @return true|false - true - кол-во объявлений не превышает допустимое значение, false - превышает
    */
    function _checkCountByField()
    {
        $db_obj = $this->_createCheckTableObj();

        // Для пользователя
        $db_obj->whereAdd($this->_optionField.'='.$this->tableValues[$this->_optionField]);

        // Кол-во превышает допустимое
        if ($db_obj->count()) {
            return false;
        }

        return true;
    }
}
?>
