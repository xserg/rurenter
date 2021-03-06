<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
����� �������� ����� �����
------------------------------------------------------------------------------
$Id: CheckForm.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

define('MSC_ID', 1123);
define('ERROR_PHONE_REPEAT', '� ������ ��� ����� �����������!');
define('ERROR_PHONE_MSCCODE', '������������ ��� ��� ������ ������!');
define('ERROR_PHONE_INCORRECT', '������������ ������� ��� ������������ ������!');
define('ERROR_PHONE_NOT_COUNTRY_CODE', '�� ������ ��� ������!');
define('ERROR_PHONE_ADD_COUNTRY_CODE', '�� ����� ��������� ��� ������!');
define('ERROR_PHONE_NOT_CODE', '�� ������ ��� ������!');
define('ERROR_PHONE_ZERO', '������������ ����� ��������!');
define('ERROR_PHONE_COUNT_10', '� ������ ������ ���� 10 ����!');
define('ERROR_PHONE_COUNT_7', '� ������ ������ ���� 7 ����!');
define('ERROR_PHONE_COUNT', '������������ ���������� ���� � ������!');
define('ERROR_REPEAT_PHONE', '������ ����� �������� ��� ������!');

class DVS_CheckForm
{
    // �������� ����� �� ������������ ���������
    function checkCorrectPhone($phone)
    {
        // +���_������ (���_������) �����_��������|(���_������) �����_��������|�����_��������
        //$pattern = '/^(\+[0-9]{1,3} ?\([0-9]{1,5}\) ?[0-9\- ]+|\([0-9]{1,5}\) ?[0-9\- ]+|[0-9\- ]+)$/';
        // (���_������)|���_������ �����_��������
        $pattern = "/^[8\+7]{0,}(\([0-9]{3}\)|[0-9]{3})[ ]{0,}([0-9]{3})[- ]?([0-9]{2})[- ]?([0-9]{2})$/";
        return preg_match($pattern, $phone) ? true : false;
    }

    // �������� ��� �� ������� ������
    function getCode($phone)
    {
        preg_match('/^[8\+7]{0,}(\(([0-9]{3})\)|([0-9]{3}))/', $phone, $code);
        //print_r($code);
        //preg_match('/\((.+)\)/', $phone, $code);
        return $code[2] ? $code[2] : $code[3];
    }

    // �������� ��������� ������� �� ������� ������
    function getNumber($phone)
    {
        if (!$str = strstr($phone, ')')) {
            $str = $phone;
        }
        return preg_replace('/[^0-9]/', '', $str);
    }

    // �������� ��� ������ �� ������� ������
    function getCountryCode($phone)
    {
        preg_match('/^\+([0-9]{1,3})/', $phone, $country_code);
        return $country_code[1];
    }

    // �������� �� ���������� ���� � ������
    function checkPhoneRepeat($number)
    {
        return preg_match('/^(0+|1+|2+|3+|4+|5+|6+|7+|8+|9+)$/', $number)? false : true;
    }

    // �������� ���������� ����� ������
    function checkMSCCode($code)
    {
        $msk_code = array('095', '495', '477', '499', '501', '901', '902', '903', '905', '906', '909', '910', '911', '915', '916', '917', '921', '926', '960');
        return in_array($code, $msk_code) ? true : false;
    }

    // �������� ������ ������ �� 0
    function checkZeroPhone($number, $code)
    {
        $valid_codes = array('903', '905', '906', '915', '916');
        return $number[0] == '0' && (!$code || !in_array($code, $valid_codes)) ? false : true;
    }

    // �������� ��������
    function checkFormPhoneField($phone, $check_country, $city_id)
    {
        // �������� �� ���������� ����
        if (!DVS_CheckForm::checkCorrectPhone($phone)) {
            return ERROR_PHONE_INCORRECT;
        }
        // ����� �������� ��� ���� ������ � ������
        $number = DVS_CheckForm::getNumber($phone);
        // ��� ������
        $code = DVS_CheckForm::getCode($phone);
        // ��� ������
        $country_code = DVS_CheckForm::getCountryCode($phone);
        // ��� ������ �� �����
        if (!$check_country && $country_code) {
            return ERROR_PHONE_ADD_COUNTRY_CODE;
        }
        // ��� ������ �����
        if ($check_country && !$country_code) {
            return ERROR_PHONE_NOT_COUNTRY_CODE;
        }
        // �������� ������� ���� ������
        if ($city_id && !$code) {
            return ERROR_PHONE_NOT_CODE;
        }
        // �������� ������ ������ �� 0
        if (!DVS_CheckForm::checkZeroPhone($number, $code)) {
            return ERROR_PHONE_ZERO;
        }
        // �������� �� ���-�� ���� - 10
        $number_cnt = strlen($number);
        $count      = $number_cnt + strlen($code);
        if (!$code && $number_cnt != 7) {
            return ERROR_PHONE_COUNT_7;
        }
        if ($code && $count != 10) {
            return ERROR_PHONE_COUNT_10;
        }
        // �������� �� ���������� ����
        if (!DVS_CheckForm::checkPhoneRepeat($number)) {
            return ERROR_PHONE_REPEAT;
        }
        // �������� ���������� ����� ������
        if ($code && $city_id == MSC_ID && !DVS_CheckForm::checkMSCCode($code)) {
            return ERROR_PHONE_MSCCODE;
        }
        return '';
    }

    // �������� ��������� � �����
    function checkFormPhone($fields)
    {
        // ���� ������ - 0
        $check_country = !$fields['geo_id'] || preg_match('/^1:/', $fields['geo_id']) ? 0 : 1;
        // ID ������
        $city_id = 0;
        if (isset($fields['city_id'])) {
            $city_id = $fields['city_id'];
        } else if (isset($fields['geo_id'])) {
            $geo_arr = split(':', $fields['geo_id']);
            $city_id = $geo_arr[2];
        }
        // �������� ��������
        $phone_arr = array();
        for ($i = 1; $i <= 3; $i++) {
            $field_opt = DVS_CheckForm::getFieldVal($fields, $i);
            $phone = str_replace($fields['dialcode'], '', $field_opt['val']);
            $msg   = '';
            if ($phone) {
                $phone_arr[$phone]++;
                // �������� �� ������������
                if ($phone_arr[$phone] > 1) {
                    $msg = ERROR_REPEAT_PHONE;
                }
                // �������� ��������
                if (!$msg) {
                    $msg = DVS_CheckForm::checkFormPhoneField($field_opt['val'], $check_country, $city_id);
                }
                // ������
                if ($msg) {
                    $arr[$field_opt['name']] = $msg;
                }            
            }
        }
        return $arr ? $arr : true;
    }

    // �������� ���� ��� ��������
    function getFieldVal($fields, $index)
    {
        if (isset($fields['p'.$index]['phone'])) {
            return array('val' => $fields['p'.$index]['phone'], 'name' => 'p'.$index);
        }
        if (isset($fields['phone'.$index])) {
            return array('val' => $fields['phone'.$index], 'name' => 'phone'.$index);
        }
        return '';
    }

    // ���������� ����������� ������ ��������
    function formatPhone($phone)
    {
        $phone = preg_replace('/^[8\+7]{0,}/', '', $phone);
        if (!preg_match('/^(\+[0-9]+)?(\([0-9]+\))?([0-9]+)$/', preg_replace('/[^0-9\+\(\)]/', '', $phone), $arr)) {
            return '';
        }
        $number = '';
        if ($arr[1]) {
            $number .= $arr[1].' ';
        }
        if ($arr[2]) {
            $number .= $arr[2].' ';
        }
        $number .= substr($arr[3], 0, 3).'-'.substr($arr[3], 3);
        return $number;
    }

    // �������� �� ������������� ������������
    function checkUserExists($email)
    {
        if ($email) {
            $user_obj = DB_DataObject::factory('user');
            $user_obj->get('email', $email);
            if (!$user_obj->N) {
                return false;
            }
        }
        return true;
    }

}

?>