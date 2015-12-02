<?php
$baseurl = 'http://misp-3.local/events/restSearch/download/';
// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, $baseurl);

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

echo('BaseURL: ' . $baseurl . '<br>');


if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
  echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo "\nResponse HTTP Body : " . $resp;
}

$result_arr = json_decode($resp, true);

echo'<pre>';
//print_r($result_arr);
/*
// Close request to clear up some resources
foreach($result_arr as $row => $innerArray) {
    $body = '{"Event":' . json_encode($innerArray) . '}';
	echo '<br>Event ID' . $innerArray['id'] . '</br>';
	echo $body;
}

print '<br>';
Print 'new json array';

unset($result_arr['Event']['orgc_id']);
unset($result_arr['Event']['org_id']);
unset($result_arr['Event']['SharingGroup']);
unset($result_arr['Event']['sharing_group_id']);

$result_arr['Event']['Orgc']= 'ADMIN';
$result_arr['Event']['Org'] = 'ADMIN';
$result_arr['Event']['distribution'] = '1';


foreach($result_arr['Event']['Attribute'] as $row => &$innerArray) {
unset ($innerArray['sharing_group_id']);
unset ($innerArray['SharingGroup']);
$innerArray['distribution'] = '1';
print_r($innerArray);

}
print_r($result_arr);
*/
/*
$search = 'SharingGroup';
// this way change your original array
foreach ($result_arr['Event']['Attribute'] as &$sub_array) {
    if(($key = array_search($search, $sub_array)) !== false) {
        unset($sub_array['SharingGroup']);
    }
}
*/
print_r($result_arr);



curl_close($ch);