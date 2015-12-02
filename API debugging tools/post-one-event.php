<?php

// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, 'http://misp.local/events/');

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: He7dzfvu3GEAEYDmdE3E4NKgk8IJZGtGVATvQpZT",
  "Accept: application/json",
 ]
);

// Send the request & save response to $resp
$resp = curl_exec($ch);

/*
if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo "\nResponse HTTP Body : " . $resp;
}
*/

$result_obj = json_decode($resp);		// json object
$result_arr = json_decode($resp,true); 	// json array

//var_dump($result_arr);
/*
// Log all new event
$logfile = fopen("newevents.log", "w") or die("Unable to open file!");
foreach($result_obj as $object) {
    echo 'Event UUID: ' . $object->uuid . PHP_EOL . '<br>';
    $date = date(' Y-m-d H:i:s ');
$txt = 'Event UUID: ' . $object->uuid . $date. PHP_EOL;
fwrite($logfile, $txt);
}
fclose($logfile);*/

// Close request to clear up some resources
curl_close($ch);

// store all event payloads

// POST all the new events

// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, 'http://misp.local/events');

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Cookie: CAKEPHP=mnl196etdra77k0cf72prucdk7",
  "Authorization: CG6QZWgiUmHht8xtQBJGemqJk4n3t806P7WP4d7Z",
  "Content-Type: application/json",
  "Accept: application/json",
 ]
);
// Create body


$body = json_encode($result_arr);

// Set body
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Send the request & save response to $resp
$resp = curl_exec($ch);
/*
if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
 // echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo "\nResponse HTTP Body : " . $resp;
}*/
$message = json_decode($resp);
if ($message->name == 'Event already exists, if you would like to edit it, use the url in the location header.') {
print'<h1>This event exists</h1>';
}

//print_r($message->name);

// Close request to clear up some resources
curl_close($ch);

?>