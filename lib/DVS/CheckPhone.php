<?php
/**
* @package aservice
* lib/DVS
* ------------------------------------------------------------------------------
* Класс проверок телефонов
* ------------------------------------------------------------------------------
* $Id: CheckPhone.php 21 2010-10-28 06:25:35Z xxserg $
*/

define('ERROR_PHONE_REPEAT', 'В номере все цифры повторяются!');
define('ERROR_PHONE_MSCCODE', 'Неправильный код для города Москва!');
define('ERROR_PHONE_INCORRECT', 'Неправильный формат! Пример: (495) 123-34-78');
define('ERROR_PHONE_NOT_CODE', 'Не указан код города!');
define('ERROR_PHONE_ZERO', 'Недопустимый номер телефона!');
define('ERROR_PHONE_COUNT_10', 'В номере должно быть 10 цифр!');
define('ERROR_PHONE_COUNT_7', 'В номере должно быть 7 цифр!');
define('ERROR_PHONE_COUNT', 'Неправильное количество цифр в номере!');
define('ERROR_REPEAT_PHONE', 'Данный номер телефона уже указан!');
define('ERROR_PHONE_EXIST', 'Один из введенных номеров телефона уже зарегистрирован в системе!');

class DVS_CheckPhone
{

    var $_optionPhoneFields = array('phone1', 'phone2', 'phone3');

    // Проверка номер на корректность написания
    function checkCorrectPhone($phone)
    {
        // (код_города) (3)|код_города 3 номер_телефона 3+2+2
        //$pattern = "/^[8\+7]{0,}(\([0-9]{3}\)|[0-9]{3})[ ]{0,}([0-9]{3})[- ]?([0-9]{2})[- ]?([0-9]{2})$/";
        $pattern = "/^[87]{0,}([0-9]{3})([0-9]{7})$/";
        preg_match($pattern, preg_replace('/[^0-9]/', '', $phone), $match);
        return $match ? $match : false;
    }

    // Проверка на повторение цифр в номере
    function checkPhoneRepeat($number)
    {
        return preg_match('/^(0+|1+|2+|3+|4+|5+|6+|7+|8+|9+)$/', $number)? false : true;
    }

    // Проверка телефонных кодов Москвы
    function checkMSCCode($code)
    {
        $msk_code = array('095', '495', '477', '499', '501', '901', '902', '903', '905', '906', '909', '910', '911', '915', '916', '917', '921', '926', '960');
        return in_array($code, $msk_code) ? true : false;
    }

    // Проверка начало номера на 0
    function checkZeroPhone($number, $code)
    {
        $valid_codes = array('903', '905', '906', '915', '916');
        return $number[0] == '0' && (!$code || !in_array($code, $valid_codes)) ? false : true;
    }

    /**
    * Проверка телефона по всем параметрам
    * @param $phone
    * @return код ошибки | ''
    */
    function checkFormPhoneField($phone)
    {
        // Проверка формата и разбиение
        if (!$phone_arr = DVS_CheckPhone::checkCorrectPhone($phone)) {
            return ERROR_PHONE_INCORRECT;
        }
        //echo '<pre>';
        //print_r($phone_arr);
        // Номер телефона без кода города и страны
        $number = $phone_arr[2];
        // Код города
        $code = $phone_arr[1];

        // Проверка начало номера на 0
        if (!DVS_CheckPhone::checkZeroPhone($number, $code)) {
            return ERROR_PHONE_ZERO;
        }
        // Проверка на кол-во цифр - 10
        $number_cnt = strlen($number);
        $count      = $number_cnt + strlen($code);
        if (!$code && $number_cnt != 7) {
            return ERROR_PHONE_COUNT_7;
        }
        if ($code && $count != 10) {
            return ERROR_PHONE_COUNT_10;
        }
        // Проверка на повторение цифр
        //if (!DVS_CheckPhone::checkPhoneRepeat($number)) {
        //    return ERROR_PHONE_REPEAT;
        //}
        // Проверка телефонных кодов Москвы
        if ($code && !DVS_CheckPhone::checkMSCCode($code)) {
            return ERROR_PHONE_MSCCODE;
        }
        return '';
    }

    /**
    * Проверка всех телефонов в форме
    * @param $fields array(название_поля => значение)
    * @return ошибка array(название_поля => ошибка) | ''
    */
    function checkFormPhone($fields)
    {
        // Проверка телефона
        $phone_arr = array();
        foreach ($fields as $name => $phone) {
            $msg   = '';
            if ($phone) {
                $phone_arr[$phone]++;
                // Проверка на дублирование
                if ($phone_arr[$phone] > 1) {
                    $msg = ERROR_REPEAT_PHONE;
                }
                // Проверка телефона
                if (!$msg) {
                    $msg = DVS_CheckPhone::checkFormPhoneField($phone);
                }
                // Ошибка
                if ($msg) {
                    $arr[$name] = $msg;
                } else {
                    $this->tableValues[$name] = DVS_CheckPhone::formatPhone($phone);
                }
            }
        }
        //print_r($tableValues);
        /*
        if ($msg = DVS_CheckPhone::_checkCountByPhone($tableValues)) {
            $arr['phone'] = $msg;
        }
        */
        return $arr ? $arr : '';
    }

    /**
    * Возвращает стандартную запись телефона
    * (495) 123-45-67
    * @param $phone string
    * @return string
    */
    function formatPhone($phone)
    {
        if (!$arr = DVS_CheckPhone::checkCorrectPhone($phone)) {
            return '';
        }
        $number = '';
        $number .= '('.$arr[1].') ';
        $number .= substr($arr[2], 0, 3).'-'.substr($arr[2], 3, 2).'-'.substr($arr[2], 5, 2);
        return $number;
    }

    /**
    * Проверка на кол-во объявлений по телефону
    * @param
    * @return true|false - true - кол-во объявлений не превышает допустимое значение, false - превышает
    */
    function checkCountByPhone($tableValues, $id = 0)
    {
        $_optionPhoneFields = array_keys($tableValues);
        // Нет телефонов в таблице
        if (!count($_optionPhoneFields)) {
            return true;
        }

        // Составим запрос для поиска объявлений
        $templateQuery = '';
        foreach ($_optionPhoneFields as $phoneField) {
            $templateQuery .= $phoneField."='{phone}' OR ";
        }
        $templateQuery = substr($templateQuery, 0, -4);
        $query = '';
        foreach ($_optionPhoneFields as $phoneField) {
            if ($tableValues[$phoneField]) {
                $query .= str_replace('{phone}', $tableValues[$phoneField], $templateQuery).' OR ';
            }
        }
        // Поиск
        //DB_DataObject::DebugLevel(1);
        $db_obj = DB_DataObject::factory('members');

        // Без текущей записи
        if ($id) {
            $db_obj->whereAdd('id!='.$id);
        }

        $db_obj->whereAdd('('.substr($query, 0, -4).')');
        $db_obj->selectAdd();
        $db_obj->selectAdd(implode(',', $_optionPhoneFields));
        //$db_obj->find(true);

        if ($db_obj->count()) {
            return ERROR_PHONE_EXIST;
        } else {
            return '';
        }
        /*
        // Подсчет кол-ва объявлений на каждый телефон
        $cnt_phone_arr = array();
        while ($db_obj->fetch()) {
            $row_phone_arr = array();
            foreach ($_optionPhoneFields as $phoneField) {
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
        */
    }
}

?>
