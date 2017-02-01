<?php
/**
 * Подсказки при вводе интересов, тагов
 *
 */
 // $Id: livesearch.class.php 21 2010-10-28 06:25:35Z xxserg $
require_once COMMON_LIB.'DVS/Dynamic.php';

class livesearch {


    /**
     * Perform a search
     *
     * @return array
     */
    function search($input) {
        $input = iconv('UTF-8', 'cp1251', $input);
        $input = trim(preg_replace("/^.*,/", "", $input));
        if (empty($input)) {
            return;
        }
        $db_obj = DVS_Dynamic::createDbObj('interests');
        $db_obj->whereAdd("name LIKE '$input%'");
        $db_obj->orderBy('name');
        $db_obj->limit(0, 23);
        $db_obj->find();
        while ($db_obj->fetch()) {
            $name = iconv( 'cp1251', 'UTF-8', $db_obj->name);
            $ret[$name] = $name;
        }
        return $ret;
    }

    function search2($input) {
        $input = iconv('UTF-8', 'cp1251', $input);
        $input = trim(preg_replace("/^.*,/", "", $input));
        if (empty($input)) {
            return;
        }
        $db_obj = DVS_Dynamic::createDbObj('v_tags');
        $db_obj->whereAdd("word LIKE '$input%'");
        $db_obj->orderBy('word');
        $db_obj->limit(0, 23);
        $db_obj->find();
        while ($db_obj->fetch()) {
            $name = iconv( 'cp1251', 'UTF-8', $db_obj->word);
            $ret[$name] = $name;
        }
        return $ret;
    }


}
?>