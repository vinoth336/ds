<?php
namespace App\Library;

$mode='Sandbox';
define('API_USERNAME', 'jambulingam-business-us_api1.gmail.com');
define('API_PASSWORD','QGCPHB2MLDDATVAH');
define('API_SIGNATURE','AFcWxV21C7fd0v3bYYYRCpSSRl31AEzqxoS4bCIDQKjSmXKmSigBRowJ');
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
				/*$sandbox = FALSE;
				
				if ($sandbox == TRUE)
				{
				define('API_USERNAME', 'pss.suresh2_business_api1.gmail.com');
				define('API_PASSWORD', '9PJL8U9R7GCZJUTD');
				define('API_SIGNATURE','AEDQ5ym3hY6udZhSrG57SrKlq2xIAG1lUoQKoByI-m6SndxvLIt0WtlT');
				define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');				
				define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');
				}
				else
				{
				define('API_USERNAME', 'payment_api1.spiderwerkz.com');
				define('API_PASSWORD', 'EEJ2QVQXXELP93B9');
				define('API_SIGNATURE', 'AmOhxtbf110LBwo10.mFhlW4x9gWAWQ3eVwULYvFF9ckIGctrL0rzGVr');
				define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
				define('PAYPAL_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
				}
				
				define('USE_PROXY',FALSE);
				define('PROXY_HOST', '127.0.0.1');
				define('PROXY_PORT', '808');
				define('VERSION', '72.0');*/
				?>