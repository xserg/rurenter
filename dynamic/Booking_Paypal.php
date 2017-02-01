<?php
/**
 * Входящий платеж
 * @package rurenter
 * $Id: Transactions_Pay.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
require_once COMMON_LIB.'DVS/Auth.php';

require_once PROJECT_ROOT.'lib/paypal-sdk/samples/PPBootStrap.php';

define('DVS_ERROR_PAY', 'Недостаточная сумма на счету!');
define('PAYED', 'Заявка оплачена!');


session_start();




class Project_Booking_Paypal extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ou' => 1, 'iu' => 1);

    public $paypal_dir = 'lib/paypal-sdk/samples';

    private $lang_ru = array(
        'PayPal_payment' => 'Платеж через PayPal',
        'Payment_for_booking' => 'Оплата заявки на аренду',
        'property' => 'объекта',
        'submit_text' => 'Подтвердить платеж',
    );

    private $lang_en = array(
        'PayPal_payment' => 'PayPal payment',
        'Payment_for_booking' => 'Payment for booking',
        'property' => 'property',
        'submit_text' => 'Confirm payment',
    );


    /** Запрос с Payments_in_Request
      * 1 - SetExpressCheckout - redirect Paypal.com
      * 2 - Paypal.com(token) - GetExpressCheckoutDetails - Форма Подтверждение оплаты
      * 3 - DoExpressCheckout - сохранение транзакции - success
      */

    function getPageData()
    {

        /**
        echo '<pre>';
        print_r($_POST);
        */
        if ($_SESSION['lang']) {
            //$this->lang = $_SESSION['lang'];
        }
        $this->words = $this->{'lang_'.$this->lang};


        if ($_POST['setExpess']) {
            $this->setExpress();
        }



        if(isset($_REQUEST['token']) && !$_POST['DoPayment']) {
            $this->createTemplateObj();
            $this->getDetails();
            $form = $this->payForm();
            $this->template_obj->setGlobalVariable($this->words);
            $center = $this->template_obj->get();
        }

        if ($_POST['DoPayment']) {
            if ($transaction_id = $this->doCheckout()) {
                $this->proccesData($transaction_id);
            }
            $this->logRequest();
        } 


        //return;
    
        if ($this->error) {
            echo 'ERROR: '.$this->error;
        } else {
            //echo 'OK';
        }
        //exit;
        //$page_arr['BODY_CLASS']   = 'property';



        $page_arr['CENTER_TITLE']         =  $this->words['PayPal_payment'];
        $page_arr['CENTER']         =  $center;
        return $page_arr;
    }


    function proccesData($transaction_id)
    {   
        //require_once PROJECT_ROOT.'WWW/paypal/constants.php';
        $payment_in_id = $_SESSION['payment_in_id'];
        $ammount = $_SESSION['amt'];
        $currency = $_SESSION['currencyCode'];


        $payments_in_obj = DB_DataObject::factory('payments_in');
        $payments_in_obj->get($payment_in_id);

        if (!$payments_in_obj->N) {
            $this->error = PAYMENT_IN_NOT_FOUND;
            return;
        }
/*
        if ($payments_in_obj->ammount != $ammount) {
            $this->error = 'error_ammount';
            return;
        }

        if ($payments_in_obj->currency != 'RUR') {
            $this->error = 'error_currency';
            return;
        }
*/

            $booking_id = $payments_in_obj->booking_id;
            $this->db_obj->get($booking_id);
            if (!$this->db_obj->N) {
                //$this->msg = BOOKING_NOT_FOUND;
                $this->error = BOOKING_NOT_FOUND;
                return;
            }

            if ($this->db_obj->book_status == 4) {
                $this->error = ALREADY_PAYED;
                //$this->msg = ALREADY_PAYED;
                return;
            }

            $user_id_from = $payments_in_obj->user_id;
            //$ammount = $payments_in_obj->ammount;
            //$currency = $payments_in_obj->currency;

            $villa_obj = DB_DataObject::factory('villa');
            $villa_obj->get($this->db_obj->villa_id);
            if (!$villa_obj->user_id) {
                $this->error = VILLA_NOT_FOUND;
                //$this->msg = VILLA_NOT_FOUND;
                return;
            }
            $user_id_to = $villa_obj->user_id;

            $transactions_obj = DB_DataObject::factory('transactions');
            $result = $transactions_obj->fromToTransaction($user_id_from, $user_id_to, $ammount, $currency, $this->db_obj->id, $payments_in_obj->id);
            //echo $result;

            if ($result) {
                $this->db_obj->book_status = 4;
                $this->db_obj->update();
                $payments_in_obj->external_id = $transaction_id;
                $payments_in_obj->ammount = $ammount;
                $payments_in_obj->currency = $currency;
                $payments_in_obj->status = 1;
                $payments_in_obj->update();
                $this->msg = PAYED;
                //echo 'OK';
                $this->infoLetters($villa_obj, $user_id_from, $ammount, $currency);
                
                $location = '/'.$this->lang.'/office/pay_success';

                header('location: '.$location);
                return;
            } else {
                //$this->msg = DVS_ERROR;
                $this->error = SQL_ERROR;
                return;
            }
            
        exit;

    }

    function infoLetters($villa_obj, $user_id_from, $ammount=0, $currency='')
    {
        //Владелец виллы
        $users_obj_to = DB_DataObject::factory('users');
        $users_obj_to->get($villa_obj->user_id);

        //Владелец виллы
        $users_obj_from = DB_DataObject::factory('users');
        $users_obj_from->get($user_id_from);


        require_once COMMON_LIB.'DVS/Mail.php';
        $data['to'] = $users_obj_to->email;
        $data['subject'] = 'ruRenter.ru PayPal payment for #'.$this->db_obj->id;
        $booking_link = SERVER_URL."/office/?op=booking&act=showcard&id=".$this->db_obj->id;
        $data_arr = array(
            'booking_id' => $this->db_obj->id,
            'ammount' => $ammount,
            'currency' => $currency,
            'booking_link' => $booking_link,
            'villa_id' => $villa_obj->id,
            'villa_name' => $villa_obj->title,
            'user_id_from' => $user_id_from,
            'user_name_from' => $users_obj_from->name,
            'start_date' => $this->db_obj->start_date,
            'end_date' => $this->db_obj->end_date,
            );
        $data['body'] = DVS_Mail::letter($data_arr, 'pay_letter.tpl');
        if ($users_obj_to->email) {
            //print_r($data);
            DVS_Mail::send($data);
        }
        $data['to'] = $users_obj_from->email;
        if ($users_obj_from->email) {
            DVS_Mail::send($data);
        }
        $data['to'] = MAIL_ADMIN;
        //$data['subject'] = 'ruRenter.ru booking '.$this->db_obj->id;
        //$data['body'] = DVS_Mail::letter($data_arr, 'pay_letter.tpl');
        DVS_Mail::send($data);
        
    }

    function logRequest()
    {
        $str = date("Y-m-d H:i:s\n");
        $str .= DVS_Auth::getIp()."\n";

        foreach ($_REQUEST as $k => $v) {
            $str .= "$k => $v\n";
        }
        if ($this->error) {
            $str .= 'ERROR: '.$this->error."\n\n";
        } else {
            $str .= "OK\n\n";
        }
        file_put_contents(PROJECT_ROOT.'logs/paypal.log', $str, FILE_APPEND);
    }

    function payForm()
    {
        //$this->createTemplateObj();
        $this->template_obj->loadTemplateFile('paypal_confirm.tpl');
        $fields = array(
            'ammount' => $_SESSION['amt'],
            'currency' => $_SESSION['currencyCode'],
            'description' => $_SESSION['description'],
            'payment_in_id' => $_SESSION['payment_in_id'],
            'token' => $_SESSION['token'],
            'payerID' => $_SESSION['payer_id'],
            'action' => '/'.$this->lang.'/office/paypal.php',
        );
        $this->template_obj->setVariable($fields);
        return;
    }

    function setExpress()
    {
        $logger = new PPLoggingManager('SetExpressCheckout');

        $url = dirname('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI']);
        //$returnUrl = "$url/GetExpressCheckout.php";
        //$cancelUrl = "$url/SetExpressCheckout.php" ;
        //echo $url;

        $returnUrl = "$url/paypal.php";
        $cancelUrl = "$url/error_pay";

        $_SESSION['amt']=isset($_REQUEST['amt'])?$_REQUEST['amt']:null;
        $_SESSION['currencyCode']=$_REQUEST['currencyCode'];
        $_SESSION['paymentType']=$_REQUEST['paymentType'];


        $orderTotalValue = $_REQUEST['amt'];
        $currencyCode = $_REQUEST['currencyCode'];
        
        $paymentDetails = new PaymentDetailsType();

        $itemTotalValue = 0;
        $taxTotalValue = 0;
        $i=0;
            $itemAmount = new BasicAmountType($currencyCode, $_REQUEST['amt']);
            $itemDetails = new PaymentDetailsItemType();
            $itemDetails->Name = $_REQUEST['itemName'];
            $itemDetails->Amount = $_REQUEST['amt'];
            $itemDetails->Quantity = 1;
            
            $paymentDetails->PaymentDetailsItem[$i] = $itemDetails;

        $paymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);

        $paymentDetails->PaymentAction = $_REQUEST['paymentType'];

        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $paymentDetails;
        $setECReqDetails->CancelURL = $cancelUrl;
        $setECReqDetails->ReturnURL = $returnUrl;
        $setECReqDetails->LocaleCode = $this->lang == 'ru' ? 'RU' : 'US';

        // Shipping details
        $setECReqDetails->NoShipping = 1;
        $setECReqDetails->AddressOverride = 0;
        $setECReqDetails->ReqConfirmShipping = $_REQUEST['reqConfirmShipping'];

        // Billing agreement
        $billingAgreementDetails = new BillingAgreementDetailsType($_REQUEST['billingType']);
        $billingAgreementDetails->BillingAgreementDescription = $_REQUEST['billingAgreementText'];
        $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);

        // Display options
        $setECReqDetails->cppheaderimage = $_REQUEST['cppheaderimage'];
        $setECReqDetails->cppheaderbordercolor = $_REQUEST['cppheaderbordercolor'];
        $setECReqDetails->cppheaderbackcolor = $_REQUEST['cppheaderbackcolor'];
        $setECReqDetails->cpppayflowcolor = $_REQUEST['cpppayflowcolor'];
        $setECReqDetails->cppcartbordercolor = $_REQUEST['cppcartbordercolor'];
        $setECReqDetails->cpplogoimage = $_REQUEST['cpplogoimage'];
        $setECReqDetails->PageStyle = $_REQUEST['pageStyle'];
        $setECReqDetails->BrandName = $_REQUEST['brandName'];

        // Advanced options
        $setECReqDetails->AllowNote = $_REQUEST['allowNote'];

        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        $paypalService = new PayPalAPIInterfaceServiceService();
        try {
            /* wrap API method calls on the service object with a try catch */
            $setECResponse = $paypalService->SetExpressCheckout($setECReq);
        } catch (Exception $ex) {
            
            include_once(PROJECT_ROOT.$this->paypal_dir."/Error.php");
            exit;
        }
        if(isset($setECResponse)) {
            /*
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>$setECResponse->Ack</div> </td></tr>";
            echo "<tr><td>Token :</td><td><div id='Token'>$setECResponse->Token</div> </td></tr>";
            echo "</table>";
            echo '<pre>';
            print_r($setECResponse);
            echo '</pre>';
            */
            if($setECResponse->Ack =='Success') {
                $token = $setECResponse->Token;
                $_SESSION['token'] = $token;

                // Redirect to paypal.com here
                $payPalURL = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=' . $token;
                //$payPalURL = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . $token;
                header("Location: ".$payPalURL);
                //echo" <a href=$payPalURL><b>* Redirect to PayPal to login </b></a><br>";
            } else {
                //echo 'Error';
                //print_r($setECResponse);
                $this->msg = 'Paypal :'.$setECResponse->Errors[0]->LongMessage;
            }
        }
            //require_once PROJECT_ROOT.$this->paypal_dir.'/Response.php';
    }

    function getDetails()
    {
        $logger = new PPLoggingManager('GetExpressCheckout');

        $token = $_REQUEST['token'];

        $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);

        $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
        $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

        $paypalService = new PayPalAPIInterfaceServiceService();
        try {
            /* wrap API method calls on the service object with a try catch */
            $getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
        } catch (Exception $ex) {
            include_once(PROJECT_ROOT.$this->paypal_dir."/Error.php");
            exit;
        }
        if(isset($getECResponse)) {

            $_SESSION['token']=$_REQUEST['token'];
            $_SESSION['payer_id'] = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;

            /*
            $_SESSION['amt']=isset($_REQUEST['paymentAmount'])?$_REQUEST['paymentAmount']:null;
            $_SESSION['currencyCode']=$_REQUEST['currencyCode'];
            $_SESSION['paymentType']=$_REQUEST['paymentType'];
            */
            /*
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>".$getECResponse->Ack."</div> </td></tr>";
            echo "<tr><td>Token :</td><td><div id='Token'>".$getECResponse->GetExpressCheckoutDetailsResponseDetails->Token."</div></td></tr>";
            echo "<tr><td>PayerID :</td><td><div id='PayerID'>".$getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID."</div></td></tr>";
            echo "<tr><td>PayerStatus :</td><td><div id='PayerStatus'>".$getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerStatus."</div></td></tr>";
            echo "</table>";
            echo '<pre>';
            print_r($getECResponse);
            echo '</pre>';
            */
        }
        //require_once PROJECT_ROOT.$this->paypal_dir.'/Response.php';
    }

    function doCheckout()
    {
            //require_once('../PPBootStrap.php');
            //session_start();

            $logger = new PPLoggingManager('DoExpressCheckout');

            $token =urlencode( $_REQUEST['token']);
            $payerId=urlencode(  $_REQUEST['payerID']);
            $paymentAction = urlencode(  $_REQUEST['paymentAction']);

            // ------------------------------------------------------------------
            // this section is optional if parameters required for DoExpressCheckout is retrieved from your database
            /*
            $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
            $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
            $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

            $paypalService = new PayPalAPIInterfaceServiceService();
            try {
                // wrap API method calls on the service object with a try catch 
                $getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
            } catch (Exception $ex) {
                include_once(PROJECT_ROOT.$this->paypal_dir."/Error.php");
                exit;
            }
            */
            //----------------------------------------------------------------------------
            $paypalService = new PayPalAPIInterfaceServiceService();
            $orderTotal = new BasicAmountType();
            $orderTotal->currencyID = $_SESSION['currencyCode'];
            $orderTotal->value = $_SESSION['amt'];

            $paymentDetails= new PaymentDetailsType();
            $paymentDetails->OrderTotal = $orderTotal;
            if(isset($_REQUEST['notifyURL']))
            {
                $paymentDetails->NotifyURL = $_REQUEST['notifyURL'];
            }

            $DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
            $DoECRequestDetails->PayerID = $payerId;
            $DoECRequestDetails->Token = $token;
            $DoECRequestDetails->PaymentAction = $paymentAction;
            $DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

            $DoECRequest = new DoExpressCheckoutPaymentRequestType();
            $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;


            $DoECReq = new DoExpressCheckoutPaymentReq();
            $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

            try {
                /* wrap API method calls on the service object with a try catch */
                $DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);
            } catch (Exception $ex) {
                include_once(PROJECT_ROOT.$this->paypal_dir."/Error.php");
                exit;
            }
            if(isset($DoECResponse)) {
                $transaction_id = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID;
                /*
                echo "<table>";
                echo "<tr><td>Ack :</td><td><div id='Ack'>$DoECResponse->Ack</div> </td></tr>";
                if(isset($DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo)) {
                    echo "<tr><td>TransactionID :</td><td><div id='TransactionID'>". $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID."</div> </td></tr>";
                }
                echo "</table>";
                echo "<pre>";
                print_r($DoECResponse);
                echo "</pre>";
                */
                return $transaction_id;
            }
            //require_once PROJECT_ROOT.$this->paypal_dir.'/Response.php';
    }
}

?>