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
	 */

	//유저 정보
	$ten_users = [
	//	ID		SID		   email name  master
		[null,"2013043280",null,"2013043280","성다혜",0],
		[null,"2013043281",null,"2013043281","우승연",0],
		[null,"2013043282",null,"2013043282","송형석",0],
		[null,"2013043283",null,"2013043283","이학진",0],
		[null,"2013043284",null,"2013043284","김진희",0],
		[null,"2013043285",null,"2013043285","안윤근",0],
		[null,"2013043286",null,"2013043286","김형락",0],
		[null,"2013043287",null,"2013043287","오지강",0],
		[null,"2013043288",null,"2013043288","김선웅",1],
		[null,"Admin",	   null,"Admin","관리자",1],
	];

	//테이블 비우기
	deletes("User");
	deletes("OTP");

	//insert
	foreach($ten_users as $user){
		insert("User",$user);
	}

	//확인하기
	//print_r(selectAll("User"));
	echo "Done!";

?>