<?php
	require("../db.php");
	/*
	 *	Author: 안윤근
	 *	@Description
	 *	패스워드를 잊어버렸을 때, forgotpw.php에서 ajax request를 보내면,
	 *	otp를 생성하여 한양메일로 메일을 보냅니다.
	 *  otp 테이블에 학번과 otp를 저장합니다.
	 *	첫 로그인도 안한 학번의 비밀번호를 찾는 경우는 본 페이지를 호출하지 않고 forgotpw.php에서 바로 처리합니다.
	 *  (sendotp.php에서 해줄 일이라고 보기에 어렵습니다.)
	 *
	 *	@Param(POST)
	 *	id: forgotpw.php에서 입력받은 ID 혹은 학번
	 *
	 *	@Return(TEXT)
	 *				
	 *	Success				:  1
	 *	Failed				:  0
	 *	Exception/Error		: -1
	 */

	function generateOTP(){
		$alnum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$alnum = str_shuffle($alnum);
		$otp = substr($alnum, rand(0, 12), 7);
		$otp = str_shuffle($otp);
		echo $otp;
		return $otp;
	}
	
	function sendOTP($mailto, $otp){
		$subject = "OTP from HYU(e)mini.";
		$message = "OTP: ".$otp;
		return mail($mailto, $subject, $message);
	}
	http_response_code(400);
	if(!isset($_POST["id"])){
		echo -1;
		exit;
	}
	$id = quote($_POST["id"]);
	$table = "User";
	$clause = "WHERE id=".$id." OR SID=".$id;
	$cnt = counts($table, $clause);

	//레코드가 없으면 존재하지 않는 ID
	if($cnt==0){
		http_response_code(404);
		echo 0;
		exit;
	}else if($cnt!=1){//1개가 아니면 뭔가 비정상적인 결과
		echo -1;
		exit;
	}

	$email = selectOne($table, "email", $clause);
	$sid = selectOne($table, "SID", $clause);

	$table = "OTP";
	$clause = "WHERE SID=".$sid;
	$cnt = counts($table, $clause);
	$otp = generateOTP();
	$expire = date("Y-m-d H:i:s", time()+300);
	if($cnt==0){
		$params = Array($sid, pwd($otp), $expire);
		if(insert($table, $params)==-1){
			echo -1;
			exit;
		}
	}else if($cnt==1){
		$set = Array("OTP"=>pwd($otp), "expire"=>$expire);
		if(update($table, $set, $clause)!=1){
			echo -1;
			exit;
		};
	}else{
		echo -1;
		exit;
	}
	if(sendOTP($email, $otp)){
		http_response_code(200);
		echo 1;
	}else{
		http_response_code(500);
		echo -1;
	}
	
?>