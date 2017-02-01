<?php
/**
 * @package web2matrix
 * ------------------------------------------------------------------------------
 * Класс управления кешом проекта
 * ------------------------------------------------------------------------------
 * $Id: Cache.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Cache.php';

class Project_Cache extends DVS_Cache
{
    public $cached_pages = array();

    function Project_Cache($get = null)
    {
        $this->get = $get ? $get : $_GET;

        if (empty($this->get)) {
            $this->get['op'] = 'pages';
            $this->get['act'] = 'show';
            //$cache_id = 'index';
        }
        foreach ($this->get as $k => $v) {
            if ($k == 'op' || $k == 'act') {
                continue;
            } else {
                $cache_id .= '_'.$k.'_'.$v;
            }
        }

        $this->cached_pages['pages']['show'] = array(
                            'cacheDir'           => PROJECT_ROOT.CACHE_FOLDER.'pages/',
                            'lifeTime'           => REFRESH_TIME,
                            'fileNameProtection' => 0,
                            'cache_id'           => $cache_id,
                            'cache_group'        => 'pages',
                            );

        $this->DVS_Cache($this->get);
        if (!empty($_COOKIE['PHPSESSID']) || !empty($_COOKIE['username'])) {
            $this->_caching = false;
        }
        $this->_caching = false;
    }
}
?>
