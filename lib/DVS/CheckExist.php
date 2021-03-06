<?php
/*////////////////////////////////////////////////////////////////////////////
autoru/lib
------------------------------------------------------------------------------

------------------------------------------------------------------------------
$Id: CheckExist.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

class CheckExist
{

    /* ���� ��� �������� �� ������������ */
    var $_optionDuplicateFields = array();

    /**
    * �������� ������ �� ��������� ������, ���������� ��� ������
    * @param $action - string �������� (���������� === new ��� ��������������)
    * @param $server - string ����� �������
    * @param $options - array ������ ����� � �������
    * @param $tableValues - array ������ ������ ��� ��������
    * @return string - ��� ������
    */
    function checkValues($action, $server, $options = array(NULL), $tableValues = array(NULL))
    {
        // �������������� ���������
        if (!$this->initOptions($server, $options, $tableValues)) {
            return '';
        }

        if ($action == 'new') {
            // �������� �� ������������ ������������ ����������
            if (!$this->_checkDuplicate()) {
                return 'error_exist';
            }
            // �������� � ���������� �� ���������� ���-�� ������� �� ������ ������������
            if (!$this->_checkCountByUserId()) {
                return 'error_count';
            }
        }
    }

    /**
    * ������� ������ DB_DataObject ��� ������� �����������
    * @param $server - string ����� �������
    * @return object|false - object - ������ DB_DataObject, false - ��� ������� ��� �� ������� ������� � �������
    */
    function _createCheckTableObj()
    {
        // �������������� ������ � �� �������
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

        // ������ ������ DB_DataObject
        if ($this->server_obj->table_sale) {
            return DVS_Dynamic::dbFactory($this->server_obj->table_sale);
        }

        return false;
    }

    /**
    * �������� �� ������������ ������������ ����������
    * @param
    * @return true|false - true - ���������� �� �����������, false - �����������
    */
    function _checkDuplicate()
    {
        $db_obj = $this->_createCheckTableObj();

        if ($this->tableFields[$this->_optionClientField] && $this->tableValues[$this->_optionClientField]) {
            // �������� ��� �� ����
            $db_obj->{$this->_optionClientField} = $this->tableValues[$this->_optionClientField];
        } else {
            // �������� ��� ��� ����
            $db_obj->{$this->_optionUserField} = $this->tableValues[$this->_optionUserField];
        }

        // ��������� �������� ��� ��������
        foreach ($this->_optionDuplicateFields as $field) {
            $db_obj->$field = $this->tableValues[$field];
        }

        // ���������� �����������
        if ($db_obj->count()) {
            return false;
        }

        return true;
    }

    /**
    * �������� �� ���-�� ���������� �� user_id
    * @param
    * @return true|false - true - ���-�� ���������� �� ��������� ���������� ��������, false - ���������
    */
    function _checkCountByField()
    {
        $db_obj = $this->_createCheckTableObj();

        // ��� ������������
        $db_obj->whereAdd($this->_optionField.'='.$this->tableValues[$this->_optionField]);

        // ���-�� ��������� ����������
        if ($db_obj->count()) {
            return false;
        }

        return true;
    }
}
?>
