<?php
/**
 * @package villarenters.ru
 * @description Запрос и получение данных Villarenters.com
 */

define('DATA_HOST', 'http://www.villarenters.com/villarenterswebservice/villasearch.asmx/');

require_once (PROJECT_ROOT.'/layout/getHTTP.php');
/*
define('PROXY_HOST', '212.248.26.65');
define('PROXY_PORT', '3128');

define('PROXY_HOST', '');
define('PROXY_PORT', '');
*/

/**
 * 
 */
class GetData
{

    public $data_url = array(
                                'countries' => array(
                                                'url' => 'GetCountryList',
                                                'params' => array('country_ref' => 0, 'VRF' => '')
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
                                                    'strLocationRefs' => 0,
                                                    'intMaxPrice' => '0',
                                                    'intMinPrice' => '0',
                                                    'intSleeps' => '0',
                                                    'blnInstantBooking' => 'false',
                                                    'intVillarentersIndex' => '1',
                                                    'intDiscountType' => '0',
                                                    'intBranding' => '0',
                                                    'intPage' => '1',
                                                    'intItemsPerPage' => '100',
                                                    'blnEnableAvailabilitySearch' => 'false',
                                                    'strFromYYYYMMDD' => '',
                                                    'strToYYYYMMDD' => '',
                                                    'intSortOrder' => '0',
                                                )),

                                'VillaSearchV2' => array(
                                                'url' => 'VillaSearchV2',
                                                'params' => array(
                                                    'Rag' => '',
                                                    'strOwnerRefs' => '',
                                                    'strPropRefs' => '',
                                                    'intPropType' => 1,
                                                    'strLocationRefs' => 0,
                                                    'intMaxPrice' => '0',
                                                    'intMinPrice' => '0',
                                                    'intSleeps' => '0',
                                                    'blnInstantBooking' => 'false',
                                                    'intVillarentersIndex' => 1,
                                                    'intDiscountType' => '0',
                                                    'intBranding' => '0',
                                                    'intPage' => '1',
                                                    'intItemsPerPage' => '100',
                                                    'blnEnableAvailabilitySearch' => 'false',
                                                    'strFromYYYYMMDD' => '',
                                                    'strToYYYYMMDD' => '',
                                                    'intSortOrder' => '0',
                                                )),

                                'GetPropertyAvailability' => array(
                                                'url' => 'GetPropertyAvailability',
                                                'params' => array('prop_ref' => 0,'VRF' => '')
                                                ),
                                'GetPropertyReviews' => array(
                                                'url' => 'GetPropertyReviews',
                                                'params' => array('prop_ref' => 0,'VRF' => '')
                                                ),
                                'GetPropertyImages' => array(
                                                'url' => 'GetPropertyImages',
                                                'params' => array('intPropRef' => 0,'blnGetThumbnails' => 'false')
                                                ),

                            );



    public function requestXMLdata($op)
    {
        $this->req = new GetHTTP;
        //return simplexml_load_string($this->request($this->data_url[$op]));
        $body = $this->req->request($this->data_url[$op]);
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

    public function requestData($op)
    {
        if (!isset($this->req)) {
            $this->req = new GetHTTP;
        }
        $body = $this->req->request($this->data_url[$op]);
        //echo $body;
        if (!$body) {
            echo "No body";
            return false;
        }
        /*
        if (preg_match('/&#x/', $body)) {
             $body = str_replace('&#x', '', $body );
        }
        */
        if (DEBUG) {
           echo $body;
        }
        return $body;
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



    function formatDate($str)
    {
        $arr = explode('/', $str);
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }


    function initVillaSearch($loacation_id, $villa_id)
    {
        if ($loacation_id) {
            $this->data_url['VillaSearch']['params']['strLocationRefs'] = $loacation_id;
            $this->data_url['VillaSearchV2']['params']['strLocationRefs'] = $loacation_id;
            echo $loacation_id."\n";
        } else {
            $this->data_url['VillaSearch']['params']['strPropRefs'] = $villa_id;
            $this->data_url['VillaSearchV2']['params']['strPropRefs'] = $villa_id;
        }
    }

    function pageVillaList()
    {
        $dir = PROJECT_ROOT.'data/'.date('Ymd');
        @mkdir($dir);
        $num=1;
        $n = 0;
        //$this->data_url['VillaSearch']['params']['Rag'] = VR_ACCOUNT_NUM;
        $this->data_url['VillaSearch']['params']['intPage'] = 1;
        $body = $this->requestData('VillaSearch');
        //echo $body;
        //return;
        while (strlen($body) > 1024) {
            file_put_contents($dir.'/'.$this->data_url['VillaSearch']['params']['strLocationRefs'].'_'.$this->data_url['VillaSearch']['params']['intPage'].'.txt', $body);
            //set_time_limit (600);
            $this->data_url['VillaSearch']['params']['intPage']++;
            $body = $this->requestData('VillaSearch');
            //echo '<b>next page: '.$this->data_url['VillaSearch']['params']['intPage'].'</b><br>';
        }
        return;
    }

    function pageVillaListV2()
    {
        $dir = PROJECT_ROOT.'data/'.date('Ymd');
        @mkdir($dir);
        $num=1;
        $n = 0;
        //$this->data_url['VillaSearch']['params']['Rag'] = VR_ACCOUNT_NUM;
        $this->data_url['VillaSearchV2']['params']['intPage'] = 1;
        $body = $this->requestData('VillaSearch');
        //echo $body;
        //return;
        while (strlen($body) > 1024) {
            file_put_contents($dir.'/'.$this->data_url['VillaSearchV2']['params']['strLocationRefs'].'_'.$this->data_url['VillaSearchV2']['params']['intPage'].'.txt', $body);
            //set_time_limit (600);
            $this->data_url['VillaSearchV2']['params']['intPage']++;
            $body = $this->requestData('VillaSearchV2');
            //echo '<b>next page: '.$this->data_url['VillaSearch']['params']['intPage'].'</b><br>';
        }
        return;
    }

    function processVilla($xml, $num)
    {
        file_put_contents(PROJECT_ROOT.'data/data'.$num.'.txt', $xml);
    }

    function getBooking($villa_id)
    {
        $this->data_url['GetPropertyAvailability']['params']['prop_ref'] = $villa_id;
        return $this->requestData('GetPropertyAvailability');
    }

    function getAllBooking()
    {
        $db_obj = DVS_Dynamic::createDbObj('villa');
        $db_obj->orderBy('id');
        $db_obj->limit(0, 200);
        $db_obj->find();
        while ($db_obj->fetch()) {
            echo $db_obj->id.' '.$db_obj->title.'<br>';
            $str .= $this->getBooking($db_obj->id);
        }
        file_put_contents(PROJECT_ROOT.'data/booking2.txt', $str);
        return;
    }
}

?>