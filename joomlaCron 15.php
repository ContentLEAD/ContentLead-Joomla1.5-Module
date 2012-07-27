<?php
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$ch1 = curl_init();
$ch2 = curl_init();

// set URL and other appropriate options
curl_setopt($ch1, CURLOPT_URL, "http://localhost/joomla/1.5/index.php?option=com_brafton2&task=loadArticles");
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch2, CURLOPT_URL, "http://localhost/joomla/1.5/index.php?option=com_brafton2&task=loadPictures");
curl_setopt($ch2, CURLOPT_HEADER, 0);

//create the multiple cURL handle
$mh = curl_multi_init();

//add the two handles
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);

$active = null;
//execute the handles
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}

//close the handles
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_close($mh);