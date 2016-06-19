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
	 * course		: "소사, 송내", "부평, 인천", ...
	 *
	 * @Return(JSON)
	 * resultTable	: 버스 코스 2차원 배열
	 * e.g., commuteType = "attend", course="소사, 송내"
	 * {
	 *		"7:00":"송내고등학교","7:04":"금호타이어",...
	 * }
	 *
	 * 혹은 (위,아래 중 JS측에서 편한걸로 리턴하겠습니다.)
	 *
	 * {
	 *		"시간":["7:00","7:04",...],
	 *		"정거장":["송내고등학교","금호타이어",...]
	 * }
	 * 
	 * e.g., commuteType = "leave", course="소사, 송내" 
	 * {
	 *		"출발시간":"17시 40분",
	 *		"정거장":["송내고등학교","금호타이어","소사초등학교",...]
	 * }
	 *
	 * resultCode	: 다음과 같습니다.
	 *							http response code
	 * Success			: 1			200
	 * Failed			: 0			404
	 * Exception/Error	: -1		400/500
	 *
	 */

	require_once("../excel.php"); 
	
	http_response_code(400);
	$err = json_encode(Array("resultTable"=>null, "resultCode"=>-1));

	if(!isset($_GET["commuteType"])){
		echo $err;
		exit;
	}
	if(!isset($_GET["course"])){
		echo $err;
		exit;
	}

	$commuteType = $_GET["commuteType"];
	$courseName = $_GET["course"];

	$filename = "../excel/commute_".$commuteType.".xlsx";
	$excel = getExcel($filename);
	if(gettype($excel)=="integer" && $excel==-1){
		echo $err;
		exit;
	}

	$result = array();
	//등교
	if($commuteType=="attend"){
		$sheetName = $courseName;
		$sheet = getSheetByName($sheetName);
		$table = getTable($sheet);

		//2번 옵션을 위한 4줄.
		$records = array();
		$size = count($table["시간"]);
		for($i=0;$i<$size;$i++){
			$records[$table["시간"][$i]] = $table["정거장"][$i];
		}

		//등교 데이터 주는 옵션
		//1번 옵션. {"시간":[...],"정거장":[...]}
		$result["resultTable"] = $table;
		//2번 옵션. {"7:00":"송내초등학교", "7:04":"금호타이어", ...}
		$result["resultTable"] = $records;
	}else if($commuteType=="leave"){//하교

		$sheetNames = $excel->getSheetNames();
		$existFlag = false;
		foreach($sheetNames as $sheetName){
			$sheet = getSheetByName($sheetName);
			$stations = getCellsInColumn($courseName, $sheet);
			if(gettype($stations)=="integer" && $stations==-1){
				continue;
			}
			$existFlag = true;
			$record = array();
			$record["출발시간"] = $sheetName;
			$record["정거장"] = $stations;
			break;
			
		}
		$result["resultTable"] = $record;

	}else{
		echo $err;
		exit;
	}

	$result["resultCode"] = 1;
	http_response_code(200);
	echo json_encode($result);//json decode하면 한글 확인가능.
	//print_r($result); //한글을 바로 확인하고싶으면 이걸로 확인.


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

?>