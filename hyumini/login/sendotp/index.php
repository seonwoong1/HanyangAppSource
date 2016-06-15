<?php
	require_once("../../db.php");
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
	 *	@Return(JSON)
	 *	resultCode: 다음과 같습니다.			
	 *	OTP successfully sent	:  1	200
	 *	Sending OTP failed		:  0	404
	 *	Exception/Error			: -1	400
	 */

	//7자리 OTP 생성 함수
	function generateOTP(){
		$alnum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$alnum = str_shuffle($alnum);
		$otp = substr($alnum, rand(0, 12), 7);
		$otp = str_shuffle($otp);
//		echo $otp;
		return $otp;
	}
	
	//OTP를 메일로 보내는 함수.
	function sendOTP($mailto, $otp){
		$subject =	"OTP from HYU ⓔ mini.";
		$message =	"HYU ⓔ mini OTP: ".$otp;
		$headers =	'From: hyumini@hanyang.ac.kr' . "\r\n" .
					'Reply-To: hyumini@hanyang.ac.kr' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		return mail($mailto, $subject, $message, $headers);
	}

	http_response_code(400);
	header("Content-type: application/json");
	$err = json_encode(Array("reason"=>"Exception/Error","resultCode"=>-1));
	if(!isset($_POST["id"])){
		echo $err;
		exit;
	}
	$id = quote($_POST["id"]);
	$table = "User";
	$clause = "WHERE id=".$id." OR SID=".$id;
	$cnt = counts($table, $clause);

	//레코드가 없으면 존재하지 않는 ID
	if($cnt==0){
		http_response_code(404);
		echo json_encode(Array("reason"=>"The ID/SID does not exist.","resultCode"=>0));
		exit;
	}else if($cnt!=1){//1개가 아니면 뭔가 비정상적인 결과
		echo $err;
		exit;
	}

	$email = selectOne($table, "email", $clause);
	$sid = selectOne($table, "SID", $clause);

	$table = "OTP";
	$clause = "WHERE SID=".$sid;
	$cnt = counts($table, $clause);
	$otp = generateOTP();
	$expire = date("Y-m-d H:i:s", time()+1800);
	if($cnt==0){
		$params = Array($sid, pwd($otp), $expire);
		if(insert($table, $params)==-1){
			echo $err;
			exit;
		}
	}else if($cnt==1){
		$set = Array("OTP"=>pwd($otp), "expire"=>$expire);
		if(update($table, $set, $clause)!=1){
			echo $err;
			exit;
		};
	}else{
		echo $err;
		exit;
	}
	if(sendOTP($email, $otp)){
		http_response_code(200);
		echo json_encode(Array("reason"=>"Send OTP S","resultCode"=>1));
	}else{
		http_response_code(500);
		echo $err;
	}
	
?>