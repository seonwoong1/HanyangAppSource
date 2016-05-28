<?php

	require("../db.php");
	
	/*
	 *	Author: 안윤근
	 *	@Description
	 *	사용자의 pw를 바꿔줍니다.
	 *
	 *	@Param(POST)
	 *	id: forgotpw.php에서 입력받은 ID 혹은 학번
	 *	newPW: 사용자로부터 입력받은 otp
	 *
	 *	@Return(JSON)
	 *	reason: 실패한 이유
	 *	resultCode: 다음과 같습니다.
	 *											HTTP Response Code
	 *	Matched						:  1		200
	 *	Not Matched	or OTP Expired	:  0		404
	 *	Exception/Error				: -1		400/500
	 */
	
	function validation($pw){
		global $table, $clause;
		$minLen = 8;
		$id = selectOne($table, "id", $clause);
		$sid = selectOne($table, "sid", $clause);
		if($pw==$id)
			return "ID와 동일한 비밀번호는 사용하실 수 없습니다.";
		if($pw==$sid)
			return "학번과 동일한 비밀번호는 사용하실 수 없습니다.";
		if(is_numeric($pw))
			return "숫자만으로 이루어진 패스워드는 사용하실 수 없습니다.";
		if(strlen($pw)<$minLen)
			return "최소 ".$minLen."자리가 넘는 패스워드를 사용하셔야 합니다.";
		return null;
	}

	http_response_code(400);
	header("Content-type: application/json");
	$err = json_encode(Array("reason"=>"Exception/Error", "resultCode"=>-1));
	if(!isset($_POST["id"])){
		echo $err;
		exit;
	}
	if(!isset($_POST["newPW"])){
		echo $err;
		exit;
	}
	$id = quote($_POST["id"]);
	$pw = $_POST["newPW"];

	$table = "User";
	$clause = "WHERE id=".$id." OR SID=".$id;
	$cnt = counts($table,$clause);
	//없는 ID.
	if($cnt==0){
		http_response_code(404);
		echo json_encode(Array("resultCode"=>0,"reason"=>"ID or SID is not exist."));
	}else if($cnt!=1){//ID가 여러개로 나옴(비정상)
		echo $err;
	}else{
		$reason = validation($pw);
		if($reason!=null){
			http_response_code(404);
			echo json_encode(Array("reason"=>$reason, "resultCode"=>0));
		}
		else{
			$set = Array("pw"=>pwd($pw));
			$cnt = update($table,$set,$clause);
			if($cnt==1){
				http_response_code(200);
				echo json_encode(Array("resultCode"=>1,"reason"=>null));
			}else{
				echo $err;
			}
		}
	}

?>