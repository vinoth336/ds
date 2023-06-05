<?php 
/**
 * Paypal Direct Payment API Component class file.
 */
namespace App\Library;
use App\Library\paypal;


class PaypalComponent {
    
    function processPayment($paymentInfo,$function){
        $paypal = new Paypal();
        if ($function=="DoDirectPayment")
            return $paypal->DoDirectPayment($paymentInfo);
        elseif ($function=="SetExpressCheckout")
            return $paypal->SetExpressCheckout($paymentInfo);
        elseif ($function=="GetExpressCheckoutDetails")
            return $paypal->GetExpressCheckoutDetails($paymentInfo);
        elseif ($function=="DoExpressCheckoutPayment")
            return $paypal->DoExpressCheckoutPayment($paymentInfo);
        elseif($function=="Currencyconvert")
        {
            $explode=explode(",",$paymentInfo);
            return $paypal->currency_convert($explode[0],$explode[1],$explode[2]);
        }
        else if($function=="withdraw")
        {
           
            $explode=explode(",",$paymentInfo);   
          
           return $paypal->withdraw($explode[0],$explode[1],$explode[2]); 
        }            
        else
            return "Function Does Not Exist!";
    }
}