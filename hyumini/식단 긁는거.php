<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title></title>
</head>
<body>
	<?php
	// curl is an extension that needs to be installed,
	// php.ini 에서 이 부분 수정 ;extension=php_curl.dll
	
	$location 	= "";
	$time_lunch	= "";
	$time_diner	= "";
	$lunch		= array();
	$diner		= array();
	$lunch_price= array();
	$diner_price= array();
	

	/* URL에서 restId 가 있는데, 1,2,3,4,5 가 각각 
	   교직원, 학생, 창업보육센터, 푸드코트, 창의인재원식당을 의미한다.
	*/
	$curl = curl_init('https://m.hanyang.ac.kr/wlife/diet/diet001002.page?campusId=02&restId=01&apiUrl%5B%5D=%2FDHSH%2FA201300004.json');
	curl_setopt($curl, CURLOPT_FAILONERROR, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($curl);
	
	
	$result = preg_replace("(\<(/?[^\>]+)\>)", "\n", $result);
	$result = preg_replace("#[[:space:]]{2,}#", "\n", $result);
	
	
	$pattern = "/위치.+/";
	preg_match($pattern, $result, $matches);
	$location = $matches[0];
	
	
	$pattern = "/중식.+/";
	preg_match($pattern, $result, $matches);
	$time_lunch = $matches[0];
	
	$pattern = "/석식.+/";
	preg_match($pattern, $result, $matches);
	$time_diner = $matches[0];	

	
	$pattern = "/중식\\n(.*?)\\n(.*?)\\n/";
	preg_match_all($pattern, $result, $matches);
	$lunch = $matches[1];
	$lunch_price = $matches[2];
	
	
	$pattern = "/석식\\n(.*?)\\n(.*?)\\n/";
	preg_match_all($pattern, $result, $matches);
	$diner = $matches[1];
	$diner_price = $matches[2];
	
	
	
	echo "<p>";
	echo $location;
	echo "</p>";
	
	echo "<p>";
	echo $time_lunch;
	echo "</p>";
	
	echo "<p>";
	echo $time_diner;
	echo "</p>";	
	
	// 여기서 뒤에 0, 1, 2, 3, 4 를 넣으면 각 요일의 메뉴가 나온다.
	echo "<p>월요일 점심</br>";
	echo $lunch[0]."</br>";
	echo $lunch_price[0];
	echo "</p>";
	
	echo "<p>월요일 저녁</br>";
	echo $diner[0]."</br>";
	echo $diner_price[0];
	echo "</p>";
	
	echo "<p>더 추가할 것은 소스 보기를 해서, 어떤 HTML 소스가 생성되었는지를 보면 됩니다.</p>
	
	
	//echo $result;
	curl_close($curl);
	
  ?>
</body>
</html>