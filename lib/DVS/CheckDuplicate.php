<?php
/*////////////////////////////////////////////////////////////////////////////
autoru/lib
------------------------------------------------------------------------------
Библиотека для команд блокировки пользователей
------------------------------------------------------------------------------
$Id: CheckDuplicate.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

class CheckExist
{

    /* Поля для телефонов */
    var $_optionPhoneFields = array('phone1', 'phone2', 'phone3');
    /* Поле срока окончания блокировки */
    var $_optionExpireDate = '';
    /* Проверяемые данные */
    var $tableValues;
    /* Поля проверяемой таблицы */
    var $tableFields;
    /* Поля для проверки на дублирование */
    var $_optionDuplicateFields = array();

    /**
    * Инициализируем параметры блокировки
    * @param $server - string Алиас проекта
    * @param $options - array Массив опций в проекте
    * @param $tableValues - array Массив данных для проверки
    * @return true|false - true, если параметры установились, false - таблицы для проверки в проекте не существует
    */
    function initOptions($server, $options = array(NULL), $tableValues = array(NULL))
    {
        // Инициализация параметров
        $availableOptions = array(
            'MaxCount',
            'UserField',
            'ClientField',
            'SmsField',
            'PhoneFields',
            'DuplicateFields',
            'ExpireDate',
        );
        foreach ($options as $key => $value) {
            if (in_array($key, $availableOptions)) {
                $property = '_option'.$key;
                $this->$property = $value;
            }
        }

        // Поля проверяемой таблицы
        $this->server = $server;
        $db_obj = $this->_createCheckTableObj();
        if ($db_obj === false) {
            return false;
        }
        $this->tableFields = $db_obj->table();

        // Установка срока окончания блокировки по умолчанию
        if ($this->_optionExpireDate == '') {
            $this->_optionExpireDate = date('Y-m-d', strtotime('+ 6 month'));
        }

        // Поля для проверки дублирования
        if (!count($this->_optionDuplicateFields)) {
            foreach ($this->tableFields as $field => $type) {
                if ($field != 'id' && $field != $this->_optionClientField && $field != $this->_optionUserField) {
                    $this->_optionDuplicateFields[] = $field;
                }
            }
        }

        // Телефоны
        if ($this->_optionPhoneFields && !is_array($this->_optionPhoneFields)) {
            $this->_optionPhoneFields = array($this->_optionPhoneFields);
        }
        foreach ($this->_optionPhoneFields as $key => $phoneField) {
            if (!$this->tableFields[$phoneField]) {
                unset($this->_optionPhoneFields[$key]);
            }
        }

        // Проверяемые данные
        $this->tableValues = $tableValues;

        // email пользователя
        $user_obj = DB_DataObject::factory('user');
        $user_obj->get($this->tableValues[$this->_optionUserField]);
        $this->tableValues['email'] = $user_obj->email;

        // Проверим id записи
        if ($_GET['id'] && $this->tableValues['id'] != $_GET['id']) {
            $this->tableValues['id'] = $_GET['id'];
        }


        return true;
    }

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
        // Проверка на допустимое кол-во записей по телефону
        if (!$this->_checkCountByPhone()) {
            return 'error_phone_count';
        }
        // Проверка по списку blacklist'a при редактировании и добавлении
        if (!$this->_checkInBlacklist()) {
            return 'error_blocked_phone';
        }
        return '';
    }

    /**
    * Отправка пользователю письма о блокировке
    * @param
    * @return true|false
    */
    function sendLetter($to_email, $from_email)
    {
        /* Подключение конфига с константами по SMS-биллингу */
        require_once AUTORU_FOLDER.'conf/config_sms.inc';
        require_once 'HTML/Template/Sigma.php';
        $template_obj =& new HTML_Template_Sigma(AUTORU_FOLDER.TMPL_FOLDER, PROJECT_ROOT.CACHE_FOLDER.'tmpl');
        $template_obj->loadTemplateFile('blacklist_mail.tmpl', 1, 1);
        $template_obj->setVariable(array(
            'server'        => SERVER,
            'count_sale'    => $this->_optionMaxCount,
            'sms_format'    => str_replace('{user_id}', $this->tableValues[$this->_optionUserField], SMS_UNBLOCK),
            'sms_number'    => SMS_UB_NUMBER,
            'sms_price'     => SMS_UB_PRICE,
        ));
        require_once COMMON_LIB.'DVS/Mail.php';
        $mail_obj =& new DVS_Mail();
        $mail_obj->params = array(
            'body'      => $template_obj->get(),
            'from'      => $from_email ? $from_email : 'autoinfo@auto.ru',
            'to'        => $to_email,
            'subject'   => 'www.'.SERVER.'.auto.ru : Your sale was blocked',
        );
        $mail_obj->send();
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
    function _checkCountByUserId()
    {
        $db_obj = $this->_createCheckTableObj();

        // Только для физ лиц
        if ($this->tableFields[$this->_optionClientField]) {
            $db_obj->whereAdd($this->_optionClientField.'=0');
        }

        // Только неоплаченные объявления
        if ($this->tableFields[$this->_optionSmsField]) {
            $db_obj->whereAdd($this->_optionSmsField.'=0');
        }

        // Для пользователя
        $db_obj->whereAdd($this->_optionUserField.'='.$this->tableValues[$this->_optionUserField]);

        // Кол-во превышает допустимое
        if ($db_obj->count() >= $this->_optionMaxCount) {
            return false;
        }

        return true;
    }

    /**
    * Проверка на кол-во объявлений по телефону
    * @param
    * @return true|false - true - кол-во объявлений не превышает допустимое значение, false - превышает
    */
    function _checkCountByPhone()
    {
        // Нет телефонов в таблице
        if (!count($this->_optionPhoneFields)) {
            return true;
        }

        // Составим запроса для поиска объявлений
        $templateQuery = '';
        foreach ($this->_optionPhoneFields as $phoneField) {
            $templateQuery .= $phoneField."='{phone}' OR ";
        }
        $templateQuery = substr($templateQuery, 0, -4);

        $query = '';
        foreach ($this->_optionPhoneFields as $phoneField) {
            if ($this->tableValues[$phoneField]) {
                $query .= str_replace('{phone}', $this->tableValues[$phoneField], $templateQuery).' OR ';
            }
        }

        // Поиск
        $db_obj = $this->_createCheckTableObj();
        // Только для физ лиц
        if ($this->tableFields[$this->_optionClientField]) {
            $db_obj->whereAdd($this->_optionClientField.'=0');
        }
        // Только неоплаченные объявления
        if ($this->tableFields[$this->_optionSmsField]) {
            $db_obj->whereAdd($this->_optionSmsField.'=0');
        }
        // Без текущей записи
        if ($this->tableValues['id']) {
            $db_obj->whereAdd('id!='.$this->tableValues['id']);
        }
        $db_obj->whereAdd('('.substr($query, 0, -4).')');
        $db_obj->selectAdd();
        $db_obj->selectAdd($this->_optionUserField.','.implode(',', $this->_optionPhoneFields));
        $db_obj->find();

        // Подсчет кол-ва объявлений на каждый телефон
        $cnt_phone_arr = array();
        while ($db_obj->fetch()) {
            $row_phone_arr = array();
            foreach ($this->_optionPhoneFields as $phoneField) {
                if ($db_obj->{$phoneField} && !$row_phone_arr[$db_obj->{$phoneField}]) {
                    $cnt_phone_arr[$db_obj->{$phoneField}]++;
                    $row_phone_arr[$db_obj->{$phoneField}] = 1;
                }
            }
        }

        // Проверка на кол-во
        foreach ($cnt_phone_arr as $phone => $cnt) {
            if ($cnt >= $this->_optionMaxCount) {
                return false;
            }
        }
        return true;
    }

}
?>