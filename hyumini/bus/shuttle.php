<?php

	/* 
	 * @Authorship
	 * Author: 안윤근
	 * 
	 * @Description
	 * Shuttle Bus Schedule 정보를 제공합니다.
	 * 파라미터에 따라, 시간표를 제공할지, 노선별 정거장별 가장 가까운 시간을 제공할지 달라집니다.
	 * 
	 * @Parameter(GET)
	 * context	: "now"/"choice"
	 * choice	: 시트이름 (context가 "now" 일때는 무시)
	 *
	 * @Return(JSON)
	 * resultTable: 버스 시간표 2차원 연관 배열
	 * e.g., context = "choice", choice="학기중_한대앞"
	 *  {
	 *		"셔틀콕" : ["8:40", "9:00", "9:20", ...],
	 *		"한대앞" : ["8:30", "8:50", "9:10", ...]
	 *	}
	 * e.g., context = "now"
	 *  {
	 *		//셔틀콕 <-> 한대앞 노선에서 가장 가까운 버스가 도착하는 시간
	 *		"셔틀콕_한대앞" : {"셔틀콕" : "8:50", "한대앞" : "9:00"}, 
	 *		//셔틀콕 <-> 예술인 노선에서 가장 가까운 버스가 도착하는 시간
	 *		"셔틀콕_예술인" : {"셔틀콕" : "8:55", "예술인" : "9:05"}  
	 *	}
	 * resultCode: 다음과 같습니다.
	 *							http response code
	 * Success			: 1				200
	 * Failed			: 0				404
	 * Exception/Error	: -1			400/500
	 * 
	 */

	require_once("./excel.php"); 
	require_once("../db.php");
	http_response_code(400);
	$err = json_encode(Array("resultTable"=>null, "resultCode"=>-1));
	if( !isset( $_GET["context"] ) ){
		echo $err;
		exit;
	}

	if( $_GET["context"]=="choice" && !isset( $_GET["choice"] ) ){
		echo $err;
		exit;
	}

	$context = $_GET["context"];
	$choice = $_GET["choice"];
	
	$filename = "./shuttle.xlsx"; 
	$excel = getExcel($filename);
	if(gettype($excel)=="integer" && $excel==-1){
		echo $err;
		exit;
	}

	
	$result = array();
	if($context=="now"){
		/*
		현재 날짜를 기준으로 db의 학기 구분을 가져온다.
		db의 학기 구분을 기준으로 학기를 잡고, 평일인지 주말인지 판단,
		평일인경우 셔틀콕-한대앞 시간표의 맨 마지막 시간을 가져와서 그 이하라면 
		시트 이름을 판단하고 
		{
			"subway" : {"cock"=>"8:50", "subway"=>"9:00"}, 
			"terminal":{"cock"=>"8:55", "terminal"=>"9:05"}
		}
		이렇게 2차원 연관배열로 역마다 다음에 오는 시간을 리턴한다.
		*/
		date_default_timezone_set('Asia/Seoul');

		$currentTimeInfo = array();

		$nowTime = date("H:i");
		$hour = intval(date("H"));
		$min = intval(date("i"));
		$nowDate = quote(date("Y-m-d"));

		$table = "Semester";
		$column = "semester";
		$clauses = "where ".$nowDate.">=start and ".$nowDate."<=end";
		$semester = selectOne($table, $column, $clauses);

		$sheetName="";
		$sheetName2="";
		if($semester == "semester"){
			$sheetName = "학기중";		

		}else if($semester == "session"){
			$sheetName = "계절학기";

		}else if($semester == "break"){
			$sheetName = "방학중";

		}else{
			echo $err;
			exit;
		}

		$currentTimeInfo = array();

		$dayofweek = date("w");
		
		if($dayofweek == 0){//일요일인 경우
			if($semester=="semester"){
				$sheetName.="_일요일";
			}else{
				$sheetName.="_휴일순환";
			}
			$currentTimeInfo["순환"] = array();
		}else if($dayofweek == 6 || checkHoliday(date("Y-m-d"))){//토요일인경우 혹은 공휴일인경우
			if($semester=="semester"){
				$sheetName.="_토요일공휴일";
			}else{
				$sheetName.="_휴일순환";
			}
			$currentTimeInfo["순환"] = array();
		}else{//평일인 경우
			$sheetName.="_한대앞";
			$sheet = getSheetByName($sheetName);
			$table = getTable($sheet);
			if(gettype($table)=="integer" && $table==-1){
				echo $err;
				exit;
			}

			$lastTime = $table["한대앞"][count($table["한대앞"])-1];
			$nowTimeSec = strtotime($nowTime.":00");
			$lastTimeSec = strtotime($lastTime.":00");
			
			$temp = explode("_", $sheetName);
			//지금이 평일순환시간이면
			if($nowTimeSec>$lastTimeSec){
				$sheetName = $temp[0]."_평일순환";
				$currentTimeInfo["순환"] = array();
			}else{//한대앞, 예술인
				$sheetName2 = $temp[0]."_예술인";
				$currentTimeInfo["셔틀콕_한대앞"] = array();
				$currentTimeInfo["셔틀콕_예술인"] = array();
			}
		}
		
		$sheetNames = array($sheetName,$sheetName2);
		$courses = array("셔틀콕_한대앞","셔틀콕_예술인");
		$nowSec = strtotime($nowTime.":00");
		for($i=0;$i<2;$i++){
			$sName = $sheetNames[$i];
			if($sName==""){
				break;
			}
			$sheet = getSheetByName($sName);
			$table = getTable($sheet);

			$stations = array_keys($table);
			foreach($stations as $station){
				$nextTime = $table[$station][0];
				foreach($table[$station] as $time){
					$tmpSec = strtotime($time.":00");
					if($tmpSec>$nowSec){
						$nextTime = $time;
						break;
					}
				}
				if($sheetNames[1]==""){
					$currentTimeInfo["순환"][$station]=$nextTime;
				}else{
					$currentTimeInfo[$courses[$i]][$station]=$nextTime;
				}
			}			
		}
		
		$result["resultTable"]=$currentTimeInfo;
		
	}else if($context=="choice"){
		$sheet = getSheetByName($choice);
		$table = getTable($sheet);
		$result["resultTable"]=$table;
		
	}else{
		echo $err;
		exit;
	}

	$result["resultCode"]=1;
	http_response_code(200);
	echo json_encode($result);


	//시트를 가져올 때 마다 반복되는 예외처리를 위해 작성한 함수
	function getSheetByName($sheetName){
		global $excel, $err;
		$sheet = getSheet($sheetName, $excel);
		if((gettype($sheet)=="integer" && $sheet==-1) || $sheet==null){
			echo $err;
			exit;
		}
		return $sheet;
	}


	/*
	 * @Authorship
	 * Origin: http://sir.kr/cm_free/1083418
	 * Bring, Modify: 안윤근
	 * 원작자의 요청: "알쯔는 멋지다!"
	 *
	 * @Description
	 * SKT Developer API를 사용해서 공휴일을 체크합니다.
	 */
	function checkHoliday($date){
		//$date = "2016-6-6"; // 현충일
		//$date = "2016-6-5"; // 일요일
		$time = strtotime($date);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,'https://apis.sktelecom.com/v1/eventday/days?type=h&year='.date('Y',$time).'&month='.date('m',$time).'&day='.date('d',$time));
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSLVERSION,3);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_POST,0);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_HTTPHEADER,array(
			'TDCProjectKey: f125aec9-9e25-422c-b352-65414559d66c',
			'Accept: application/json'
		));
		$currentTimeInfo = curl_exec($ch);
		curl_close($ch);
		
		$data = json_decode($currentTimeInfo);

		if ($data->totalResult == 1) { // 공휴일
			return true;
		} else { // 평일
			return false;
		}
	}
	
?>