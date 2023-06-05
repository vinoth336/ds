<?php



function pushnotification($message){
    $app_api = 'AIzaSyBolzOUPWBcMTyF1fSjzKPHwnn4Te0lUIo';
    $app_name = 'Customer App';
    $mobile_token = 'cIocs6EeTyuV0WRda3w1mF:APA91bHNTpLHrt5ugh3a0htcw92GvBLvUUVewqmfBDLqW9wphNBMIlk_Bm0r40RIgErwDDeZkX9rzsfNBk_MejElkihjlZm-yhYSFTOEnqXNTurFjcjTYS3PZproIvzwgpBHVifbSZp2';
    $registrationIds = [$mobile_token];

    // prep the bundle
    $msg = array
    (
        'message' 	=> 'dstar',
        'title'		=> 'Message from'.$app_name,
        'vibrate'	=> 1,
        'sound'		=> 1,
        'largeIcon'	=> 'large_icon',
        'smallIcon'	=> 'small_icon',
        'body' 		=> $message,
    );

    $fields = array
    (
        'registration_ids' 	=> $registrationIds,
        'data'			=> $msg
    );

    $headers = array
    (
        'Authorization: key=' . $app_api,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );

    //Decoding json from result
    $res = json_decode($result);


    if ($res === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    //Getting value from success
    $flag = $res->success;


    echo print_r($flag, true);
}
