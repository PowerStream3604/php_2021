<?php

	// ������ ���� �迭
	$listArray = [];

	$encode = array('ASCII','UTF-8','EUC-KR');
//	echo '/hsk1209/www/data/file/'.'filesise.csv';

	$h = fopen('/hsk1209/www/data/file/'.'filesise.csv','r');
	$listArray = fgetcsv($h);
//	print_r($listArray[0]);
	//print_r(mb_detect_encoding($listArray[0],$encode));
	$i=0;
	$theArray = [];
	$lineCount = 0;
	while(($data = fgetcsv($h,1000,',')) !== FALSE){
		$theArray[] = $data;
		//echo iconv("EUC-KR","UTF-8",$listArray[$i]);
		//$i++;
		$lineCount++;
	}
	for ($i = 0; $i<$lineCount; $i++){
		$sqlQuery = "INSERT INTO g5_consult SET";
		for($j = 0; $j<10; $j++){
			if($j==0){
				$sqlQuery = $sqlQuery." "."manageNumber=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==1){
				$sqlQuery = $sqlQuery." "."Name=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==2){
				$sqlQuery = $sqlQuery." "."pNum=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==3){
				$sqlQuery = $sqlQuery." "."state=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==4){
				$sqlQuery = $sqlQuery." "."city=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==5){
				$sqlQuery = $sqlQuery." "."dong=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==6){
				$sqlQuery = $sqlQuery." "."apartment=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==8){
				$sqlQuery = $sqlQuery." "."supplyArea=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==9){
				$sqlQuery = $sqlQuery." "."netArea=".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'"';
			}
		}
		echo $sqlQuery.";";
		echo "<br>";
	}
	//echo iconv("EUC-KR","UTF-8",$theArray[0][9]);
	//echo $lineCount;
	//var_dump($theArray[0][0]);
	//echo iconv("EUC-KR","UTF-8",$listArray[i]);
//	echo $listArray[0];
	fclose($h);
	
		/*
		//Each line in the file is converted into an individual array that we call $listArray
		while(($data = fgetcsv($h, 1000, ',')) !== FALSE)
		{
			$listArray[] = $data;
			
		}
		//close the file
		fclose($h);
		
		0echo "<pre>";
		var_dump($listArray);
		echo "</pre>";
		*/
		/*
	$filename = '/hsk1209/www/data/file/'.'filesise.csv';
	// The nested array to hold all the arrays
	$dataArray = [];
	//Open the filefor reading
	if(($h = fopen('{$filename}','r')) !== false)
	{
		$i=0;
		// Each line in the file is converted into an individual array that we call $data
		while(($data = fgetcsv($h, 1000, ",")) !== FALSE)
		{
			//Each individual array is being pushed into the nested array
			$dataArray[$i] = $data;
			$i++;
		}
		// close the file
		fclose($h);

	}
	var_dump(iconv("EUC-KR","UTF-8",$dataArray));
*/

?>

<?php

	// ������ ���� �迭
	$listArray = [];

	$encode = array('ASCII','UTF-8','EUC-KR');
//	echo '/hsk1209/www/data/file/'.'filesise.csv';

	$h = fopen('/hsk1209/www/data/file/'.'filesise.csv','r');
	$listArray = fgetcsv($h);
//	print_r($listArray[0]);
	//print_r(mb_detect_encoding($listArray[0],$encode));
	$i=0;
	$theArray = [];
	$lineCount = 0;
	while(($data = fgetcsv($h,1000,',')) !== FALSE){
		$theArray[] = $data;
		//echo iconv("EUC-KR","UTF-8",$listArray[$i]);
		//$i++;
		$lineCount++;
	}
	for ($i = 0; $i<$lineCount; $i++){
		$sqlQuery = "INSERT INTO g5_consult VALUES ";
		for($j = 0; $j<10; $j++){
			if($j==0){
				$sqlQuery = $sqlQuery." "."(".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==1){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==2){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==3){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==4){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==5){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==6){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==8){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'",';
			}
			if($j==9){
				$sqlQuery = $sqlQuery." "."".'"'.iconv("EUC-KR","UTF-8",$theArray[$i][$j]).'")';
			}
		}
		echo $sqlQuery.";";
		echo "<br>";
	}
	//echo iconv("EUC-KR","UTF-8",$theArray[0][9]);
	//echo $lineCount;
	//var_dump($theArray[0][0]);
	//echo iconv("EUC-KR","UTF-8",$listArray[i]);
//	echo $listArray[0];
	fclose($h);
	
		/*
		//Each line in the file is converted into an individual array that we call $listArray
		while(($data = fgetcsv($h, 1000, ',')) !== FALSE)
		{
			$listArray[] = $data;
			
		}
		//close the file
		fclose($h);
		
		0echo "<pre>";
		var_dump($listArray);
		echo "</pre>";
		*/
		/*
	$filename = '/hsk1209/www/data/file/'.'filesise.csv';
	// The nested array to hold all the arrays
	$dataArray = [];
	//Open the filefor reading
	if(($h = fopen('{$filename}','r')) !== false)
	{
		$i=0;
		// Each line in the file is converted into an individual array that we call $data
		while(($data = fgetcsv($h, 1000, ",")) !== FALSE)
		{
			//Each individual array is being pushed into the nested array
			$dataArray[$i] = $data;
			$i++;
		}
		// close the file
		fclose($h);

	}
	var_dump(iconv("EUC-KR","UTF-8",$dataArray));
*/

?>
