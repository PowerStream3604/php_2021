<?php

	include_once('./_common.php');

function Curl($string){
	$ch=curl_init();
	// user credencial
	curl_setopt($ch, CURLOPT_USERPWD, "");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $string);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_VERBOSE, true);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);

	//$api
	curl_close($ch);
	return $response;
}

function GetSmall($string){
	$url = "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=".$string;
	$responseS = Curl($url);
	//내용 가공하는 부분
	$apiresponseS = json_decode($responseS, TRUE);
	
	$resultS = $apiresponseS['msg']['servicedata']['ARRAY수3'];
	$numS = count($resultS);
	$listS = array();
	for($g = 0; $g < $numS; $g++)
	{
		$dong = $apiresponseS['msg']['servicedata']['ARRAY수3'][$g]['소지역명'];
		$code = $apiresponseS['msg']['servicedata']['ARRAY수3'][$g]['법정동코드'];
		$listS[$code] = $dong;
	}
	return $listS;
}
//



// 법정동주소로 물건식별자 가지고 오는 코드
function SearchApartment($apartName, $code){
	for($i = 0; $i<20; $i++){
		$url = "https://onland.kbstar.com/quics?page=C059652&QAction=835627&RType=json&%EA%B2%80%EC%83%89%ED%8E%98%EC%9D%B4%EC%A7%80%EB%B2%88%ED%98%B8=".$i."&keyword=".$apartName;
		$responseD = Curl($url);
		var_dump($responseD);
		// 내용 가공하는 부분
		$apiresponseA = json_decode($responseD, TRUE);
		$resultA = $apiresponseA['msg']['servicedata']['단지검색목록'];
		$numA = count($resultA);
		$apartList = array();
		for($j = 0; $j < $numA; $j++){
			 
			//법정동코드가 같은지 확인하는 부분
			$apartCode = substr($apiresponseA['msg']['servicedata']['단지검색목록'][$i]['법정동코드'], 0, -2);
			if($apartCode == $code){
				$apartIdentifier = $apiresponseA['msg']['servicedata']['단지검색목록'][$i]['물건식별자'];
				$apartList[$apartIdentifier] = $apartName;
				return $apartList;
			}
		}
	}
}

// 
function GetPrice($apartKey){
	$url = "https://onland.kbstar.com/quics?page=C059689&%EB%AC%BC%EA%B1%B4%EC%8B%9D%EB%B3%84%EC%9E%90=".$apartKey."&%ED%83%AD%EA%B5%AC%EB%B6%84=1&QSL=F#CP";
	$url = "https://onland.kbstar.com/quics?chgCompId=b062071&baseCompId=ccChangeArea&page=C059689&cc=ccChangeArea:b062071&%ED%83%AD%EA%B5%AC%EB%B6%84=2&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=1168010300&%EB%AC%BC%EA%B1%B4%EC%8B%9D%EB%B3%84%EC%9E%90=KBM014126&%EC%8B%9C%EC%84%B8%EB%AC%BC%EA%B1%B4%EC%8B%9D%EB%B3%84%EC%9E%90=KBA011487&%EB%A7%A4%EB%AC%BC%EC%A2%85%EB%B3%84%EA%B5%AC%EB%B6%84=01&%EB%A9%B4%EC%A0%81%EC%9D%BC%EB%A0%A8%EB%B2%88%ED%98%B8=1&%EC%A7%80%EC%97%AD%EB%AA%85=%EA%B0%9C%ED%8F%AC%EB%8F%99&%EB%A9%94%EB%89%B4%ED%83%80%EC%9E%85=M";
	//$url = "https://onland.kbstar.com/quics?page=C059689&%EB%AC%BC%EA%B1%B4%EC%8B%9D%EB%B3%84%EC%9E%90=KBM014171&%ED%83%AD%EA%B5%AC%EB%B6%84=1&%EB%A7%A4%EB%AC%BC%EA%B1%B0%EB%9E%98%EA%B5%AC%EB%B6%84=1&QSL=F#CP";
	$responseP = Curl($url);
	$apiresponse = json_decode($responseP, TRUE);
	return $apiresponse;
}
//해당 사용자 아이디, 관리번호,동, 단지 이름, 곱급면적, 전용 면적들을 DB에서 가지고 오는 부분
$sql = "SELECT cId, manageNumber, dong, apartment, supplyArea, netArea FROM g5_consult;";
$resultSql = sql_query($sql);
$resultData = array();
for($k = 0; $row=sql_fetch_array($resultSql);$i++){
	$resultData[$k] = $row;
	$resultData[$k]['cId'] = get_text($row['cId']);
	$resultData[$k]['manageNumber'] = get_text($row['manageNumber']);
	$resultData[$k]['dong'] = get_text($row['dong']);
	$resultData[$k]['apartment'] = get_text($row['apartment']);
	$resultData[$k]['supplyArea'] = get_text($row['supplyArea']);
	$resultData[$k]['netArea'] = get_text($row['netArea']);
}


$currentTime = date("Y-m-d H:i:s");
//테스트용 예시
$apartmentName = "원당자이";
$apartCode = "28260115";
$apartListNameCode = SearchApartment($apartmentName, $apartCode);
print_r($apartListNameCode);
$apartKey = array_search($apartmentName,$apartListNameCode);
$apartP = GetPrice($apartKey);
var_dump($apartP);
//echo $apartListNameCode[(array_keys($apartListNameCode,$apartmentName))]."wow";


// 여기 부분에서 g5_apartment_price에 값을 넣으면 된다
$sql = "INSERT INTO g5_apartment_price SET saleHigh='{}', saleAverage='{}', saleLow='{$apartmentName}', leaseHign='{}', leaseAverage='{}', leaseLow='{}', MonthRentDeposit='{}', MonthRent='{}', createdAt='{$currentTime}', updatedAt='{$currentTime}';";

// 입력하는 부분
sql_query($sql);


//$thisasdf = GetSmall();

//print_r($thisasdf);


/*
//대지역
$url = 'https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json';
$response = Curl($url);

//내용 가공 하는 부분
$apiresponse = json_decode($response,TRUE);
//var_dump($apiresponse);

//echo "<br>";
//echo "<br>";
//echo "<br>";

$result = $apiresponse['msg']['servicedata']['ARRAY수1'];
$gh = 0;
//echo $apiresponse['msg']['servicedata']['ARRAY수1'][$gh]['대지역명'];
//print_r($result);
$num = count($result);
//echo $apiresponse['msg']['servicedata']['ARRAY수1'][0]['대지역명']."weg";
$list = array();
for($i = 0; $i < $num; $i++){
	$name = $apiresponse['msg']['servicedata']['ARRAY수1'][$i]['대지역명'];
	$code = $apiresponse['msg']['servicedata']['ARRAY수1'][$i]['법정동코드'];

	$list[$code] = $name;
	//echo "법정동코드: ".$apiresponse['msg']['servicedata']['ARRAY수1'][$i]['법정동코드'];
	echo "<br>";
}


// 대지역과 법정동 코드가 있는 배열
//print_r($list);

//서울
$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=11";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
///echo "<br>---------------------<br>";
//var_dump($apiCity);

///echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$seoulCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	$citySmall = GetSmall($codeCity);
	//echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$seoulCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
//print_r($seoulCity);
print("<pre>seoulCity".print_r($seoulCity,true)."</pre>");

// 서울 중지역명
//print_r($seoulCity);
//print_r(GetSmall(array_search($seoulCity[11305])));

$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=41";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$GeongGiDoCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
		//echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$GeongGiDoCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>GeongGiDoCity".print_r($GeongGiDoCity,true)."</pre>");
//print_r($GeongGiDoCity);



$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=28";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$IncheonCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$IncheonCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>IncheonCity".print_r($IncheonCity,true)."</pre>");
//print_r($IncheonCity);




$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=26";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$BusanCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$BusanCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>BusanCity".print_r($BusanCity,true)."</pre>");
//print_r($BusanCity);





$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=30";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$DaejeonCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$DaejeonCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>DaejeonCity".print_r($DaejeonCity,true)."</pre>");
//print_r($DaejeonCity);






$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=27";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$DaeguCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$DaeguCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>DaeguCity".print_r($DaeguCity,true)."</pre>");
//print_r($DaeguCity);





$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=29";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$GwangjuCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$GwangjuCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>GwangjuCity".print_r($GwangjuCity,true)."</pre>");
//print_r($GwangjuCity);






$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=42";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$GangWonDoCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$GangWonDoCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>GangWonDoCity".print_r($GangWonDoCity,true)."</pre>");
//print_r($GangWonDoCity);





$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=31";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$UlsanCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$UlsanCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>UlsanCity".print_r($UlsanCity,true)."</pre>");
//print_r($UlsanCity);





$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=44";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$chungNamCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$chungNamCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>GeongGiDoCity".print_r($chungNamCity,true)."</pre>");
//print_r($chungNamCity);




$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=43";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$chungBukCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$chungBukCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>chungBukCity".print_r($chungBukCity,true)."</pre>");
//print_r($chungBukCity);




$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=48";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$gyeongNamCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$gyeongNamCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>gyeongNamCity".print_r($gyeongNamCity,true)."</pre>");
//print_r($gyeongNamCity);





$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=47";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$gyeongBukCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$gyeongBukCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>gyeongBukCity".print_r($gyeongBukCity,true)."</pre>");
//print_r($gyeongBukCity);





$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=46";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$jeonManCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$jeonManCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>jeonManCity".print_r($jeonManCity,true)."</pre>");
//print_r($jeonManCity);





$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=45";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$jeonBukCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$jeonBukCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>jeonBukCity".print_r($jeonBukCity,true)."</pre>");
//print_r($jeonBukCity);




$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=50";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$jeJuCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$jeJuCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>jeJuCity".print_r($jeJuCity,true)."</pre>");
//print_r($jeJuCity);




$cityUrl= "https://onland.kbstar.com/quics?page=okbland&QAction=827950&RType=json&%EB%B2%95%EC%A0%95%EB%8F%99%EC%BD%94%EB%93%9C=36";

$responseCity = Curl($cityUrl);
$apiCity = json_decode($responseCity, TRUE);
echo "<br>---------------------<br>";
//var_dump($apiCity);

//echo "<br><br><br>---------------------<br>";
$cityResult = $apiCity['msg']['servicedata']['ARRAY수2'];

$cityNum = count($cityResult);
//echo $cityNum."GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG";
$seJongCity = array();
for($i = 0; $i < $cityNum; $i++){
	$nameCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['중지역명'];
	$codeCity = $apiCity['msg']['servicedata']['ARRAY수2'][$i]['법정동코드'];
	//	echo "<br>------------ 서울  '{$nameCity}'  (동) -------------<br>";
	//print_r($citySmall);
	$citySmall = GetSmall($codeCity);
	$seJongCity[$codeCity][$nameCity]=$citySmall;
	echo "<br>";
}
print("<pre>seJongCity".print_r($seJongCity,true)."</pre>");
//print_r($seJongCity);
*/
?>
