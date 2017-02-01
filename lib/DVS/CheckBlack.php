<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Класс проверки нарушения правил
------------------------------------------------------------------------------
$Id: CheckBlack.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

class DVS_CheckBlack
{
    // Допустимое кол-во объявлений
    var $_maxCount = 3;
    // Таблица объявлений
    var $_checkTable = 'sale';
    // Поле user_id
    var $_userField = 'user_id';
    // Поле client_id
    var $_clientField = 'client_id';
    // Поля для телефонов
    var $_phoneFields = array('phone1', 'phone2', 'phone3');
    // Таблица списка нарушителей
    var $_blacklistTable = 'blacklist';
    // Причина блокировки
    var $reason_arr = array(
        1   => array('error_blocked', 'Превышение'),
        2   => array('error_phone', 'Превышение по телефону'),
        3   => array('error_exist', 'Дублирование записи!'),
        4   => array('error_phone', 'Присутствие в списке заблокированных'),
    );
    // Проверяемые данные
    var $values;
    // Поля проверяемой таблицы
    var $checkTableFields;
    // Поля для проверки на дублирование
    var $_duplicateFields = array();

    function DVS_CheckBlack($values=array(NULL), $options=array(NULL))
    {
        // Инициализация параметров
        $availableOptions = array(
            'maxCount',
            'checkTable',
            'userField',
            'clientField',
            'phoneFields',
            'blacklistTable',
            'duplicateFields',
        );
        foreach ($options as $key => $value) {
            if (in_array($key, $availableOptions)) {
                $property = '_'.$key;
                $this->$property = $value;
            }
        }
        // Проверяемые данные
        $this->values = $values;
        // Поля проверяемой таблицы
        $db_obj = DB_DataObject::factory($this->_checkTable);
        $this->checkTableFields = $db_obj->table();
        // Поля для проверки дублирования
        if (!count($this->_duplicateFields)) {
            foreach ($this->checkTableFields as $field => $type) {
                if ($field != 'id' && $field != $this->_clientField && $field != $this->_userField) {
                    $this->_duplicateFields[] = $field;
                }
            }
        }
        // email пользователя
        $user_obj = DB_DataObject::factory('user');
        $user_obj->get($this->values[$this->_userField]);
        $this->values['email'] = $user_obj->email;
    }

    // Проверка на кол-во объявлений
    function checkCount()
    {
        $db_obj = DB_DataObject::factory($this->_checkTable);
        if ($this->checkTableFields[$this->_clientField]) {
            $db_obj->whereAdd($this->_clientField.'=0');
        }
        $db1_obj = $db_obj;

        // Проверка на кол-во по user_id
        $db_obj->whereAdd($this->_userField.'='.$this->values[$this->_userField]);
        //if ($db_obj->count(DB_DATAOBJECT_WHEREADD_ONLY) >= $this->_maxCount) {
        if ($db_obj->count() >= $this->_maxCount) {
            return -1;
        }
        // Проверка на кол-во по телефонам
        if (!$this->_phoneFields) {
            return 0;
        }
        if (!is_array($this->_phoneFields)) {
            $this->_phoneFields = array($this->_phoneFields);
        }      
        $templateQuery = '';
        foreach ($this->_phoneFields as $key => $phoneField) {
            if (!$this->checkTableFields[$phoneField]) {
                unset($this->_phoneFields[$key]);
            } else {
                $templateQuery .= $phoneField."='{phone}' OR ";
            }
        }
        $templateQuery = substr($templateQuery, 0, -4);
        if (!count($this->_phoneFields)) {
            return 0;
        }
        $query = '';
        foreach ($this->_phoneFields as $phoneField) {
            if ($this->values[$phoneField]) {
                $query .= str_replace('{phone}', $this->values[$phoneField], $templateQuery).' OR ';
            }
        }
        $db1_obj->whereAdd('('.substr($query, 0, -4).')');
        $db1_obj->selectAdd();
        $db1_obj->selectAdd($this->_userField.','.implode(',', $this->_phoneFields));
        $db1_obj->find();
        $cnt_phone_arr = array();
        while ($db1_obj->fetch()) {
            $row_phone_arr = array();
            foreach ($this->_phoneFields as $phoneField) {
                if ($db1_obj->{$phoneField} && !$row_phone_arr[$db1_obj->{$phoneField}]) {
                    $cnt_phone_arr[$db1_obj->{$phoneField}]++;
                    $row_phone_arr[$db1_obj->{$phoneField}] = 1;
                }
            }
        }
        foreach ($cnt_phone_arr as $phone => $cnt) {
            if ($cnt >= $this->_maxCount) {
                return 2;
            }
        }
        return 0;
    }

    // Проверка на дублирование добавленного объявления
    function checkDuplicate()
    {
        $db_obj = DB_DataObject::factory($this->_checkTable);
        if ($this->checkTableFields[$this->_clientField] && $this->values[$this->_clientField]) {
            $db_obj->{$this->_clientField} = $this->values[$this->_clientField];
        } else {
            $db_obj->{$this->_userField} = $this->values[$this->_userField];
        }
        foreach ($this->_duplicateFields as $field) {
            $db_obj->$field = $this->values[$field];
        }
        if ($db_obj->count()) {
            return 3;
        }
        return 0;
    }

    // Проверка по списку blacklist'a
    function checkBlacklist()
    {
        $blacklist_obj = DB_DataObject::factory($this->_blacklistTable);
        $query  = $this->_userField."=".$this->values[$this->_userField];
        $query .= " OR email='".$this->values['email']."'";
        foreach ($this->_phoneFields as $phoneField) {
            if ($this->values[$phoneField]) {
                $query .= " OR phone='".$this->values[$phoneField]."'";
            }
        }
        $blacklist_obj->whereAdd('('.$query.')');
        if ($blacklist_obj->count()) {
            return 4;
        }
        return 0;
    }

    // Добавление в список нарушителей
    function insertInBlacklist($error_code)
    {
        if ($error_code >= 0) {
            return ;
        }
        $blacklist_obj          = DB_DataObject::factory($this->_blacklistTable);
        $blacklist_obj->user_id = $this->values[$this->_userField];
        $blacklist_obj->email   = $this->values['email'];
        $blacklist_obj->phone   = $this->values[$this->_phoneFields[0]];
        $blacklist_obj->reason  = abs($error_code);
        if (!$blacklist_obj->count()) {
            $blacklist_obj->insert();
        }
        return ;
    }

    // Проверка данных на нарушение правил, возвращает код ошибки
    function checkValues($action)
    {
        if ($action == 'new') {
            // Проверка на дублирование добавленного объявления
            if ($error_code = $this->checkDuplicate()) {
                return $this->reason_arr[abs($error_code)][0];
            }
            // Проверка на допустимое кол-во записей, блокирует только на превышение по кол-ву записей от одного пользователя
            // при совпадении номера телефона, не добавляет запись, но и не блокирует
            if ($error_code = $this->checkCount()) {
                $this->insertInBlacklist($error_code);
                return $this->reason_arr[abs($error_code)][0];
            }
        }
        // Проверка по списку blacklist'a при редактировании и добавлении
        // Выдает только сообщение об ошибке, не блокирует
        if ($error_code = $this->checkBlacklist()) {
            return $this->reason_arr[abs($error_code)][0];
        }
        return 0;
    }
}

?>
