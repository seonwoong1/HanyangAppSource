<?php
	require("../db.php");
	
	/*
	 *	Author: 안윤근
	 *	@Description
	 *	inputotp.php에서 학번(ID)와 사용자에게 입력받은 OTP를 ajax request로 보내면,
	 *	DB에서 해당 학번(ID)로 발행된 OTP를 조회하여,
	 *	1. expired Date가 지났는지 확인하고
	 *	2. OTP가 매치되는지 확인합니다.
	 *	3. 1,2를 모두 만족한다면 테이블에서 해당 OTP 레코드를 삭제합니다.
	 *
	 *	@Param(GET)
	 *	id: forgotpw.php에서 입력받은 ID 혹은 학번
	 *	otp: 사용자로부터 입력받은 otp
	 *
	 *	@Return(JSON)
	 *	reason: 실패한 이유
	 *	resultCode: 다음과 같습니다.
	 *	Matched						:  1
	 *	Not Matched	or OTP Expired	:  0
	 *	Exception/Error				: -1
	 */
	
	http_response_code(400);
	header("Content-type: application/json");
	$err = json_encode(Array("reason"=>"Exception/Error", "resultCode"=>-1));
	if(!isset($_GET["id"])){
		echo $err;
		exit;
	}
	if(!isset($_GET["otp"])){
		echo $err;
		exit;
	}
	$id = $_GET["id"];
	$inputotp = pwd($_GET["otp"]);
	
	$table = "User";
	$column = "SID";
	$clause = "WHERE id=".quote($id)." OR sid=".quote($id);
	$sid = selectOne($table,$column,$clause);

	$table = "OTP";
	$column = "*";
	$clauses = Array("WHERE"=>"SID=".quote($sid), "ORDER BY"=>"expire DESC", "LIMIT"=>1);
	$result = selectAll($table, $column, $clauses);
	//print_r($result);
	if(count($result)==0){
		echo $err;
		exit;
	}

	$result = $result[0];
	$otp = $result["OTP"];
	$expire = $result["expire"];
	$expire = DateTime::createFromFormat("Y-m-d H:i:s", $expire);
	$expire = $expire->getTimestamp();
	
	http_response_code(404);
	if((time()-$expire)>300){
		echo json_encode(Array("reason"=>"OTP already expired.","resultCode"=>0));
	}
	else if($inputotp != $otp){
//		echo $inputotp."<br/>";
//		echo $otp."<br/>";
		echo json_encode(Array("reason"=>"Incorrect OTP.","resultCode"=>0));
	}else{
		deletes($table, $clauses);
		http_response_code(200);
		echo json_encode(Array("resultCode"=>1));
	}

?>