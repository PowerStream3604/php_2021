<?php

	include_once('./_common.php');
/*
	// json 데이터를 받아올 url
	$url = "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json";
	//$url = "https://onland.kbstar.com/quics?page=C059652&QAction=835627&RType=json&검색페이지번호=2&keyword=메트로시티";
	//call api
	$json = file_get_contents($url);
	$json = json_decode($json);
	echo $json;
	//echo "hello";
*/
/*
	//API URL
	$url  = "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json";
	// Create a new cURL resource
	$ch = curl_init($url);
	// Will return the response, if false it prints the response
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($handle, CURLOPT_URL,$YourUrl);
	// Execute the session and store the contents in $result
	$result=curl_exec($handle);
	// Closing the session
	curl_close($handle);
	
	var_dump($result);
*/

	$url = 'https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json';


$ch=curl_init();
// user credencial
curl_setopt($ch, CURLOPT_USERPWD, "username:passwd");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
//apiresponse variable 은 배열이다

//파일이 json인지 아닌지 확인하는 것 
function isJson($string)
{
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

function json_validate($string)
{
    // decode the JSON data
    $result = json_decode($string);

    // switch and check possible JSON errors
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $error = ''; // JSON is valid // No error has occurred
            break;
        case JSON_ERROR_DEPTH:
            $error = 'The maximum stack depth has been exceeded.';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            $error = 'Invalid or malformed JSON.';
            break;
        case JSON_ERROR_CTRL_CHAR:
            $error = 'Control character error, possibly incorrectly encoded.';
            break;
        case JSON_ERROR_SYNTAX:
            $error = 'Syntax error, malformed JSON.';
            break;
        // PHP >= 5.3.3
        case JSON_ERROR_UTF8:
            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_RECURSION:
            $error = 'One or more recursive references in the value to be encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_INF_OR_NAN:
            $error = 'One or more NAN or INF values in the value to be encoded.';
            break;
        case JSON_ERROR_UNSUPPORTED_TYPE:
            $error = 'A value of a type that cannot be encoded was given.';
            break;
        default:
            $error = 'Unknown JSON error occured.';
            break;
    }

    if ($error !== '') {
        // throw the Exception or exit // or whatever :)
        exit($error);
    }

    // everything is OK
    return $result;
}

//$api
curl_close($ch);

$apiresponse = json_decode($response);
if($apiresponse == FALSE){
	// JSON is invalid
	echo "json is invalid";
}
//var_dump($apiresponse);

echo "-----------------------";
$output = json_validate($response);
echo $output;
echo "-----------------------";


echo "<br>";
echo "<br>";
echo "<br>";

print_r($apiresponse['msg']['servicedata']);
echo "<br>";
echo count($apiresponse['msg']['servicedata']);
echo "<br>";
print_r($apiresponse['msg']['servicedata'][12]['ARRAY수1']);

$result = $apiresponse['msg']['servicedata']['ARRAY수1'];
echo "<br>";
echo count($result);


foreach($apiresponse['msg']['servicedata']['ARRAY수1'] as $prResult => $value){
	echo "11111111111111111111111111111111111";
	echo $value['대지역명'];
}
$name = $result['대지역명'];
//echo $result;
/*
foreach($apiresponse['msg']['servicedata']['ARRAY수1'] as $dCode){
	echo "kkkkk";
	echo $dCode[0]['대지역명'], '<br>';
}
*/
print("<pre>".print_r($apiresponse,true)."</pre>");
//$rr = array("김용준"=>"hello");
//echo $rr['김용준'];
?>
