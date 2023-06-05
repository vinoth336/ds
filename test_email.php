<?PHP
/*$sender = 'someone@somedomain.tld';
$recipient = 'you@yourdomain.tld';

$subject = "php mail test";
$message = "php test message";
$headers = 'From:' . $sender;

if (mail($recipient, $subject, $message, $headers))
{
    echo "Message accepted";
}
else
{
    echo "Error: Message not accepted";
}*/
?>
<?php 
	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );
	$from = "admin@deliverystar.in";
	$to = "raja@bicsglobal.com";
	$subject = "Delivery Star Test Mail";
	$message = "Delivery Star Test Mail";
	$headers = "From:" . $from;
	if(mail($to,$subject,$message, $headers)){
		echo "Test email sent";
	} else {
		//$errorMessage = error_get_last()['message'];
		print_r(error_get_last());
		echo "Test email failure";
	}
	
?>
