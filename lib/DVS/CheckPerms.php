<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Среда разработки веб проектов AUTO.RU
Класс проверки прав пользователей
------------------------------------------------------------------------------
$Id: CheckPerms.php 83 2011-01-17 18:29:41Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

class DVS_CheckPerms
{
    // Объект класса настроек DVS_Layuot
    var $layout_obj;

    // Объект таблицы бд
    var $db_obj;

    // Таблица
    var $op;

    // Действие
    var $act;

    // Массив прав для действия по ролям пользователя
    var $perms_arr;

    // Конструктор
    function DVS_Checkperms($layout_obj, $db_obj, $op, $act, $role, $perms_arr = null)
    {
        // Инициализируем объект настроек проекта
        $this->layout_obj =& $layout_obj;

        // Инициализируем объект таблицы
        $this->db_obj =& $db_obj;

        // Инициализируем название таблицы
        $this->op = $op;

        // Инициализируем действие
        $this->act = $act;

        // Инициализируем тип пользователя
        $this->role = $role;

        // Инициализирует права на действие
        if (isset($this->db_obj->perms_arr[$this->act])) {
            $this->perms_arr = $this->db_obj->perms_arr[$this->act];
        } else {
            $this->perms_arr = $perms_arr;
        }
     }

    // Основная функция проверки прав
    function checkPerms()
    {
        global $_DVS;

        // Дополнительная проверка прав 
        if (method_exists($this->db_obj, 'checkPermsOp') && !$this->db_obj->checkPermsOp()) {
            return 'error_forbidden5';
        }

        // Если админ и не заданы права, все разрешить 
        if ($this->role == 'aa' && !isset($this->perms_arr[$this->role])) {
            return true;
        }

        // Проверка таблицы и действия 
        if (!$this->checkOp()) {
            return 'error_forbidden1';
        }

        // Проверка действия 
        if (!$this->checkAct()) {
            return 'error_forbidden2';
        }

        // Проверка на изменение записи 
        if (in_array($this->act, array_keys($_DVS['CONFIG']['DVS_DEFAULT_CLASSES'])) && isset($_GET['id']) && !$this->checkPermsByField($this->layout_obj->perms_fieldname)) {
            return 'error_forbidden3';
        }
        
        // Проверка прав доступа редактора 
        if ($this->role == 'ar' && !$this->checkPermsRedactor()) {
            //return 'error_forbidden4';
        }

        return true;
    }

    // Проверяет разрешение на доступ к чтению таблицы
    function checkOp()
    {
        // Список разрешенных таблиц не задан
        if (!is_array($this->layout_obj->op_arr)) {
            return false;
        }

        // Таблице нет в списке разрешенных
        if (!in_array($this->op, $this->layout_obj->op_arr)) {
            return false;
        }

        return true;
    }

    // Проверяет разрешение на действие this->perms_arr
    function checkAct()
    {
        // Права для действия не заданы
        if (!is_array($this->perms_arr)) {
            return false;
        }
        // Действие для роли запрещено
        if (!$this->perms_arr[$this->role]) {
            return false;
        }

        return true;
    }

    // Проверяет разрешение на изменение записи по полю this->layout_obj->project_obj->perms_fieldname
    function checkPermsByField($fieldname = 'user_id')
    {
        /*
        // Поле не определенно
        if (!$fieldname) {
            return false;
        }
        */
        // Проверим  по полю*/
        if (isset($this->db_obj->$fieldname)) {
            if ($fieldname == 'user_id') {
                return $_SESSION['_authsession']['data']['id'] == $this->db_obj->$fieldname;
            } else {
                return $_SESSION['_authsession']['data'][$fieldname] == $this->db_obj->$fieldname;
            }
        } else {
            // Проверим запись в связной таблице
            return $this->checkLinkedTables($this->layuot_obj->linked_table_arr[$this->op]);
        }
    }

    // Проверка разрешение на изменение записи в связных таблиц this->layout_obj->linked_table_arr
    function checkLinkedTables($linked_arr)
    {
        // Связной таблицы нет
        if (!is_array($linked_arr)) {
            return true;
        }
        
        // Получить запись из связной таблицы
        $obj = DB_DataObject::factory($linked_arr[$this->op]['op']);
        $obj->get($this->db_obj->{$linked_arr[$this->op]['fieldname']});

        return $obj->N && $_SESSION['_authsession']['data'][$fieldname] == $obj->$fieldname;
    }

    // Проверка прав доступа редактора
    function checkPermsRedactor()
    {

    }
}
?>
