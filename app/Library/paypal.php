<?php  /*App::import('currency');
$currency=new Currency(); */
/***********************************************************
This File Sets Up Calls to Paypal by arranging url information.
***********************************************************/

namespace App\Library;

//use App\Library\constants;

//require_once 'constants.php';
class Paypal{
    
    function __construct(){
        
    }
	
	
  
 function withdraw($email,$amount,$id)
 {
     
        $mode='Sandbox';
        define('API_USERNAME', 'jambulingam-business-us_api1.gmail.com');
        define('API_PASSWORD','TS7NKH2H7FBNV46C');
        define('API_SIGNATURE','AFcWxV21C7fd0v3bYYYRCpSSRl31ACLXnJYTapj4v820AqE2FhH6UzI9');
        //$this->mode=$settings['Paypalsetting']['mode'];
        if ($mode == 'Sandbox'){
            define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');
            define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=');
        }else{
            define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
            define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=');
        }

        define('USE_PROXY',FALSE);
        define('PROXY_HOST', '127.0.0.1');
        define('PROXY_PORT', '808');
        define('VERSION', '72.0');




        $vEmailSubject = 'Withdraw Paypal';
        //$environment = 'sandbox';
        $emailSubject = urlencode($vEmailSubject);
        $receiverType = urlencode('EmailAddress');
        $currency = urlencode('USD');
        $receivers = array(
          0 => array(
            'receiverEmail' => $email, 
            'amount' => $amount,
            'uniqueID' => $id, // 13 chars max
            'note' => " Payment Note"),  
        );
        $receiversLenght = count($receivers);
        $nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
        $receiversArray = array();
        for($i = 0; $i < $receiversLenght; $i++)
        {
         $receiversArray[$i] = $receivers[$i];
        }

        foreach($receiversArray as $i => $receiverData)
        {
         $receiverEmail = urlencode($receiverData['receiverEmail']);
         $amount = urlencode($receiverData['amount']);
         $uniqueID = urlencode($receiverData['uniqueID']);
         $note = urlencode($receiverData['note']);
         $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
        }

        $httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);

        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {    
         
         return $httpParsedResponseAr;
        }
        else
        {
         
         return $httpParsedResponseAr;
        }


         }


       function PPHttpPost($methodName_, $nvpStr_)
        {
         //global $environment;
         
        /*$API_UserName = urlencode('payment_api1.spiderwerkz.com');
        $API_Password = urlencode('EEJ2QVQXXELP93B9');
        $API_Signature = urlencode('AmOhxtbf110LBwo10.mFhlW4x9gWAWQ3eVwULYvFF9ckIGctrL0rzGVr');
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
        $version = urlencode('51.0');*/
        //require_once 'constants.php';
        $API_UserName=API_USERNAME;
        $API_Password=API_PASSWORD;
        $API_Signature=API_SIGNATURE;
        $API_Endpoint =API_ENDPOINT;
        $version=VERSION;

         // Set the curl parameters.
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
         curl_setopt($ch, CURLOPT_VERBOSE, 1);

         // Turn off the server and peer verification (TrustManager Concept).
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_POST, 1);

         // Set the API operation, version, and API signature in the request.

         $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";


         // Set the request as a POST FIELD for curl.
         curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq."&".$nvpStr_);

         // Get response from the server.
         $httpResponse = curl_exec($ch);

         if( !$httpResponse)
         {
          echo $methodName_ . ' failed: ' . curl_error($ch) . '(' . curl_errno($ch) .')';
         }

         // Extract the response details.
         $httpResponseAr = explode("&", $httpResponse);

         $httpParsedResponseAr = array();
         foreach ($httpResponseAr as $i => $value)
         {
          $tmpAr = explode("=", $value);
          if(sizeof($tmpAr) > 1)
          {
           $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
          }
         }

         if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
         {
          exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
         }


         return $httpParsedResponseAr;
         
        }

 
}
