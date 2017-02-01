<?php
/**
 * DVS Framework
 *
 * @category   DVS
 * @package    DVS
 * @version $Id: Error.php 21 2010-10-28 06:25:35Z xxserg $
 */


/**
 * Вывод ошибок
 *
 * @category   DVS
 * @package    DVS
 */


class DVS_Error
{

    /**
     * Код страницы ошибки
     * Определяет алгоритм вывода страницы ошибки
     */
    public $page_code;

    /**
     * Код ошибки, текст берется из базы 
     */
    public $error_code;

    /**
     * Текст ошибки 
     */
    public $message;


    private $project_obj;

    public function __construct($page_code, $error_code = '', $message = '', $iface='frontend', $project_obj = '')
    {
        $this->page_code = $page_code;
        $this->iface = $iface;
        $this->error_code = $error_code;
        $this->message = $message;
        $this->project_obj = $project_obj;
    }

    /**
     * Отображение статичной страницы ошибки
     * с минимальной обвязкой
     */
    static public function showStaticError($text)
    {
        $tmpl_file = COMMON_LIB.'tmpl/autoru06/static.tmpl';
        if (DVS::isReadable($tmpl_file)) {
            $tmpl = file_get_contents($tmpl_file);
            $content = str_replace('{TEXT}', $text, $tmpl);
        } else {
            $content = $text;
        }
        return $content;
    }

    /**
     * Отображение динамической страницы ошибки
     * с обвязкой проекта или бэкенда
     * @TODO добавить кеширование
     */
    private function showDynamicError()
    {
        $err_project_obj = $this->createErrorObj();
        if ($this->project_obj) {
            $this->project_obj->page_obj = $err_project_obj->page_obj;
        } else {
            $this->project_obj = $err_project_obj;
        }
        //DVS::dump($this->project_obj);
        $doc_obj = DVS_Face::createDocumentObj();
        return $doc_obj->showDocument($this->project_obj);
    }

    /**
     * 
     */
    private function createErrorObj()
    {
        $project_obj = DVS_Face::createProjectObj('admin');
        $project_obj->setIface($this->iface);
        $page_prop['page_op'] = 'messages';
        $page_prop['page_act'] = 'show';
        //$page_prop['project_id'] = 56;
        //print_r($this);
        //exit;
            //$project_obj->createPageObj('messages', 'show');
        $project_obj->createPageObjByProperties($page_prop);
        if (!$this->error_code && !$this->message) {
            $this->error_code = 'error_404';
        }
        if ($this->error_code) {
            $project_obj->page_obj->getPageData($this->error_code); 
        } else {
            $project_obj->page_obj->db_obj->page_data = array(
                'MESSAGE_TEXT' => $this->message,
                'MESSAGE_TYPE' => 'error'
            );
        }
        return $project_obj;
    }


    public function processError()
    {
        switch ($this->page_code) {
            default:
            case 404:
                $this->error_code = 'error_404';
                header("HTTP/1.0 404 Not Found");
                $content = $this->showDynamicError();
                break;
            case 103:
                $content = $this->showDynamicError();
                break;
            case 104:
                if (!$this->message) {
                    $this->message = DVS_ERROR_SERVICE;
                }
                $content = self::showStaticError($this->message);
                break;
        }
        echo $content;
        //print_r($content);
    }
}

?>
