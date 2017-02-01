<?php
/*////////////////////////////////////////////////////////////////////////////
@package lib2/DVS
------------------------------------------------------------------------------
Класс управления кешом
------------------------------------------------------------------------------
$Id: Cache.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

class DVS_Cache
{

    // Кешировать страницу
    var $_caching;
    // Своиства обекта CacheLite
    var $_cacheopt;
    /*
    Конструктор проверяет, нужен ли кэш для страницы, вызывается в конструкторе проекта.
    В конструкторе проекта необходимо также определить свойства для страницы.
    
        $cached_pages = array(

                    'op1' => array(
                        'act1'      => array(
                            'cacheDir'           => PROJECT_ROOT.CACHE_FOLDER.$this->get['board'].'/',
                            'lifeTime'           => MSG_REFRESH_TIME*100,
                            'fileNameProtection' => 0,
                            'cache_id'           => $this->get['id'].($_REQUEST['st'] ? '_st' : '').($_GET['archive'] == 2 ? '_old' : ''),
                            'cache_group'        => 'msg',
                            //'automaticSerialization' => true,
                            ),
    ................
    );
    */

    //Конструктор проверяет кеширование страницы и определяет свойства кеширования
    function DVS_Cache($get = null)
    {
        $this->get = $get ? $get : $_GET;
        //print_r($this->get);
        // Нужно ли кешировать эту страницу
        if (is_array($this->cached_pages[$this->get['op']]) && in_array($this->get['act'], array_keys($this->cached_pages[$this->get['op']]))) {
            $this->_caching = true;
            $this->_cacheopt = $this->cached_pages[$this->get['op']][$this->get['act']];
        }

        if ($this->get['op'] == 'static') {
            $this->_caching = true;
            $this->_cacheopt = array(
                            'cacheDir'           => PROJECT_ROOT.CACHE_FOLDER.'static/',
                            'lifeTime'           => REFRESH_TIME,
                            'fileNameProtection' => 0,
                            'cache_id'           => $this->get['act'],
                            'cache_group'        => 'static',
                            );
        }
    }

    function showCachedPage()
    {
        if (!$content = $this->getContent()) {
            require_once COMMON_LIB.'DVS/Page.php';
            $page_obj = DVS_Page::factory(DVS::getVar('op'), DVS::getVar('act'), 'i');
            $content = $page_obj->showPage();
            if (!$page_obj->nocache) {
                $this->saveCache($content);
            }
        }
        return $content;
    }

    // Объект CacheLite создается только в случае если страница кэшируется
    function getContent($doNotTestCacheValidity = false)
    {
        if ($this->_caching) {
            $this->createCacheObject();
            return $this->cache->get($this->_cacheopt['cache_id'], $this->_cacheopt['cache_group'], $doNotTestCacheValidity);
        } else {
            return false;
        }
    }

    // Создание объекта CacheLite
    function createCacheObject()
    {
        require_once 'Cache/Lite.php';
        $this->cache =& new Cache_Lite($this->_cacheopt);
    }

    function makeCachedir($dir, $dirmode = 0775)
    {
       if (!empty($dir)) {
           if (!file_exists($dir)) {
               preg_match_all('/([^\/]*)\/?/i', $dir,$atmp);
               $base="";
               foreach ($atmp[0] as $key=>$val) {
                   $base=$base.$val;
                   if(!file_exists($base))
                       if (mkdir($base,$dirmode)) {
                            chmod($base, $dirmode);
                       } else {
                           echo "Error: Cannot create ".$base;
                           return 0;
                       }
               }
           } else if (!is_dir($dir)) {
                   echo "Error: ".$dir." exists and is not a directory";
                   return -2;
           }
       }
       return 0;
    }

    // Сохранение кеша
    function saveCache($content)
    {
        global $_DVS;

        if ($this->_caching && !$_DVS['NOCACHE']) {
            if (defined('TEST_CACHE')) {
                echo "CACHING ".$this->_cacheopt['cacheDir']
                    ." ====".$this->_cacheopt['cache_id']."++++++".$this->_cacheopt['cache_group']."--".$this->_cacheopt['fileNameProtection'];
            }
            $this->makeCachedir($this->_cacheopt['cacheDir']);
            $this->cache->save($content);
        }
    }

    // Очистка кеша
    function deleteCache($remove_group = false)
    {
        $this->createCacheObject();
        if ($remove_group) { // Очистка группы кеша
            $this->cache->clean($this->_cacheopt['cache_group']);
        } else { // Очистка закешированнного файла
            $this->cache->remove($this->_cacheopt['cache_id'], $this->_cacheopt['cache_group']);
        }
    }

    function getArray($op)
    {
        require_once 'Cache/Lite.php';
        $this->cache =& new Cache_Lite(array(
                            'cacheDir'           => PROJECT_ROOT.CACHE_FOLDER.'array/',
                            'cache_id'           => $op,
                            'cache_group'        => 'array',
                            'lifeTime'           => REFRESH_TIME,
                            'fileNameProtection' => 0,
                            'automaticSerialization' => true,
                            ));
        if (!$array = $this->cache->get($op, 'array', 1)) {
            require_once 'DB/DataObject.php';
            $db_obj = DB_DataObject::factory($op);
            $array = $db_obj->selArray();
            $this->cache->save($array);
        }
        return $array;
    }
}
?>
