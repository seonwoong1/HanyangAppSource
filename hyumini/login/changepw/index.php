<?php

	/*
	 *	Author: 안윤근
	 *	@Description
	 *	현재 세션의 유저의 pw를 바꿔줍니다.
	 *
	 *	@Param(POST)
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
	
	require_once("../../db.php");
	
	function validation($pw){
		global $table, $clause;
		$minLen = 8;
		$id = selectOne($table, "id", $clause);
		$sid = selectOne($table, "sid", $clause);
		$oldpw = selectOne($table,"pw",$clause);
		if($pw==$id)
			return "ID와 동일한 비밀번호는 사용하실 수 없습니다.";
		if($pw==$sid)
			return "학번과 동일한 비밀번호는 사용하실 수 없습니다.";
		if(is_numeric($pw))
			return "숫자만으로 이루어진 패스워드는 사용하실 수 없습니다.";
		if(strlen($pw)<$minLen)
			return "최소 ".$minLen."자리가 넘는 패스워드를 사용하셔야 합니다.";
		if(pwd($pw)==$oldpw)
			return "이전에 사용한 패스워드는 사용하실 수 없습니다.";
		return null;
	}

	http_response_code(400);
	header("Content-type: application/json");
	$err = json_encode(Array("reason"=>"Exception/Error", "resultCode"=>-1));

	//임시 세션 검증
	session_start();
	if(!isset($_SESSION["valid"])||!isset($_SESSION["SID"])||!$_SESSION["valid"]){
		session_destroy();
		echo $err;
		exit;
	}

/*
	if(!isset($_POST["id"])){
		echo $err;
		exit;
	}*/
	if(!isset($_POST["newPW"])){
		echo $err;
		exit;
	}
	$sid= quote($_SESSION["SID"]);
	$pw = $_POST["newPW"];

	$table = "User";
	$clause = "WHERE SID = ".$sid;
	$cnt = counts($table, $clause);
	//없는 ID.
	if($cnt==0){
		http_response_code(404);
		echo json_encode(Array("resultCode"=>0,"reason"=>"SID is not exist."));
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
			$cnt = update($table, $set, $clause);
			if($cnt==1){
				http_response_code(200);
				echo json_encode(Array("resultCode"=>1,"reason"=>null));
			}else{
				echo $err;
			}
		}
	}

?>