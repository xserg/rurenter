<?php
/**
 * Videoradar
 * Активация нового пользователя
 * $Id: Users_Activate.php 65 2010-11-30 09:37:41Z xxserg $
*/

//define('DEBUG', 1);
ini_set('display_errors', 'On');

require_once COMMON_LIB.'DVS/Dynamic.php';
//require PROJECT_ROOT.'/layout/sendBooking.php';
require (PROJECT_ROOT.'../villa/layout/getHTTP.php');

class Project_Booking_log_Send extends DVS_Dynamic
{
    /**
     * Права
     */
    //var $perms_arr = array('iu' => 1);

    function getPageData()
    {
        $this->host = 'http://www.villarenters.com';
        $this->req = new GetHTTP;

        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        if ($this->db_obj->N) {
            if ($this->db_obj->book_status == 0) {

                $start_arr = explode ("-", $this->db_obj->start_date);
                $days = $this->db_obj->daysCount();
                // 1 стр.
                $body = $this->getStep1($start_arr, $days);
                $match = $this->parseStep($body);

                //exit;

                if ($match) {
                    // 2 страница
                    $this->getStep2();
                    $body = $this->sendRequest($match);
                    $match2 = $this->parseStep($body);

                        $users_obj = DB_DataObject::factory('users');
                        $users_obj->get($this->db_obj->user_id);
                        $this->params = array();
                        $this->getStep3($users_obj, 1);
                        //$this->setGuests();
                        $body = $this->sendRequest($match2);
                        $match3 = $this->parseStep($body);
                        
                        //header('Location: '.$this->host.str_replace('&amp;', '&', $match[1]));

                        
                        /*
                        $this->params = array();
                        $this->getStep3($users_obj, 0);
                        $this->setGuests();
                       
                        //echo '<pre>';
                        //print_r($match3);
                        //print_r($this->params);
                        
                        $body = $this->sendRequest($match3);
                         */
                        


                }


                /*
                // 2 страница
                $body = $this->getStep2($match);

                $match2 = $this->parseStep($body);
                
                // 3 стр гости
                $body = $this->getStep2($match2, 3);
                */


                $body = str_replace('</title>', '</title><base href="http://villarenters.com">', $body);
                echo $body;
                exit;

            } else {
                $this->msg = DVS_ERROR_REACTIVATE;
            }
        } else {
            $this->msg = DVS_ERROR_ACTIVATE;
        }
    }

    function sendRequest($match)
    {
        $this->req = new GetHTTP;
        $this->params['__VIEWSTATE'] = $match[2];
        $this->params['__EVENTVALIDATION'] = $match[3];
        $req_params = array(
                    'host' => $this->host,
                    'url' => str_replace('&amp;', '&', $match[1]),
                    'method' => 'post',
                    'params' => $this->params,
        );
        $body = $this->req->request($req_params);
        return $body;
    }


    function getStep1($start_arr, $days)
    {
        $params = array(
                    'ref' => $this->db_obj->villa_id,
                    'day' => $start_arr[2],
                    'mth' => $start_arr[1],
                    'yr' => $start_arr[0],
                    'dur' => $days,
                    'adl' => $this->db_obj->people,
                    'chd' => $this->db_obj->childs,
                    'inf' => $this->db_obj->infants, 
                    'rag' => VR_ACCOUNT_NUM,
                    'rcam' => '',
        );

        $req_params = array(
                    'host' => $this->host,
                    'url' => '/Booking.aspx',
                    'method' => 'get',
                    'params' => $params,
        );

        $body = $this->req->request($req_params);
        return $body;
    }

    /**
    Получение VIEWSTATE
    Проверка ошибок
    array(
    1=> action
    2=> viewstate
    3=> eventvalidation
    */
    function parseStep($str)
    {
        $vars_tpl = '!action="([^"]+)".*?__VIEWSTATE" value="([^"]+)".*?id="__EVENTVALIDATION" value="([^"]+)"!s';
        preg_match($vars_tpl, $str, $vars_match);
        return $vars_match;
    }

    function getStep2()
    {
        $this->params = array(
        '__EVENTTARGET' => '',
        '__EVENTARGUMENT' => '',
        'Tabs_ClientState' => '{"ActiveTabIndex":1,"TabState":[true,true,true,true,true]}',
        '__LASTFOCUS' => '',
        'Tabs$SizeOfPartyAndOptionalExtras$ddlAdults' => $this->db_obj->people,
        'Tabs$SizeOfPartyAndOptionalExtras$ddlChildren' => $this->db_obj->childs,
        'Tabs$SizeOfPartyAndOptionalExtras$ddlInfants' => $this->db_obj->infants,
        'Tabs$SizeOfPartyAndOptionalExtras$SpecialDiscountTB' => '', 
        // Новый аккаунт
        'Tabs$pnlPartyDetails$NewRenterTBList' => 0,
        // Кнопка Continue 1
        'Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.x' => '34',
        'Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.y' => '26',
        'RBDToBePaid' => '0'
         );
    }

    function getStep3($users_obj, $button)
    {
        $this->params = array(
        '__EVENTTARGET' => '',
        '__EVENTARGUMENT' => '',
        'Tabs_ClientState' => '{"ActiveTabIndex":1,"TabState":[true,true,true,true,true]}',
        '__LASTFOCUS' => '',
        'Tabs$SizeOfPartyAndOptionalExtras$ddlAdults' => $this->db_obj->people,
        'Tabs$SizeOfPartyAndOptionalExtras$ddlChildren' => $this->db_obj->childs,
        'Tabs$SizeOfPartyAndOptionalExtras$ddlInfants' => $this->db_obj->infants,
        'Tabs$SizeOfPartyAndOptionalExtras$SpecialDiscountTB' => '', 
            
            'Tabs$pnlPartyDetails$ddlTitle' =>                         'Mr.',
            'Tabs$pnlPartyDetails$txtFirstName' =>                     $users_obj->name,
            'Tabs$pnlPartyDetails$txtLastName' =>                      $users_obj->lastname,
            'Tabs$pnlPartyDetails$ddlAge' =>                           $users_obj->age,
            'Tabs$pnlPartyDetails$ddlCountry' =>                       $users_obj->country,
            'Tabs$pnlPartyDetails$txtAddress1' =>                      $users_obj->house_num,
            'Tabs$pnlPartyDetails$txtAddress2' =>                      $users_obj->street,
            'Tabs$pnlPartyDetails$txtAddress3' =>                       '',
            'Tabs$pnlPartyDetails$txtTown' =>                          $users_obj->city,
            'Tabs$pnlPartyDetails$txtCounty' =>                         '',
            'Tabs$pnlPartyDetails$txtPostCode' =>                      '11111',
            'Tabs$pnlPartyDetails$txtHomeTel' =>                       $users_obj->home_phone,
            'Tabs$pnlPartyDetails$txtMobileTel' =>                     $users_obj->mobile_phone,
            'Tabs$pnlPartyDetails$txtNewPassword' =>                   $users_obj->password,
            'RBDToBePaid' => '0',
            );
            if ($button) {
        // Новый аккаунт
            $this->params['Tabs$pnlPartyDetails$NewRenterTBList'] = 0;
            $this->params['Tabs$pnlPartyDetails$emailTB'] =  $users_obj->email;
            $this->params['Tabs$pnlPartyDetails$confirmEmail'] = $users_obj->email;
             $this->params['Tabs$pnlPartyDetails$ibtnPartyContinue.x'] = 31;
             $this->params['Tabs$pnlPartyDetails$ibtnPartyContinue.y'] = 37;
           }
    }

    function setGuests()
    {
        //DB_DataObject::DebugLevel(1);
        $guests_obj = DB_DataObject::factory('guests');
        $guests_obj->booking_log_id = $this->db_obj->id;
        $guests_obj->find();
        $n = 2;
        while ($guests_obj->fetch()) {
            $this->params['Tabs$pnlSpecialReq$dgPartyMembers$ctl0'.$n.'$ddlTitle'] = 'Mr.';
            $this->params['Tabs$pnlSpecialReq$dgPartyMembers$ctl0'.$n.'$txtFirstName'] = $guests_obj->guest_name;
            $this->params['Tabs$pnlSpecialReq$dgPartyMembers$ctl0'.$n.'$txtLastName'] = $guests_obj->guest_lastname;
            $this->params['Tabs$pnlSpecialReq$dgPartyMembers$ctl0'.$n.'$ddlAge'] = $guests_obj->age;
            $n++;
        }
        $this->params['Tabs$pnlSpecialReq$ibtnSpecialContinue.x'] = 22;
        $this->params['Tabs$pnlSpecialReq$ibtnSpecialContinue.y'] = 21;

    }


    /**
        Получение страницы 2 Your details
        __EVENTTARGET
        __EVENTARGUMENT
        Tabs_ClientState {"ActiveTabIndex":0,"TabState":[true,true,true,true,true]}
        __LASTFOCUS
        __VIEWSTATE
        __EVENTVALIDATION
        Tabs$SizeOfPartyAndOptionalExtras$ddlAdults 1
        Tabs$SizeOfPartyAndOptionalExtras$ddlChildren 0
        Tabs$SizeOfPartyAndOptionalExtras$ddlInfants 0
        Tabs$SizeOfPartyAndOptionalExtras$SpecialDiscount TB
        Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.x 37
        Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.y 25
        Tabs$pnlPartyDetails$ddlTitle Mr.
        Tabs$pnlPartyDetails$txtFirstName
        Tabs$pnlPartyDetails$txtLastName
        Tabs$pnlPartyDetails$ddlAge 0
        Tabs$pnlPartyDetails$txtAddress1
        Tabs$pnlPartyDetails$txtAddress2
        Tabs$pnlPartyDetails$txtAddress3
        Tabs$pnlPartyDetails$txtTown
        Tabs$pnlPartyDetails$txtCounty
        Tabs$pnlPartyDetails$txtPostCode
        Tabs$pnlPartyDetails$txtHomeTel
        Tabs$pnlPartyDetails$txtMobileTel
        Tabs$pnlPartyDetails$txtNewPassword
        RBDToBePaid 0
    */
    function getStep22($match='', $step='')
    {
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->db_obj->user_id);

        
        $start_arr = explode ("-", $this->db_obj->start_date);
        $days = $this->db_obj->daysCount();
        $url = '/Booking.aspx?ref='.$this->db_obj->villa_id.'&day='.$start_arr[2].'&mth='.$start_arr[1].'&yr='.$start_arr[0].'&dur='.$days.'&adl='.$this->db_obj->people.'&chd='.$this->db_obj->childs.'&inf='.$this->db_obj->infants.'&rag='.VR_ACCOUNT_NUM.'&rcam=';
        
        $params = array(
        '__EVENTTARGET' => '',
        '__EVENTARGUMENT' => '',
        'Tabs_ClientState' => '{"ActiveTabIndex":1,"TabState":[true,true,true,true,true]}',
        '__LASTFOCUS' => '',
        '__VIEWSTATE' => $match[2],
        '__EVENTVALIDATION' => $match[3],
        'Tabs$SizeOfPartyAndOptionalExtras$ddlAdults' => $this->db_obj->people,
        'Tabs$SizeOfPartyAndOptionalExtras$ddlChildren' => $this->db_obj->childs,
        'Tabs$SizeOfPartyAndOptionalExtras$ddlInfants' => $this->db_obj->infants,
        'Tabs$SizeOfPartyAndOptionalExtras$SpecialDiscountTB' => '', 
        
        // Новый аккаунт
        'Tabs$pnlPartyDetails$NewRenterTBList' => 0,
        // Кнопка Continue 1
        'Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.x' => '34',
        'Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.y' => '26',
        'RBDToBePaid' => '0'
            );
        
        if ($step == 3) {
        $params += array(
            'Tabs$pnlPartyDetails$emailTB' =>                          $users_obj->email,
            'Tabs$pnlPartyDetails$confirmEmail' =>                     $users_obj->email,
            'Tabs$pnlPartyDetails$ddlTitle' =>                         'Mr.',
            'Tabs$pnlPartyDetails$txtFirstName' =>                     $users_obj->name,
            'Tabs$pnlPartyDetails$txtLastName' =>                      $users_obj->lastname,
            'Tabs$pnlPartyDetails$ddlAge' =>                           $users_obj->age,
            'Tabs$pnlPartyDetails$ddlCountry' =>                       $users_obj->country,
            'Tabs$pnlPartyDetails$txtAddress1' =>                      $users_obj->house_num,
            'Tabs$pnlPartyDetails$txtAddress2' =>                      $users_obj->street,
            'Tabs$pnlPartyDetails$txtAddress3' =>                       '',
            'Tabs$pnlPartyDetails$txtTown' =>                          $users_obj->city,
            'Tabs$pnlPartyDetails$txtCounty' =>                         '',
            'Tabs$pnlPartyDetails$txtPostCode' =>                      '11111',
            'Tabs$pnlPartyDetails$txtHomeTel' =>                       $users_obj->home_phone,
            'Tabs$pnlPartyDetails$txtMobileTel' =>                     $users_obj->mobile_phone,
            'Tabs$pnlPartyDetails$txtNewPassword' =>                   $users_obj->password,
            'Tabs$pnlPartyDetails$ibtnPartyContinue.x' =>              31,
            'Tabs$pnlPartyDetails$ibtnPartyContinue.y' =>              37,
        );
        }

       

        $req_params = array(
                    'host' => $this->host,
                    'url' => str_replace('&amp;', '&', $match[1]),
                    'method' => 'post',
                    'params' => $params,
        );

        $body = $this->req->request($req_params);
        return $body;
    }

    function setParams()
    {
        $params = array(
        '__EVENTTARGET' => '',
        '__EVENTARGUMENT' => '',
        'Tabs_ClientState' => '{"ActiveTabIndex":0,"TabState":[true,true,true,true,true]}',
        '__LASTFOCUS' => '',
        '__VIEWSTATE' => $match[2],
        '__EVENTVALIDATION' => $match[3],
        'Tabs$SizeOfPartyAndOptionalExtras$ddlAdults' => '1',
        'Tabs$SizeOfPartyAndOptionalExtras$ddlChildren' => '0',
        'Tabs$SizeOfPartyAndOptionalExtras$ddlInfants' => '0',
        'Tabs$SizeOfPartyAndOptionalExtras$SpecialDiscountTB' => '',

        'Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.x' => '34',
        'Tabs$SizeOfPartyAndOptionalExtras$ibtnQuoteContinue.y' => '26',

        'Tabs$pnlPartyDetails$NewRenterTBList' =>                  0,
        'Tabs$pnlPartyDetails$emailTB' =>                          '',
        'Tabs$pnlPartyDetails$confirmEmail' =>                     '',
        'Tabs$pnlPartyDetails$ddlTitle' =>                         '',
        'Tabs$pnlPartyDetails$txtFirstName' =>                     '',
        'Tabs$pnlPartyDetails$txtLastName' =>                      '',
        'Tabs$pnlPartyDetails$ddlAge' =>                           '',
        'Tabs$pnlPartyDetails$ddlCountry' =>                       '',
        'Tabs$pnlPartyDetails$txtAddress1' =>                      '',
        'Tabs$pnlPartyDetails$txtAddress2' =>                      '',
        'Tabs$pnlPartyDetails$txtAddress3' =>                       '',
        'Tabs$pnlPartyDetails$txtTown' =>                          '',
        'Tabs$pnlPartyDetails$txtCounty' =>                         '',
        'Tabs$pnlPartyDetails$txtPostCode' =>                      '11111',
        'Tabs$pnlPartyDetails$txtHomeTel' =>                       '',
        'Tabs$pnlPartyDetails$txtMobileTel' =>                     '',
        'Tabs$pnlPartyDetails$txtNewPassword' =>                   '',

        'Tabs$pnlPartyDetails$ibtnPartyContinue.x' =>              61,
        'Tabs$pnlPartyDetails$ibtnPartyContinue.y' =>              27,

        'Tabs$pnlSpecialReq$dgPartyMembers$ctl02$ddlTitle' =>      0,
        'Tabs$pnlSpecialReq$dgPartyMembers$ctl02$txtFirstName' =>  '',
        'Tabs$pnlSpecialReq$dgPartyMembers$ctl02$txtLastName' =>   '',
        'Tabs$pnlSpecialReq$dgPartyMembers$ctl02$ddlAge' =>        0,

        'RBDToBePaid' => '0'
        );
    }
}

?>