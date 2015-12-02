<?php
// Setup
$baseexporturl = 'http://misp-3.local/events';
$baseimporturl = 'http://misp-23.local/events';

/*----------------------------------------------------------------------------
Get Index of events needed
----------------------------------------------------------------------------*/
// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, $baseexporturl);

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


if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo "\nResponse HTTP Body : " . $resp;
}


$result_obj = json_decode($resp);		     // json object
$result_arr = json_decode($resp,true); 	 // json array

//var_dump($result_arr);

// Log all new event
$logfile = fopen("newevents.log", "w") or die("Unable to open file!");
foreach($result_obj as $object) {
    echo 'Event UUID: ' . $object->uuid . PHP_EOL . '<br>';
    $date = date(' Y-m-d H:i:s ');
	$txt = 'Event UUID: ' . $object->uuid . $date. PHP_EOL;
	fwrite($logfile, $txt);
}
fclose($logfile);

// Close request to clear up some resources
curl_close($ch);


// echo events to page
foreach($result_arr as $row => $innerArray) {
	$eventid = $innerArray['id'];
    $body = '{"Event":' . json_encode($innerArray) . '}';
	echo '<br>Event ID ' . $eventid . '</br>';
	echo $body;
}

foreach($result_arr as $row => $innerArray) {
$eventid = $innerArray['id'];

/*----------------------------------------------------------------------------
Get whole actual events
----------------------------------------------------------------------------*/
// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, $baseexporturl . $eventid);

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Cookie: CAKEPHP=h18fp5tsdi50pu77gl4rq78980",
  "Authorization: He7dzfvu3GEAEYDmdE3E4NKgk8IJZGtGVATvQpZT",
  "Content-Type: application/json",
  "Accept: application/json",
 ]
);

// Send the request & save response to $resp
$resp = curl_exec($ch);

if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo "\nResponse HTTP Body : " . $resp;
}

$result_arr = json_decode($resp,true); 	// json array

// Write event json to file for records
foreach($result_obj as $object) {
	$logfile = fopen("eventID" . $object->uuid , "w") or die("Unable to open file!");
	$eventjson = $result_obj;
	fwrite($logfile, $eventjson);
	fclose($logfile);
}


//print($body);
// Close request to clear up some resources
curl_close($ch);

/*----------------------------------------------------------------------------
Check if event already exists
Get the index of this instance and check UUID's if UUID exists 
get its event ID on this instance and PUT event instead of POST
----------------------------------------------------------------------------*/


/*----------------------------------------------------------------------------
POST whole events inside foreach
----------------------------------------------------------------------------*/
// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, $baseimporturl);

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

// Change 2.3 values
$result_arr_23 = $result_arr; //change sharing group value to 0 

$result_arr_23[0]['orgc'];
// or if you want to change all entries with orgc "1"
foreach ($result_arr_23 as $key => $entry) {
    if ($entry['orgc'] == '5') {
        $result_arr_23[$key]['orgc'] = "1";
    }
}

// Create body
$body = json_encode($result_arr_23);
 

// Set body
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Send the request & save response to $resp
$resp = curl_exec($ch);

if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo "\nResponse HTTP Body : " . $resp;
}

// Close request to clear up some resources
curl_close($ch);
}



