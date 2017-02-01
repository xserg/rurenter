<?php
/**
 * lib/DVS
 * ------------------------------------------------
 * @package DVS
 * @author Serg Fomin xxserg@gmail.com
 * Класс создания динамических страниц
 * ------------------------------------------------
 * $Id: Dynamic.php 496 2014-05-23 15:00:18Z xxserg $
 */

// Параметры объекта DB_DataObject
// Для ликвидации конфликта Zend Optimazer и DB_DataObject
define('DB_DATAOBJECT_NO_OVERLOAD', 0);

require_once COMMON_LIB.'DVS/Page.php';
require_once COMMON_LIB.'DVS/CheckPerms.php';

class DVS_Dynamic extends DVS_Page
{
    /* Объект DB_DataObject */
    var $db_obj;

    /* Строка запроса в урл */
    var $qs;

    /* Объект проверки прав доступа */
    var $perm_obj;

    var $perms_arr;

    var $page_arr=array();

    /*
    Генерирует динамическую страницу
    - создает объект db_obj,perm_obj
    - если есть _GET['id'] вызывается this->getRow
    - вызов perm_obj->checkPerms
    - layuot_obj->getPageData - инициализирует page_arr 
    - this->getPageData - получает основной контент
    - возвращает this->parsePage (готовая страница)
    */
    function showPage()
    {
        global $_DVS;
        if (in_array($this->act, array_keys($_DVS['CONFIG']['DVS_DEFAULT_CLASSES']))){
            //$this->hide_blocks = 'RIGHT';
        }
        //Создание объекта DB_DataObject таблицы $this->op
        $this->db_obj     = $this->createDbObj($this->op, $this->role);
        $this->db_obj->qs = '?op='.$this->op;
        if (isset($this->db_obj->qs_arr) && is_array($this->db_obj->qs_arr)) {
            $this->db_obj->qs = $this->getQS($this->db_obj->qs, $this->db_obj->qs_arr);
        }
        // Определить qs
        if (method_exists($this->db_obj, 'getQS')) {
            $this->db_obj->getQS();
        }
        //print_r($this->db_obj);
        if (defined('TEST_DEBUG')) {
            DB_DataObject::debugLevel(TEST_DEBUG);
        }
        if (isset($_GET['id']) && $_GET['id'] == intval($_GET['id'])) {
            $this->getRow();
            if (!$this->db_obj->N) {
                $this->show404();
                $this->nocache = true;
            }
        }
        /* Проверка прав пользователя делается после получения записи, для возможности проверки по полям */
        $this->perm_obj = & new DVS_Checkperms($this->layout_obj, $this->db_obj, $this->op, $this->act, $this->role, $this->perms_arr);
        $return_perms   = $this->perm_obj->checkPerms();
        //echo $return_perms;
        if ($return_perms !== true) {
            $this->msg = $return_perms;
            $this->error = true;
            //header('Location: /'.$return_perms.'.html');
            //exit;
        }

        // Создадим массив данных страницы (данные переопределяются в последовательсти: по умолчанию, проектные, страничные)
        //$this->page_arr = array_merge($this->layout_obj->getPageData(&$this->template_obj), $this->page_arr);


        if (!$this->error) {
            $this->page_arr = $this->getPageData();
        }

        $this->createTemplateObj();
        $this->loadTemplate();
        $this->layout_obj->getPageData($this->template_obj);
        
        /**
         * Добавление данных из db_obj
         */
        if (isset($this->db_obj->page_arr)) {
            $this->page_arr = array_merge($this->page_arr, $this->db_obj->page_arr);
        }
        if (isset($this->db_obj->src_files)) {
            if (is_array($this->src_files)) {
                $this->src_files = array_merge($this->src_files, $this->db_obj->src_files);
            } else {
                $this->src_files = $this->db_obj->src_files;
            }
        }
        if (isset($this->msg)) {
            $this->showMessage($this->msg);
        }
        $this->closeDb();
        return $this->parsePage();
    }


    function getQS($qs, $qs_arr)
    {
        /* Инициализируем qs в объектах */
        if (is_array($qs_arr)) {
            foreach ($qs_arr as $field) {
                if (isset($_GET[$field]) && !empty($_GET[$field])) {
                    $qs .= '&'.$field.'='.$_GET[$field];
                }
            }
        }
        //echo $qs;
        return $qs;
    }

    function getRow()
    {
        $this->db_obj->get($_GET['id']);
    }

    /* Переход по ссылке */
    function goLocation()
    {
        if ($this->db_obj->qs) {
            /* Запомним сообщение в сессии */
            $_SESSION['message'] = $this->msg;
            
            header('Location: '.$this->db_obj->qs);
            exit;
        }
    }

    /*
    Объект db_obj создается методом createDbObj(),
    к-рый определяет существует ли файл с названием класа DBO_TableName_Role и возвращает объект класса,
    если нет - DB_DataObject::factory.
    */
    function createDbObj($op, $role='iu')
    {
        global $_DVS;
        require_once 'PEAR.php';
        $db_options = &PEAR::getStaticProperty('DB_DataObject','options');
        $db_options = array(
            'database'                 => DB_DSN,
            'schema_location'          => PROJECT_ROOT.'conf',
            'class_location'           => PROJECT_ROOT.'DataObjects/',
            'require_prefix'           => '',
            'class_prefix'             => 'DBO_',
            'db_driver'                => DB_DRIVER,
            //'quote_identifiers'         => true

        );

        $filename = ucfirst($op).'_'.ucfirst($_DVS['CONFIG']['INAMES'][$role]);
        $classname = $db_options['class_prefix'].$filename;

        if (file_exists($db_options['class_location'].$filename.'.php')) {
            require_once $db_options['class_location'].ucfirst($op).'.php';
            require_once $db_options['class_location'].$filename.'.php';
            $db_obj = new $classname;
        } else {
            require_once 'DB/DataObject.php';
            $db_obj = DB_DataObject::factory($op);
        }
        //$this->initLangProps(& $db_obj);
        DVS_Dynamic::initLangProps(& $db_obj);
        /*
        if (method_exists($this->layout_obj, 'initDbObj')) {
            $this->layout_obj->initDbObj(&$db_obj);
        }
        */
        if (OS_WINDOWS === true) {
            //$db_obj->query("SET NAMES cp1251");
        }
        return $db_obj;
    }

    function closeDb() {
        if ($DB = $this->db_obj->getDatabaseConnection()) {
            $DB->disconnect();
        }
    }

    function getPrimaryKey($db_obj)
    {
        if (!isset($db_obj->id) && $keys = $db_obj->keys()) {
            $id = $keys[0];
        } else {
            $id = 'id';
        }
        return $id;
    }

    function initLangProps(& $db_obj)
    {
        $lang = $GLOBALS['_DVS']['LANG']['dbo'][$db_obj->__table];
        if (isset($lang)) {
            foreach ($lang as $prop => $val) {
                $db_obj->{$prop} = $val;
            }
        }
    }
}
?>
