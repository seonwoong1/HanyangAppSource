<?php
	require_once("./db.php");
	/*
	 * @Author
	 * Author: 안윤근
	 * 
	 * @Description
	 * DB에 편하게 집어넣기 위해 임시로 만든 스크립트입니다.
	 * 원래 매니저페이지에서 엑셀을 파싱해서 집어넣어야 하지만..
	 * 매니저페이지를 만들 시간이 없었기 때문에 임시로 이 스크립트를 사용합니다.
	 * 나중에 매니저페이지 만들게 됬을 때 약간의 수정만으로 사용할 수 있게 만들었습니다.
	 *
	 */

	function inserting($tableName, $datas){
		//테이블 비우기
		deletes($tableName);
		//insert
		foreach($datas as $data){
			insert($tableName, $data);
		}
	}

	//유저 정보
	$users = [
	//	ID		SID		     email PW			 name  master
		[null, "Admin",	     null, "Admin",		 "관리자", 1],
		["rpsprpsp","TESTER1","frebern@naver.com",pwd("rlslrlsl"),"테스터1",0],
		["rlslrlsl","TESTER2","frebern@naver.com",pwd("rpsprpsp"),"테스터2",1],
		//안윤근
		[null, "2013043285", null, "2013043285", "안윤근", 0]
		//이학진
		//김진희
		//성다혜
		//우승연
		//송형석
		//김형락
		//김선웅
		//오지강
	];

	//강의스케쥴 정보
	$schedules = [
	//	lectureID	dayofweek	startTime	endTime	classroom
		//안윤근
		['ELE3026', 1, '13:00:00', '14:30:00', 'Y005-0305'],
		['CSE4006', 2, '11:00:00', '12:30:00', 'Y005-0305'],
		['GEN4051', 3, '15:30:00', '17:30:00', 'Y015-0301'],
		['ELE3026', 5, '10:30:00', '12:00:00', 'Y005-0304'],
		['CSE4006', 5, '13:00:00', '15:00:00', 'Y021-0318'],
		['CSE4006', 5, '15:00:00', '16:30:00', 'Y005-0305']
		//이학진
		//김진희
		//성다혜
		//우승연
		//송형석
		//김형락
		//김선웅
		//오지강
	];

	//강의 정보
	$lectures = [
	//	lectureID	lectureName	Professor
		//안윤근
		['ELE3026', '객체지향개발론', '김정선'],
		['CSE4006', '소프트웨어공학', 'Scott Uk-Jin Lee'],
		['GEN4051', '초우량기업의조건', '전상길']
		//이학진
		//김진희
		//성다혜
		//우승연
		//송형석
		//김형락
		//김선웅
		//오지강
	];

	//아이디와 듣는강의 연결
	$connections = [
	//	SID		lectureID
		//안윤근
		["2013043285", "ELE3026"],
		["2013043285", "CSE4006"],
		["2013043285", "GEN4051"]
		//이학진
		//김진희
		//성다혜
		//우승연
		//송형석
		//김형락
		//김선웅
		//오지강
	];

	
	$dataSet = array("User"=>$users, "LectureSchedule"=>$schedules, "Lecture"=>$lectures, "ConnectLecture"=>$connections);
	foreach($dataSet as $table=>$datas){
		inserting($table,$datas);
	}

	//OTP 테이블 비우기.
	deletes("OTP");

	//확인하기
	echo "Done!";

?>