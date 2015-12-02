<?php
// Setup
// ULR's to get from
$baseexporturl = 'http://misp-3.local/events/';
$api_export_index = 'restSearch/download/';

// URL's to send to
$baseimporturl = 'http://misp-23.local/events/';
$api_import_index = '';

// seperate tags with &&
// $eventtags = 'nicp';

/*----------------------------------------------------------------------------
Get Index of events needed
----------------------------------------------------------------------------*/
// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, $baseexporturl . $api_export_index);

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

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
  print "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  print "\nResponse HTTP Body : " . $resp;
}
*/

$result_obj_export_index = json_decode($resp);        // json object
$result_arr_export_index = json_decode($resp,true);   // json array

//var_dump($result_arr);

// Log all new event
$logfile = fopen("newevents.log", "w") or die("Unable to open file!");


print 'Start export index <br>';
foreach($result_arr_export_index['response'] as $row => $innerArray) {
    print 'Event Export ID: ' . $innerArray['Event']['id'] . ' Event UUID: ' . $innerArray['Event']['uuid'] . PHP_EOL . '<br>';
    $date = date(' Y-m-d H:i:s ');
  $txt = 'Event Export ID: ' . $innerArray['Event']['id'] .'Event UUID: ' . $innerArray['Event']['uuid'] . $date. PHP_EOL;
  fwrite($logfile, $txt);
}
print 'End export index <br>';
fclose($logfile);


// Close request to clear up some resources
curl_close($ch);

// print events to page
foreach($result_arr_export_index['response'] as $row => $innerArray) {
  $eventid = $innerArray['Event']['id'];
    $body = '{"Event":' . json_encode($innerArray) . '}';
 // print '<br>Event ID ' . $eventid . '</br>';
//  print $body;
}

// Get event index from baseimporturl
//----------------------------------------------------------------------------*/

// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, $baseimporturl . $api_import_index);

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: CG6QZWgiUmHht8xtQBJGemqJk4n3t806P7WP4d7Z",
  "Accept: application/json",
 ]
);

// Send the request & save response to $resp
$resp = curl_exec($ch);

/*
if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  print "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  print "\nResponse HTTP Body : " . $resp;
}
*/

$result_obj_import_index = json_decode($resp);        // json object
$result_arr_import_index = json_decode($resp,true);   // json array

// Close request to clear up some resources
curl_close($ch);

// print events to page
print '<br>Start Event import Index</br>';
foreach($result_arr_import_index as $row => $innerArray) {
  $eventimportid = $innerArray['id'];
  $eventuuid = $innerArray['uuid'];
  $body = '{"Event":' . json_encode($innerArray) . '}';
  print 'Event Import ID: ' . $eventimportid . ' Event UUID: ' . $eventuuid . '</br>';
 // print $body;
}
print 'End Event import Index</br>';

/*----------------------------------------------------------------------------
Get whole actual events
----------------------------------------------------------------------------*/
foreach($result_arr_export_index['response'] as $row => $innerArray) {
  $eventid = $innerArray['Event']['id'];
  $eventuuid = $innerArray['Event']['uuid'];
  print '<br>';
  print '<br><h3>Start Event import</h3></br>';
  print $row;
  print 'Whole event ID:' . $eventid . ' Event UUID: ' . $eventuuid . '<br>';

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
/*
if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  print "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  print "\nResponse HTTP Body : " . $resp;
}
*/

$result_obj_get = json_decode($resp);        // json object
$result_arr_get = json_decode($resp,true);  // json array

// Change and unset 2.4 values 
// Unset values
unset($result_arr_get['Event']['orgc_id']);
unset($result_arr_get['Event']['org_id']);
unset($result_arr_get['Event']['SharingGroup']);
unset($result_arr_get['Event']['sharing_group_id']);

$result_arr_get['Event']['Orgc']= 'ADMIN';
$result_arr_get['Event']['Org'] = 'ADMIN';
$result_arr_get['Event']['distribution'] = '1';

foreach($result_arr_get['Event']['Attribute'] as $row => &$innerArray) {
unset ($innerArray['sharing_group_id']);
unset ($innerArray['SharingGroup']);
$innerArray['distribution'] = '1';
}

print'<pre>';
print'Get Event <br>';
print_r($result_arr_get);

// Create body
$body = json_encode($result_arr_get);

$result_string = json_encode($resp,true);  // json string

// Write event json to file for records
foreach($result_obj_get as $object) {
  $logfile = fopen("eventID" . $object->uuid , "w") or die("Unable to open file!");
  fwrite($logfile, $result_string);
  fclose($logfile);
}

// print($body);
// Close request to clear up some resources
curl_close($ch);

  $uuid = $innerArray['uuid'];

/*----------------------------------------------------------------------------
POST whole events inside foreach if new event
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

$result_arr_get = json_decode($resp,true);

//print'<pre>';
//print_r($result_arr);

// Create body
$body = json_encode($result_arr_get);

// Set body
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Send the request & save response to $resp
$resp = curl_exec($ch);

if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  print "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  print "\nResponse HTTP Body : " . $resp;
}

// Close request to clear up some resources
curl_close($ch);

$message = json_decode($resp);
if ($message->name == 'Event already exists, if you would like to edit it, use the url in the location header.') {

/*----------------------------------------------------------------------------
PUT whole events inside foreach if exists
----------------------------------------------------------------------------*/
// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, $baseimporturl . $eventimportid);

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

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

print'<pre>';
print 'PUT Event as: ' . $eventimportid . '<br>';

$body = json_encode($result_arr_get);

// Set body
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Send the request & save response to $resp
$resp = curl_exec($ch);

if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  print "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  print "\nResponse HTTP Body : " . $resp;
}

// Close request to clear up some resources
curl_close($ch);
  }
}

?>