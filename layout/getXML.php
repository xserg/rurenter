<?php
/**
 * @package villarenters.ru
 * @description Запрос и получение данных Villarenters.com
 */

define('DATA_HOST', 'http://www.villarenters.com/villarenterswebservice/villasearch.asmx/');

require_once ('HTTP/Request2.php');

/*
define('PROXY_HOST', '212.248.26.65');
define('PROXY_PORT', '3128');

define('PROXY_HOST', '');
define('PROXY_PORT', '');
*/

/**
 * 
 */
class GetXML
{

    public $data_url = array(
                                'countries' => array(
                                                'url' => 'GetCountryList',
                                                'params' => array('country_ref' => 33, 'VRF' => '')
                                                ),
                                                
                                'childlocations' => array(
                                                'url' => 'GetChildLocations',
                                                'params' => array('ParentID' => 0, 'VRF' => '')
                                                ),
                                /* Возвращает еще и количество вил */
                                'childlocationsV2' => array(
                                                'url' => 'GetChildLocationsV2',
                                                'params' => array('ParentID' => 0, 'VRF' => '')
                                                ),
                                'VastSearch' => array(
                                                'url' => 'VastSearch',
                                                'params' => array()
                                                ),
                                'VillaSearch' => array(
                                                'url' => 'VillaSearch',
                                                'params' => array(
                                                    'Rag' => '',
                                                    'strOwnerRefs' => '',
                                                    'strPropRefs' => '',
                                                    'strLocationRefs' => '',
                                                    'intMaxPrice' => '0',
                                                    'intMinPrice' => '0',
                                                    'intSleeps' => '0',
                                                    'blnInstantBooking' => 'false',
                                                    'intVillarentersIndex' => '0',
                                                    'intDiscountType' => '0',
                                                    'intBranding' => '0',
                                                    'intPage' => '1',
                                                    'intItemsPerPage' => '200',
                                                    'blnEnableAvailabilitySearch' => 'false',
                                                    'strFromYYYYMMDD' => '',
                                                    'strToYYYYMMDD' => '',
                                                    'intSortOrder' => '0',
                                                )),
                                'GetPropertyAvailability' => array(
                                                'url' => 'GetPropertyAvailability',
                                                'params' => array('prop_ref' => '19931','VRF' => '')
                                                ),
                                'GetPropertyReviews' => array(
                                                'url' => 'GetPropertyReviews',
                                                'params' => array('prop_ref' => '19931','VRF' => '')
                                                ),
                                'GetPropertyImages' => array(
                                                'url' => 'GetPropertyImages',
                                                'params' => array('intPropRef' => '19931','blnGetThumbnails' => 'false')
                                                ),

                            );


    /**
     * PEAR HTTP_Request Object
     *
     * @var object
     */
    public $req;

    /**
     * server response
     *
     * @var array
     */
    public $response;    
    
    
    
    /**
     * constructor
     *
     * @param string $url
     */
     
    function __construct()
    {
        $this->req = &new HTTP_Request2('', HTTP_Request2::METHOD_GET, 
            array('proxy_host' => PROXY_HOST, 'proxy_port' => PROXY_PORT));
    }
    
    /**
     * post vars to server and return response
     *
     * @param array $vars

        array('url' => $url, 'params' => $vars);

     * @return array
     */
    function request($vars=array())
    { 
        //$this->req = &new HTTP_Request2('', HTTP_Request2::METHOD_GET, array('proxy_host' => PROXY_HOST, 'proxy_port' => PROXY_PORT));

        if (!isset($this->url_obj)) {
            $this->url_obj       = new Net_URL2(DATA_HOST.$vars['url']);
        }
        $this->url_obj->setQueryVariables($vars['params']);
        $this->req->setUrl($this->url_obj);

        if (DEBUG == 1) {
            //echo "\n POST TO: ".$this->req->getUrl()."\n";
        }
        //echo '<pre>';
        //print_r($this->req);
       
        $this->response = $this->req->send();
        //print_r($this->response);

        $body = $this->response->getBody();
        //unset($this->req);
        //unset($url_obj);
        //unset($this->response);
        return $body;

        //$this->response = $this->req->getResponseBody();

        //return $response;
    }

    public function requestXMLdata($op)
    {
        //return simplexml_load_string($this->request($this->data_url[$op]));
        $body = $this->request($this->data_url[$op]);
        //echo $body;
        if (!$body) {
            echo "No body";
            return false;
        }
        if (preg_match('/&#x/', $body)) {
             $body = str_replace('&#x', '', $body );
        }
        $xml = simplexml_load_string($body);
        if (DEBUG) {
           //print_r($xml);
        }
        return $xml;
    }

    /**
     * add common vars to request
     *
     */
    function commonVars()
    {
        $this->req->addPostData('nocache', '0.'.rand(1, 54768704578));
        $this->req->addPostData('phpsessid', $this->gid);
    }
    
    /**

    
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

    public function getCountries($obj)
    {
        $country_arr = $obj->Countries->Country;
        foreach ($country_arr as $country) {
            echo $country->Description.' '.$country->ID.'<br>';
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
        if ($db_obj->counter != $count) {
            $db_obj->name = $obj->LocationDescription;
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

/*

[Locations] => SimpleXMLElement Object
                        (
                            [Location] => Array
                                (
                                    [0] => SimpleXMLElement Object
                                        (
                                            [LocationRef] => 13028
                                            [LocationDescription] => Herceg Novi
                                            [ParentID] => 2716
                                        )

                                    [1] => SimpleXMLElement Object
                                        (
                                            [LocationRef] => 2716
                                            [LocationDescription] => Montenegro
                                            [ParentID] => 3929
                                        )

                                    [2] => SimpleXMLElement Object
                                        (
                                            [LocationRef] => 3929
                                            [LocationDescription] => Europe
                                            [ParentID] => 0
                                        )

                                )

                        )


*/
    function makeLocationStr($obj)
    {
        $arr = $obj->Locations->Location;
        //$arr = array_reverse($arr);
        foreach ($arr as $k => $l) {
            $str = intval($l->LocationRef).':'.$str;
        }
        return ':'.$str;
    }

    /**
     * @param $obj - simplexml object
     * @param $photo = 0 - загружать ссылки на фото
     * @param $db_obj - DB_DataObject
     */
    function insertVilla($obj, $photo, $db_obj)
    {

        //print_r($obj);

        //$db_obj = DVS_Dynamic::createDbObj('villa');
        $db_obj->disable_sk = true;
        $db_obj->id = intval($obj->prop_ref);
        $db_obj->user_id = intval($obj->owner_ref);
        $db_obj->proptype = strval($obj->PropType); //////////////////////Преобразовать!!!
        $db_obj->title = strval($obj->Title);
        $db_obj->title_rus = $db_obj->title;
        $db_obj->summary = strval($obj->Summary);
        //$db_obj->summary_rus = $db_obj->summary;
        $db_obj->filename = strval($obj->Filename);
        $db_obj->location= intval($obj->Locations->Location[0]->LocationRef);
        $db_obj->locations_all = $this->makeLocationStr($obj);
        $db_obj->minprice = intval($obj->MinPrice); 
        $db_obj->maxprice = intval($obj->MaxPrice);
        $db_obj->currency = strval($obj->Currency);                        // int(4)  
        $db_obj->sleeps =  intval($obj->Sleeps);
        
        $db_obj->lon = str_replace(',', '.', strval($obj->Lon));
        $db_obj->lat = str_replace(',', '.', strval($obj->Lat));
        
        $db_obj->maplink = strval($obj->MapLink);
        $db_obj->extra = strval($obj->Extra);
        //$db_obj->extra_rus = $db_obj->extra;
        $db_obj->instantbooking =  $this->bool($obj->InstantBooking);
        $db_obj->villarentersindex = intval($obj->VillarentersIndex);
        $db_obj->src_site = 'villarenters.com';


        if ($murl = $obj->PropImages->PropImage[0]->ImageURL) {
            $db_obj->main_image = $this->getMainImage($murl);
        }

        //DB_DataObject::DebugLevel(1);
        //print_r($db_obj);
        //exit;
        $insert = $db_obj->insert();
        //echo "insert=$insert<br>";
        $villa_id  = intval($obj->prop_ref);

        if ($insert) {
            //echo $db_obj->title.'<br>';
            //flush();

            if ($villa_id && $obj->Descriptions) {
                $descriptions_obj = DB_DataObject::factory('descriptions');
                $descriptions_obj->villa_id = $villa_id;
                foreach ($obj->Descriptions->Description as $k => $arr) {
                    $descriptions_obj->title = $arr->Title;
                    $descriptions_obj->body = $arr->Paragraph;
                    $descriptions_obj->insert();
                }

            }

            if ($villa_id && $obj->UserFields) {
                $user_options_obj = DB_DataObject::factory('user_options');
                $user_options_obj->villa_id = $villa_id;
                foreach ($obj->UserFields->UserField as $k => $arr) {
                    $user_options_obj->user_location = strval($arr->UserLocation);
                    $user_options_obj->distance = strval($arr->UserDistance);
                    $user_options_obj->insert();
                }
            }

            $opt_obj = DB_DataObject::factory('villa_options');
            $opt_obj->insertOptions($villa_id, $obj);


            if ($photo == 0) {
                $this->insertImages($villa_id, $this->images_obj);
                //$this->updateMainImage($db_obj);
            }
        } else {
            echo 'Error '.$villa_id."\n";
        }
        
        //unset($db_obj);
        unset($descriptions_obj);
        unset($user_options_obj);
        unset($opt_obj);
        unset($obj);
        
        //DB_DataObject::DebugLevel(1);
        /*
        $db_obj->disable_sk = false;
        $db_obj_orig = clone $db_obj;
        $db_obj->main_image = $this->main_image;
        $db_obj->update($db_obj_orig);
        */
        /*
        if ($obj->PropImages) {
            $images_obj = DB_DataObject::factory('images');
            $images_obj->insertImages($villa_id, $obj->PropImages->PropImage);
        }
        */
    }


    function getMainImage($url)
    {
            $m = explode('/', $url);
            return $m[5].'/'.$m[6];
    }

    function updateMainImage($db_obj)
    {
        $db_obj->disable_sk = false;
        $db_obj_orig = clone $db_obj;
        $db_obj->main_image = $this->main_image;
        $db_obj->update($db_obj_orig);
        unset($db_obj_orig);
    }


    function insertImages($villa_id, $images_obj=null)
    {
            $img_arr_big = $this->getImages($villa_id);

        if (sizeof($img_arr_big)) {
            $images_obj = DVS_Dynamic::createDbObj('images');
            // DB_DataObject::DebugLevel(1);
            //$villa_id = intval($obj->prop_ref);
            $images_obj->villa_id = $villa_id;
            $images_obj->delete();
            $images_obj->insertImagesDB($villa_id, $img_arr_big);
            $this->main_image = $images_obj->main_image;
        } else {
            echo 'Error Images<br>';
        }
    }

    function getImages($villa_id)
    {
        $this->data_url['GetPropertyImages']['params']['intPropRef'] = $villa_id;
        $xml = $this->requestXMLdata('GetPropertyImages');
        if (is_object($xml->villaimage)) {
            $i = 0;
            foreach ($xml->villaimage as $k => $a) {
                foreach($a->attributes() as $name => $val) {
                    if ($name == 'url') {
                        $a->ImageURL = strval($val);
                    }
                    if ($name == 'label') {
                        $a->ImageCaption = strval($val);
                    }
                }
                $img_arr[$i] = $a;
                $i++;
            }
            return $img_arr;
        }
    }

/**

[prop_ref] => 19931
    [Reviews] => SimpleXMLElement Object
        (
            [Review] => Array
                (
                    [0] => SimpleXMLElement Object
                        (
                            [ReviewDate] => 27/09/2006
                            [StarRating] => 10
                            [By] => lesley
                            [From] => glasgow
                            [Article] => The apartment is in a great location
                        )

                    [1] => SimpleXMLElement Object
                        (
                            [ReviewDate] => 16/05/2007
                            [StarRating] => 0
                            [By] => member
                            [From] => SimpleXMLElement Object
                                (
                                )

                            [Article] => 'What a fantastic stay in Luz.  h!'
                        )

    public $article;                         // text()  
    public $article_rus;                     // text()  
    public $post_date;                       // datetime()   not_null
    public $rating;                          // int(4)   not_null
    public $author;                          // varchar(64)   not_null
    public $from;                            // varchar(128)   not_null


*/

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

    function initVillaSearch($loacation_id, $villa_id)
    {
        if ($loacation_id) {
            $this->data_url['VillaSearch']['params']['strLocationRefs'] = $loacation_id;
            echo $loacation_id.' <br>';
        } else {
            $this->data_url['VillaSearch']['params']['strPropRefs'] = $villa_id;
        }
    }

    function pageVillaList($photo=0, $demo=false, $page=1)
    {
        $num=1;
        $n = 0;
        $this->data_url['VillaSearch']['params']['intPage'] = $page;
        while ($num > 0) {
            set_time_limit (720);
            $xml = $this->requestXMLdata('VillaSearch');
            $num = intval($xml->NumberOfRecords);
            if ($num > 0) {
                if (!$demo) {
                    $n += $this->processVilla($xml, $photo);
                } else {
                    echo '<pre>';
                    print_r($xml);
                }
                $this->data_url['VillaSearch']['params']['intPage']++;
                echo '<b>next page: '.$this->data_url['VillaSearch']['params']['intPage'].'</b><br>';
            }
        }
        return $n;
    }


    function processVilla($xml, $photo)
    {
        $i=0;

        $db_obj = DVS_Dynamic::createDbObj('villa');

        $this->images_obj = DVS_Dynamic::createDbObj('images');


        foreach ($xml->Villas->Villa as $k => $villa) {
            //echo '<pre>'.$i;
            //print_r($this);
            $villa_id = intval($villa->prop_ref);
            if ($photo == 2) {
                echo 'img '.$villa_id.'<br>';
                DB_DataObject::DebugLevel(1);
                $this->insertImages($villa_id, $this->images_obj);
                //$db_obj = DVS_Dynamic::createDbObj('villa');
                /*
                $db_obj->get($villa_id);
                if ($db_obj->N) {
                    $this->insertImages($villa);
                    $this->updateMainImage($db_obj);
                }
                */
            } else {
                $this->insertVilla($villa, $photo, $db_obj);
                $this->data_url['GetPropertyReviews']['params']['prop_ref'] = $villa_id;
                $comments_xml = $this->requestXMLdata('GetPropertyReviews');
                $this->insertComments($villa_id, $comments_xml);
            }
            $i++;
            unset($villa);
        }
        return $i;
    }

}

?>