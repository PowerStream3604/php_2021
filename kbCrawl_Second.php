<?php

	include_once('./_common.php');
/*
	// json �����͸� �޾ƿ� url
	$url = "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json";
	//$url = "https://onland.kbstar.com/quics?page=C059652&QAction=835627&RType=json&�˻���������ȣ=2&keyword=��Ʈ�ν�Ƽ";
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
//apiresponse variable �� �迭�̴�

//������ json���� �ƴ��� Ȯ���ϴ� �� 
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

$apiresponse = json_decode($response,TRUE);
var_dump($apiresponse);

echo "<br>";
echo "<br>";
echo "<br>";
/*
$num = 0;
$arrayJ = $apiresponse['msg']['servicedata']['ARRAY��1'];

foreach($arrayJ as $key => $value)
{
	forech($value as $keys => $values)
	{
		echo $key."=> ".$keys." : ".$values."<br>";
	}
}
*/
echo $arrayJ;
/*
foreach($arrayJ as $jso)
{
	$num++;
	echo "<br><b># $num</b><br>";
	foreach ($jso as $key=>$value)
	{
		echo "$key: $value <br>";

	}
}
print("<pre>".print_r($apiresponse,true)."</pre>");

*/



//print_r($apiresponse['msg']['servicedata']);
//echo "<br>";
//echo count($apiresponse['msg']['servicedata']);
//echo "<br>";
//print_r($apiresponse['msg']['servicedata'][0]['ARRAY��1']);

$result = $apiresponse['msg']['servicedata']['ARRAY수1'][0]['법정동코드'];

//print_r($result);

//echo "<br>";
//echo count($result);

/*
foreach($apiresponse['msg']['servicedata']['ARRAY��1'] as $prResult => $value){
	echo "11111111111111111111111111111111111";
	echo $value['��������'];
}
$name = $result['��������'];
*/
$allow = array("김용준123"=>array("이이56"=>array("김용준")));
echo $allow['김용준123']['이이56'][0];
//print_r($allow);
?>
