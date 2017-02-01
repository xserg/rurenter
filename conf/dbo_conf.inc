<?
/*////////////////////////////////////////////////////////////////////////////

------------------------------------------------------------------------------
Конфиг для DB_DataObject
------------------------------------------------------------------------------
$Id: dbo_conf.inc 147 2011-10-05 13:43:51Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

// Параметры объекта DB_DataObject
// Для ликвидации конфликта Zend Optimazer и DB_DataObject
define(DB_DATAOBJECT_NO_OVERLOAD, 0);

require_once 'PEAR.php';
$mdb2_options = &PEAR::getStaticProperty('MDB2','options');
//$mdb2_options = array('portability' => MDB2_PORTABILITY_ALL);
$mdb2_options = array('portability' => 1, 'field_case' => CASE_LOWER);


$db_options = &PEAR::getStaticProperty('DB_DataObject','options');
$db_options = array(
    'database_'.DB_NAME                  => DB_DSN,
    /*
    'database' => array(
        'phptype'  => 'oci8',
        'dbsyntax' => "Easy Connect",
        'username' => DB_USER,
        'password' => DB_PASS,
        'protocol' => false,
        'hostspec' => DB_HOST,
        'port'     => false,
        'socket'   => false,
        //'database' => DB_NAME,
        'new_link' => false,
        'service'  => DB_NAME, // only in oci8
    ),
    */
    'schema_location'           => PROJECT_ROOT.'conf',
    'class_location'            => PROJECT_ROOT.'DataObjects2/',
    'require_prefix'            => '',
    'class_prefix'              => 'DBO_',
    'db_driver'                 => 'MDB2',
    'generator_no_ini'          => true,
    //'dont_die'                => true,
        'generator_include_regex'   => "/pages/"

);
?>
