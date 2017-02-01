<?php
/**
 * DVS Framework
 *
 * @category   DVS
 * @package    DVS
 * @version $Id: DVS.php 21 2010-10-28 06:25:35Z xxserg $
 */


/**
 * DVS_Exception
 */
require_once 'DVS/Exception.php';

/**
 * Базовая библиотека
 *
 * @category   DVS
 * @package    DVS
 */

class DVS
{

    /**
     * Object registry provides storage for shared objects
     * @var array
     */
    static private $_registry = array();

    /**
     * Loads a class from a PHP file
     * @param string $class
     * @param string $file
     * @throws DVS_Exception
     * @return void
     */
    static public function loadClass($class, $file)
    {
        if (class_exists($class, false)) {
            return;
        }
        self::loadFile($file);
        if (!class_exists($class, false)) {
            throw new DVS_Exception("File \"$file\" was loaded,\n but class \"$class\" was not found within.");
        }
    }

    /**
     * Loads a PHP file.  This is a wrapper for PHP's include() function.
     * $filename must be the complete filename, including any
     * extension such as ".php"
     * @param  string        $filename
     * @throws DVS_Exception
     * @return void
     */
    static public function loadFile($filename)
    {
        if (!self::isReadable($filename)) {
            throw new DVS_Exception("File \"$filename\" was not found.");
        } else {
            include_once($filename);
        }
    }

    /**
     * Returns TRUE if the $filename is readable, or FALSE otherwise.  This
     * function uses the PHP include_path, where PHP's is_readable() does not.
     *
     * @param string $filename
     * @return boolean
     */
    static public function isReadable($filename)
    {
        $f = @fopen($filename, 'r', true);
        $readable = is_resource($f);
        if ($readable) {
            fclose($f);
        }
        return $readable;
    }

    /**
     * Debug helper function.  This is a wrapper for var_dump() that adds
     * the <pre /> tags, cleans up newlines and indents, and runs
     * htmlentities() before output.
     *
     * @param  mixed  $var The variable to dump.
     * @param  string $label An optional label.
     * @return string
     */
    static public function dump($var, $label=null, $echo=true)
    {
        // format the label
        $label = ($label===null) ? '' : rtrim($label) . ' ';

        // var_dump the variable into a buffer and keep the output
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // neaten the newlines and indents
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        if (PHP_SAPI == 'cli') {
            $output = PHP_EOL . $label
                    . PHP_EOL . $output
                    . PHP_EOL;
        } else {
            $output = '<pre>'
                    . $label
                    . $output
                    . '</pre>';
        }

        if ($echo) {
            echo($output);
        }
        return $output;
    }

    /**
     * Вывод ошибки
     */
    public static function showError($page_code, $error_code = '', $message = '', $iface='frontend', $project_obj = '')
    {
        require_once 'DVS/Error.php';
        $error_obj = new DVS_Error($page_code, $error_code, $message, $iface, $project_obj);
        $error_obj->processError();
        exit;

    }

    /**
     * Exceptions Handler
     * @param  object $exception
     * @return void
     */
    static public function exceptionHandler($exception)
    {
        self::dump($exception, 'EX', 1);
        self::showError(104, '', 'Неизвестная ошибка:<BR>'.$exception->getMessage());
        
        echo DEBUG ? self::dump($exception, 'EX', 1) : '';
    }


    /**
     * Очистка переменных запроса
     * @param string $name название переменной
     * @param string $rule правило очистки
     * @param string $type тип глобальной переменной
     * @return string
     */
    static public function getVar($name, $rule = 'word', $type = 'GET')
    {
        switch (strtoupper($type))
        {
            case 'GET' :
                $input  = &$_GET;
                break;
            case 'POST' :
                $input  = &$_POST;
                break;
            case 'COOKIE' :
                $input  = &$_COOKIE;
                break;
            case 'REQUEST' :
                $input  = &$_REQUEST;
                break;
        }

        if (isset($input[$name]) && $input[$name] !== null) {
            $source = trim($input[$name]);
            switch (strtoupper($rule))
            {
                case 'INT' :
                    // Only use the first integer value
                    @ preg_match('/-?[0-9]+/', $source, $matches);
                    $result = @ (int) $matches[0];
                    break;

                case 'FLOAT' :
                    // Only use the first floating point value
                    @ preg_match('/-?[0-9]+(\.[0-9]+)?/', $source, $matches);
                    $result = @ (float) $matches[0];
                    break;

                case 'WORD' :
                    $result = (string) preg_replace( '#\W\-#', '', $source );
                    break;
            }
            return $result;
        } else {
            return null;
        }

    }

    /**
     * Registers a shared object.
     * @param   string      $name The name for the object.
     * @param   object      $obj  The object to register.
     * @return  void
     */
    static public function register($name, $obj)
    {
        self::$_registry[$name] = $obj;
    }


    /**
     * Retrieves a registered shared object, where $name is the
     * registered name of the object to retrieve.
     *
     * @see     register()
     * @param   string      $name The name for the object.
     * @return  object      The registered object.
     */
    static public function registry($name)
    {
        return self::$_registry[$name];
    }


    /**
     * Returns TRUE if the $name is a named object in the
     * registry, or FALSE if $name was not found in the registry.
     *
     * @param  string $name
     * @return boolean
     */
    static public function isRegistered($name)
    {
        return isset(self::$_registry[$name]);
    }

    static public function dumpRegistry()
    {
        self::dump(self::$_registry);
    }

}

?>
