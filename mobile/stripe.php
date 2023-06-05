<?php

// include Stripe
require 'stripe/Stripe.php';
error_reporting(1);
$params = array(
		"testmode"   => "on",
		"private_live_key" => "sk_live_1FPNOipYoQ8mt0YZMP04PK8H",
		"public_live_key"  => "pk_live_OuRHqipEozZmv3BZ3WbfdmKi",
		"private_test_key" => "sk_test_3MkjuC185R3zykvsSzNj1jfe",
		"public_test_key"  => "pk_test_6ZUJ8UViO2N7A1jmvyJScwhK"
);

if ($params['testmode'] == "on") {
	Stripe::setApiKey($params['private_test_key']);
	$pubkey = $params['public_test_key'];
} else {
	Stripe::setApiKey($params['private_live_key']);
	$pubkey = $params['public_live_key'];
}
if(isset($_POST['stripeToken']))
{
	$amount_cents = str_replace(".","",$_POST['amount']);  // Chargeble amount
	$invoiceid = "14526321";                      // Invoice ID
	$description = "Invoice #" . $invoiceid . " - " . $invoiceid;

	try {

		$charge = Stripe_Charge::create(array(
						"amount" => $amount_cents,
						"currency" => $_POST['currency'],
						"source" => $_POST['stripeToken'],
						"description" => $description)
		);

		if ($charge->card->address_zip_check == "fail") {
			throw new Exception("zip_check_invalid");
		} else if ($charge->card->address_line1_check == "fail") {
			throw new Exception("address_check_invalid");
		} else if ($charge->card->cvc_check == "fail") {
			throw new Exception("cvc_check_invalid");
		}
		// Payment has succeeded, no exceptions were thrown or otherwise caught

		$result = "success";

	} catch(Stripe_CardError $e) {

		$error = $e->getMessage();
		$result = "declined";

	} catch (Stripe_InvalidRequestError $e) {
		$result = "declined";
	} catch (Stripe_AuthenticationError $e) {
		$result = "declined";
	} catch (Stripe_ApiConnectionError $e) {
		$result = "declined";
	} catch (Stripe_Error $e) {
		$result = "declined";
	} catch (Exception $e) {

		if ($e->getMessage() == "zip_check_invalid") {
			$result = "declined";
		} else if ($e->getMessage() == "address_check_invalid") {
			$result = "declined";
		} else if ($e->getMessage() == "cvc_check_invalid") {
			$result = "declined";
		} else {
			$result = "declined";
		}
	}

	if($result=="success") {
		//$response = "<div class='col-sm-offset-3 col-sm-9 text-success'>Your Payment has been processed successfully.</div>";
$data['id']='1';
		$data['status']="Success";
	} else{
		//$response = "<div class='text-danger'>Stripe Payment Status : \".$result.</div>";
	$data['id']='2';
		$data['status']="Error";
	}
$res=$data;
echo json_encode($res);
}

?>