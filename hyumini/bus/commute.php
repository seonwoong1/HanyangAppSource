<?php

	/* 
	 * @Authorship
	 * Author: 안윤근
	 * 
	 * @Description
	 * Commute Bus Schedule 정보를 제공합니다.
	 * 
	 * @Parameter(GET)
	 * commuteType	: "attend", "leave"
	 * leaveTime	: null or "17:40", "22:00" (commuteType="leave" 인 경우에 설정)
	 * course		: "소사,송내", "부평,인천", ...
	 *
	 * @Return(JSON)
	 * resultTable	: 버스 코스 2차원배열
	 * resultCode	: 다음과 같습니다.
	 *							http response code
	 * Success			: 1			200
	 * Failed			: 0			404
	 * Exception/Error	: -1		400/500
	 *
	 */

	require_once("./excel.php"); 
	
	http_response_code(400);
	$err = json_encode(Array("resultTable"=>null, "resultCode"=>-1));
	if( !isset( $_GET["commuteType"] ) ){
		echo $err;
		exit;
	}

	if( !isset( $_GET["choice"] ) ){
		echo $err;
		exit;
	}

	$excel = getExcel($filename);
	$sheet = getSheet(0, $excel);
	printSheet($sheet);
	
?>