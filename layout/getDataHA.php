<?php
/**
 * @package holidayaway
 * @description Запрос и получение данных Villarenters.com
 */

//define('DATA_HOST', 'http://www.holiday-rentals.co.uk/');
define('HA_HOST', 'http://www.homeaway.co.uk/');

define('MAX_PAGE', 9693);

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
class GetDataHA
{
    public $error;

    public $data_url = array(
        'Search' => array ('host' => 'http://www.homeaway.co.uk/', 'url' => 'search', 'params' => array()),
        //search/europe/region:5/page:
    );

    public function requestData($op)
    {
        if (!isset($this->req)) {
            $this->req = new GetHTTP;
        }
        $body = $this->req->request($this->data_url[$op]);

        if (!$body) {
            echo "No body<pre>";
            //print_r($this->req);
            return false;
        }

        if (DEBUG) {
            //print_r($this->req);
           //echo $body;
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



    function formatDate($str)
    {
        $arr = explode('/', $str);
        return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }


    function pageVillaList()
    {
        $dir = PROJECT_ROOT.'data/'.date('Ymd').'_list';
        @mkdir($dir);
        //    $body = $this->requestData('Search');
        for($i = 7819; $i < 9693; $i++) {
            if ($i > 1) {
                $this->data_url['Search']['url'] = 'search/page:'.$i;
            }
            $body = $this->requestData('Search');
            file_put_contents($dir.'/'.$i.'.htm', $body);
            echo '<b>next page: '.$i.'</b><br>';
            flush();
            //return;

        }
        return;
    }



    function processVilla($xml, $num)
    {
        file_put_contents(PROJECT_ROOT.'data/data'.$num.'.txt', $xml);
    }

    /**
    Список регионов на странице результатов поиска
    <li><a href="/Africa/r10.htm" class="region-refinement">
								Africa rentals
								(1,545)
								</a></li>
								<li><a href="/Asia/r1342.htm" class="region-refinement">
								Asia rentals
    */

    function getRegions($url_arr)
    {
        $dir = PROJECT_ROOT.'data/'.date('Ymd').'_reg';
        @mkdir($dir);

        foreach ($url_arr[1] as $k => $url) {
            $this->data_url['Search']['url'] = $url;
            $body = $this->requestData('Search');
            file_put_contents($dir.'/'.$url_arr[2][$k].'.txt', $body);

            //$regions_tpl = '/<a href="\/(([^\/]+)\/r(\d+).htm)" class="region-refinement">\n[\s]+([^\n]+)([^<]+)</';
            $regions_tpl = '/<a href="\/([^"]+)" class="region-refinement">\n[\s]+([^\n]+)([^<]+)</';
            preg_match_all ($regions_tpl, $body, $regions_match);

            if ($regions_match) {
                $this->getRegions($regions_match);
            }
            echo '<b'.$url_arr[2][$k].'</b><br>';
            flush();

        }
    }

    function getVilla($id, $img='', $dir='')
    {
        if (!$dir) {
            $dir = PROJECT_ROOT.'data/'.date('Ymd').'_villa';
            @mkdir($dir);
        }
        $this->data_url['Search']['url'] = 'p'.$id;
        $body = $this->requestData('Search');
        if (strlen($body) > 1024) {
            //echo $id.'<br>';
            file_put_contents($dir.'/'.$id.'.htm', $body.$img);
            return $id.'.htm';
        }
    }

    function getLocation($id, $page=0, $url='')
    {
        $page += 1;
        $dir = PROJECT_ROOT.'data/'.date('Ymd').'_loc';
        @mkdir($dir);
        if ($page == 1) {
            $this->data_url['Search']['url'] = 'r'.$id.'htm';
        } else {
            $this->data_url['Search']['url'] = $url.$page;
        }
        $body = $this->requestData('Search');
        if (strlen($body) > 1024) {
            echo $id.'-'.$page.'<br>';
            file_put_contents($dir.'/r'.$id.'-'.$page.'.htm', $body);
            unset($body);
            $page_tpl = '!/(search/[^:]+?:\d+/page:)(\d+)" class="(next|last)"!';
            preg_match ($page_tpl, $body, $page_match);
            array_shift($page_match);
            if ($page <= $page_match[1]) {
                $this->getLocation($id, $page, $page_match[0]);
            }
        }
    }

    function getVillas($start=2)
    {
        //require_once (PROJECT_ROOT.'/layout/saveData.php');
        $log = '../logs/getVillas.txt';
        //$dir = PROJECT_ROOT.'data/'.date('Ymd').'_list';
        $dir = PROJECT_ROOT.'data/20110617_list';
        $villa_dir = PROJECT_ROOT.'data/20110617_villa';
        @mkdir($villa_dir);

        $start = intval(file_get_contents($log));
        echo $start.'<br>';
        flush();
        //exit;

        //$files_arr = saveData::getFilesArray($dir);
        $id_tpl = '/href="\/(p\d+a?)(\?uni_id=\d+)?" class="listing-url">.*?src="([^"]+)"/s';
        for($i = $start; $i <= MAX_PAGE; $i++) {
            set_time_limit (720);
        //foreach ($files_arr as $k => $file) {
            //echo $file.'<br>';
            $str = file_get_contents($dir.'/'.$i.'.htm');
            //echo $str;
            preg_match_all ($id_tpl, $str, $id_match);
            //echo '<pre>';
            //print_r($id_match);
            foreach ($id_match[1] as $k => $id) {
                //echo $id.'<br>';
                //if (!file_exists(PROJECT_ROOT.'data/'.date('Ymd').'_villa'.'/'.$id.'.htm')) {
                if (!file_exists($villa_dir.'/'.$id.'.htm')) {
                    $this->getVilla($id, $id_match[3][$k], $villa_dir);
                }
            }
            //file_put_contents($log, $i."\n", FILE_APPEND);
            file_put_contents($log, $i);
            //exit;
        }
    }

/*
    [1] => Title
    [3] => Summary
    [4] => Region_id
    [5] => Bedroom
    [6] => Sleeps
    [7] => Bathroom
    [8] => Type (trim)
    [9] => Summary foto
    [10] => Description foto

Array
(
    [geonode] => europe:italy:veneto - venice:verona - lake garda:lake garda:garda
    [locationtype] => resort; waterfront
    [currency] => EUR
    [features] => not suitable for children; pets not allowed; wheelchair inaccessible
    [responsivenesstime] => >48 hrs
    [maxbeds] => 6
    [maxprice] => 3100.0
    [propertyid] => 2027063
    [misc] => family; romantic
    [propertytype] => house
    [propertydetails] => pho24:fln:fulln:son:fmbFD:csn:csmn:HOUSE:pmn:1098945:ppy:ols:ry:transn:classic:micron:rev2:ppln:multin:bookrm:ipmn
    [maxsleeps] => 10
    [maxbaths] => 6.0
)



[id] => 2142830
    [systemId] => trips
    [availabilityUpdated] => 1425379122600
    [sleeps] => 10
    [useQuotableRates] => 1
    [chequesVacancesAccepted] => 
    [propertyId] => 2027063
    [unitId] => 2142830
    [contact] => stdClass Object
        (
            [displayName] => Lia Poggi
            [languagesSpoken] => Array
                (
                    [0] => English
                    [1] => French
                    [2] => German
                    [3] => Italian
                )
[images] => Array
        (
            [0] => stdClass Object
                (
                    [altText] => Garda house rental
                    [altTextTail] => 
                    [imageFiles] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [height] => 86
                                    [imageSize] => SMALL
                                    [secureUri] => https://imagesus-ssl.homeaway.com/mda01/14db2f14-0ecb-40eb-b6bc-1b6ebbfa5dcd.1.1
                                    [uri] => http://imagesus.homeaway.co.uk/mda01/14db2f14-0ecb-40eb-b6bc-1b6ebbfa5dcd.1.1
                                    [width] => 133
                                )


            [5] => stdClass Object
                                (
                                    [height] => 260
                                    [imageSize] => 400x300
                                    [secureUri] => https://imagesus-ssl.homeaway.com/mda01/14db2f14-0ecb-40eb-b6bc-1b6ebbfa5dcd.1.6
                                    [uri] => http://imagesus.homeaway.co.uk/mda01/14db2f14-0ecb-40eb-b6bc-1b6ebbfa5dcd.1.6
                                    [width] => 400
                                )

[changeoverDay] => 
    [spu] => trips-2027063-2142830
    [reviewCount] => 2
    [uid] => 2142830
    [olb] => 
    [olp] => 1
    [ipm] => 
    [quotableInquiries] => 1
    [petsAllowed] => 
    [clearstay] => 
    [yesBookIt] => 
    [travelMob] => 
    [ppb] => 
    [exppb] => 
    [requireInquiryForPhone] => 
    [hasExactQuotes] => 1
    [thirdPartyBookingUrl] => 
    [thirdPartyBookable] => 
    [hasEmptyPriceRanges] => 
*/
    function parseVilla2($str)
    {
        //$villa_tpl = '!analyticsdatalayer = {([^}]+)}.*?<h1>([^<]+)</h1>.*?class="(preview|prop-desc-txt)">\s{0,}<p>(.*?)</p>\s{0,}</div>(\s{0,}<div class="js-descriptionCollapse collapse">\s{0,}<p>(.*?)</p>)?.*?"price-large">([^\d]+)([0-9,.]+)</span>\s{0,}(.*?)\s+.*?unitJSON =(.*?)};!sm';

        $villa_tpl = '!analyticsdatalayer = {([^}]+)}.*?<h1>([^<]+)</h1>.*?class="(preview|prop-desc-txt)">\s{0,}<p>(.*?)</p>\s{0,}</div>(\s{0,}<div class="js-descriptionCollapse collapse">\s{0,}<p>(.*?)</p>)?.*?"price-large">([^\d]+)([0-9,.]+)</span>\s{0,}(.*?)\s+.*?function\(\) {\s+return (.*?)};!sm';


        $villa_tpl = '!analyticsdatalayer = {([^}]+)}.*?<h1>([^<]+)</h1>.*?class="(preview|prop-desc-txt)">\s{0,}<p>(.*?)</p>\s{0,}</div>(\s{0,}<div class="js-descriptionCollapse collapse">\s{0,}<p>(.*?)</p>)?.*?"price-large">([^\d]+)([0-9,.]+)</span>\s{0,}(.*?)\s+.*?"listing":(.*?)\);!sm';


        preg_match ($villa_tpl, $str, $match);

        if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
            print 'Backtrack limit was exhausted!';
        }

        if (!$match) {
            echo "no villa match $id<br>";
            $this->error = "no villa match $id\n";
        }

        array_shift($match);
        $json_arr1 = (array)json_decode('{'.$match[0].'}');
        $json_arr2 = (array)json_decode('{"listing":'.$match[9]);

        $facility_tpl = '/class="span3" id=.*?>([^:]+):(.*?)<\/ul>\s+<\/div>/s';
        //$facility2_tpl = '/<li>\s{0,}(.*?)\s{0,}<\//s';

        $facility2_tpl = '/<li>\s{0,}(<span title=[^>]+>\s+)?([^<]+)\s{0,}</s';
        
        preg_match_all ($facility_tpl, $str, $facility_match);

        //print_r($facility_match);
        if (!$facility_match) {
            echo "no facility_match $id<br>";
            //return;
        }
        if ($facility_match) {
            array_shift($facility_match);
            //print_r($facility_match);
            foreach ($facility_match[0] as $k => $v) {
                preg_match_all($facility2_tpl, $facility_match[1][$k], $options_match);
                //print_r($options_match);
                //array_shift($options_match);
                array_shift($options_match);
                array_shift($options_match);
                $opt_arr[$v] = $options_match[0];
            }
        }



        $img_arr = self::getImageArr($json_arr2[listing]->images);
        $geo_arr = (array)$json_arr2[listing]->geoCode;
        $main_img = $json_arr2[listing]->thumbnailUrl;

        //echo '<pre>';
        //print_r($img_arr);
        //print_r($match);
        
        //print_r($json_arr1);
        //print_r($json_arr2);
        


        if ($geo_arr) {
            $geo_arr[a] = $geo_arr[latitude];
            $geo_arr[b] = $geo_arr[longitude];
            //print_r ($geo_arr);
        }

        if ($match) {
            $ret['id'] = $json_arr1[propertyid];
            $ret['title'] = $match[1];
            $ret['summary'] = '';
            $ret['location'] = $json_arr1[geonode];
            $ret['bedroom'] = $json_arr1[maxbeds];
            $ret['sleeps'] = $json_arr1[maxsleeps];
            $ret['bathroom'] = $json_arr1[maxbaths];
            $ret['type'] = $json_arr1[propertytype];
            //$ret['description_title'] = $match[8];
            $ret['description'] = $match[3].'<br>'.$match[5];
            $ret['main_img'] = $main_img;

            $ret['region_id'] = $region_id_match;
            $ret['notes'] = $notes_match;
            $ret['extra'] = $extra_match;

            $ret['img'] = $img_arr;
            $ret['reviews'] = $json_arr2[reviewCount];
            $ret['minprice'] = str_replace(",", "", $match[7]);
            $ret['maxprice'] = (int)$json_arr1[maxprice];
            $ret['currency'] = $json_arr1[currency];
            $ret['period'] = $match[8];
            $ret['opt'] = $opt_arr;
            $ret['map'] = $geo_arr;
            $ret['json'] = $json_arr;
        }
        

        //print_r($ret);
        unset($str);
        unset($match);
        return $ret;
    }


    function getImageArr($img_obj)
    {
        for ($i=0; $i < sizeof($img_obj); $i++) {
            $images = (array)$img_obj[$i];

            $image_size_arr = $images[imageFiles];

            for ($s=0; $s<10; $s++) {
                if ($image_size_arr[$s]->height == 300) {
                    $img_arr[$i][0] = $image_size_arr[$s]->uri;
                    $img_arr[$i][1] = $images[note];
                    break;
                }
            }

            //$img_arr[$i][0] = $images[imageFiles][6]->uri;
            //$img_arr[$i][1] = $images[note];
        }
        return $img_arr;
    }

}

?>