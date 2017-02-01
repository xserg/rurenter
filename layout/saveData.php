<?php
/**
 * @package holidayaway.ru
 * @description ââîä â áàçó äàííûõ ÕÀ
 */



/**
 * 
 */
class saveData
{

     
    function __construct()
    {
    }
    

    /**
     * Read directory content
     *
     * @param string $dir
     * @return string
     */
    public static function getFiles($dir)
    {
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) { 
                if ($file != "." && $file != "..") { 
                    $str .= "<a href=?file=$file>$file</a><br>"; 
                } 
            }
            closedir($handle); 
            return $str;
        }
    }

    public static function getFilesArray($dir)
    {
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) { 
                if ($file != "." && $file != "..") { 
                    $ret[] = $file; 
                } 
            }
            closedir($handle); 
            return $ret;
        }
    }

    public function insertRegion($obj, $db_obj)
    {
        $db_obj->id = $obj->LocationRef;
        $db_obj->name = $obj->LocationDescription;
        $db_obj->rus_name = $obj->LocationDescription;
        $db_obj->parent_id = $obj->ParentID;
        $db_obj->counter = intval($obj->PropCount);
        //print_r($db_obj);
        $db_obj->insert();
    }

    public function updateCounters($obj)
    {
        $db_obj = DVS_Dynamic::createDbObj('countries');
        $db_obj->disable_sk = true;
        //DB_DataObject::DebugLevel(1);
        $db_obj->get((int)$obj->LocationRef);
        if (!$db_obj->N) {
            $this->insertRegion($obj, $db_obj);
            echo ' inserted<br>';
            $this->inserted++;
            return;
        }
        $count = intval($obj->PropCount);
        $name = strval($obj->LocationDescription);
        if ($db_obj->counter != $count || $db_obj->name != $name) {
            $db_obj->parent_id = $obj->ParentID;
            $db_obj->name = $obj->$name;
            $db_obj->counter = $count;
            $db_obj->update();
            $this->updated++;
            echo ' updated<br>';
        }
    }

    function bool($var)
    {
        return $var ? 1 : 0;
    }
    /**
    [region_id] => Array
        (
            [0] => Array
                (
                    [0] => World
                    [1] => Europe
                    [2] => Switzerland
                    [3] => Valais
                    [4] => 4-Valleys
                    [5] => haute-nendaz
                )

            [1] => Array
                (
                    [0] => 1
                    [1] => 5
                    [2] => 62
                    [3] => 762
                    [4] => 775
                    [5] => 9055
                )

            [2] => Array
                (
                    [0] => World
                    [1] => Europe
                    [2] => Switzerland
                    [3] => Valais
                    [4] => 4 Valleys Ski chalets & apartments
                    [5] => Haute-Nendaz Ski apartments & chalets
                )

        )
    */
    function checkRegion($arr)
    {
        //echo "CHECK REGION";

        //print_r($arr);
        $pid = 0;
        foreach ($arr[1] as $k => $id) {
            $arr[2][$k] = preg_replace('/Holiday.*$/', '', $arr[2][$k]);

            if ($id == 1) {
                continue;
            }

            $ñountries_obj = DVS_Dynamic::createDbObj('countries');
            //DB_DataObject::DebugLevel(1);
            $ñountries_obj->get($id);
            if (!$ñountries_obj->N) {
                $ñountries_obj->disable_sk = true;
                $ñountries_obj->id = $id;
                $ñountries_obj->parent_id = $pid;
                $ñountries_obj->name = $arr[2][$k];
                $ñountries_obj->rus_name = $arr[2][$k];
                $ñountries_obj->ha_alias = $arr[0][$k];
                $ñountries_obj->insert();
            }
            if ($ñountries_obj->parent_id != $pid) {
                $ñountries_obj->parent_id = $pid;
                $ñountries_obj->update();
            }
            $pid = $id;
        }
    }

    function makeLocationStr($arr, $check_region = false)
    {
        if ($check_region) {
            $this->checkRegion($arr);
        }
        //print_r($arr);
        array_shift($arr[1]);
        return ':'.implode(':', $arr[1]).':';
    }

    static function getLatLon($str)
    {
        //str_replace('\'', '', $str);
        //str_replace(',', '.', strval($obj->Lon));
        return urldecode(str_replace('\'', '', $str));
    }


    /**
     * @param $obj - simplexml object
     * @param $photo = 0 - çàãðóæàòü ññûëêè íà ôîòî
     * @param $db_obj - DB_DataObject

            $ret['id']
            $ret['title']
            $ret['summary']
            $ret['location']
            $ret['bedroom']
            $ret['sleeps']
            $ret['bathroom']
            $ret['type']
            $ret['description_title']
            $ret['description']
            main_img
            $ret['region_id']
            $ret['notes']
            $ret['extra']
            $ret['img']
            $ret['rate']
            $ret['opt']
            $ret['map']

     */
    function insertVilla($arr, $db_obj, $update=false)
    {

        if (!$arr['title']) {
            echo 'Error no title';
            return false;
        }
        if (!$arr['id']) {
            echo 'Error no Id';
            return false;
        }
        if ($update) {
            $db_obj->get($arr['id']);
            if ($db_obj->N) {
                return;
                //echo "deleting/n";
                //$db_obj->preDelete();
                //$db_obj->delete();
            }
        }
        $db_obj->disable_sk = true;
        $db_obj->id = $arr['id'];



        $db_obj->user_id = '74';
        $db_obj->proptype = trim($arr['type']);
        $db_obj->title = $arr['title'];
        $db_obj->title_rus = $arr['title'];
        $db_obj->summary = $arr['summary'];
        $db_obj->summary_rus = $arr['summary'];
        $db_obj->sleeps = $arr['sleeps'];
        $db_obj->rooms = $arr['bedroom'];
        
        $db_obj->bathrooms = $arr['bathroom'];
        $db_obj->status_id = 2;


        //$db_obj->main_image = $main_img;
        $db_obj->add_date = date('Y-m-d H:i:s');

        $location_arr = explode(':', $arr['location']);
        $location_id = $this->findLocation($location_arr);
        $db_obj->location= $location_id['location'];
        //$db_obj->locations_all = $this->makeLocationStr($arr['region_id'], $update);
        $db_obj->locations_all = $location_id['locations_all'];

        $db_obj->minprice = $arr['minprice'];
        $db_obj->maxprice = $arr['maxprice'];
        $db_obj->currency = $arr['currency'];
        $db_obj->pay_period = ($arr['period'] == 'Weekly' ? 1 : 2);


        $db_obj->lon = self::getLatLon($arr['map'][b]);
        $db_obj->lat = self::getLatLon($arr['map'][a]);

        if ($arr['notes'] || $arr['extra']) {
            $db_obj->extra = $arr['notes'][0].$arr['extra'][0];
        }
        
        //$db_obj->extra_rus = $db_obj->extra;
        $db_obj->src_site = 'homeaway.com/'.$arr['id'];

        //DB_DataObject::DebugLevel(1);
        $insert = $db_obj->insert();
        $villa_id  = $db_obj->id;

        if ($insert) {
            $this->insertImagesArray($villa_id, $arr, $db_obj);

        //DB_DataObject::DebugLevel(1);

            $this->insertOptions($arr['opt'], $villa_id);

            if ($villa_id && $arr['description']) {
                $descriptions_obj = DB_DataObject::factory('descriptions');
                $descriptions_obj->villa_id = $villa_id;
                //foreach ($obj->Descriptions->Description as $k => $arr) {
                    $descriptions_obj->title = $arr['description_title'];
                    $descriptions_obj->title_rus = $arr['description_title'];
                    $descriptions_obj->body = $arr['description'];
                    $descriptions_obj->body_rus = $arr['description'];
                    $descriptions_obj->insert();
                //}
            }
            return 1;
        } else {

            echo 'Error '.$villa_id."\n";
        }
    }


    function getMainImage($url)
    {
            $m = explode('/', $url);
            return $m[5].'/'.$m[6];
    }

    function updateMainImage($db_obj, $main_img)
    {

        $db_obj->disable_sk = false;
        $db_obj_orig = clone $db_obj;
        $db_obj->main_image = $main_img;

        $db_obj->update($db_obj_orig);
        unset($db_obj_orig);
    }

    /**
    Âñòàâêà êàðòèíîê èç îïèñàíèÿ âèëëû (íàçâàíèÿ áîëüøèõ ýêñòðàïîëèðóþòñÿ)
    */
    function insertImagesArray($villa_id, $arr, $db_obj)
    {
        if (sizeof($arr['img'])) {
            $images_obj = DVS_Dynamic::createDbObj('images');
            // DB_DataObject::DebugLevel(1);
            $images_obj->villa_id = $villa_id;
            $images_obj->delete();
            $images_obj->insertImagesHA($villa_id, $arr, $db_obj);
            $this->main_image = $images_obj->main_image;
            unset($images_obj);
        } else {
            echo 'Error Images<br>';
        }
    }

    function formatDate($str)
    {
        $arr = explode('/', $str);
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }

    function insertComments($villa_id, $obj)
    {
        $comments_obj = DB_DataObject::factory('comments');
        $comments_obj->villa_id = $villa_id;

        //print_r($obj);

        $reviews_arr = $obj->Reviews->Review;
        //print_r($reviews_arr);
        //DB_DataObject::DebugLevel(1);
        foreach ($reviews_arr as $k => $r) {
            $comments_obj->article = strval($r->Article);
            $comments_obj->post_date = $this->formatDate(strval($r->ReviewDate));
            $comments_obj->rating = intval($r->StarRating);
            $comments_obj->author = strval($r->By);
            $comments_obj->city = strval($r->From);
            $comments_obj->insert();
        }
        unset($comments_obj);
    }




    function insertOptions($opt_arr, $villa_id)
    {
        $grour_obj = DVS_Dynamic::createDbObj('option_groups');
        $group_arr = $grour_obj->selArray();
        $options_obj = DVS_Dynamic::createDbObj('options');
        $options_arr = $options_obj->selArray();
        $villa_opt_obj = DVS_Dynamic::createDbObj('villa_options');
        foreach ($opt_arr as $group => $options) {
            if (!($group_id = array_search($group, $group_arr))) {
                $grour_obj->name = $group;
                $grour_obj->rus_name = $group;
                $group_id = $grour_obj->insert();
                $group_arr[$group_id] = $group;
                //$group_arr = $grour_obj->selArray();
            }
            //echo $group_id;
            foreach ($options as $k => $v) {
                if (!($option_id = array_search($v, $options_arr))) {
                    $options_obj->name = $v;
                    $options_obj->rus_name = $v;
                    $options_obj->group_id = $group_id;
                    $option_id = $options_obj->insert();
                    $options_arr[$option_id] = $v;
                    //echo $v.' '.$option_id.'<br>';
                    //$group_arr = $grour_obj->selArray();
                }
                $villa_opt_obj->insertOptionsHA($villa_id, $option_id);
            }
        }
    }

    function findLocation($loc_arr)
    {

        $vals['locations_all'] = ':';
        $parent_id = '';
        foreach ($loc_arr as $k => $v) {
                    //echo "$k => $v<br>";
                            $countries_obj = DB_DataObject::factory('countries');
                            $countries_obj->name = $v;
                            //$countries_obj->whereAdd("name='".mysql_escape_string($v)."'");

                            if ($parent_id) {
                                $countries_obj->parent_id = $parent_id;
                                 //$vals['locations_all'] .= $countries_obj->parent_id.':';
                            }
                            $countries_obj->find(true);
                            //echo $countries_obj->name.' '.$countries_obj->id."\n<br>";


                            if (!$countries_obj->N) {
                                //echo "NOT FOUND<br>";
                                if ($parent_id) {
                                    $countries_obj->name = $v->long_name;
                                    $countries_obj->rus_name = $rus_name;
                                    //$countries_obj->insert();
                                    //echo "Inserted \n";
                                }
                            } else {
                                if (!$parent_id && $countries_obj->parent_id) {
                                    $vals['locations_all'] .= $countries_obj->parent_id.':';
                                }
                            }

                            $parent_id=$countries_obj->id;
                            
                            if ($countries_obj->id) {
                                $vals['locations_all'] .= $countries_obj->id.':';
                                $vals['location'] = $countries_obj->id;
                            }
                
        }
        //$vals['location'] = $countries_obj->id;
        return $vals;
    }
}

?>