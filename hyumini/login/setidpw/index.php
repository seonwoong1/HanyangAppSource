<?php
	require("../../db.php");
	/*
	 *	Author: 안윤근
	 *	@Description
	 *	첫 로그인 시, firstlogin.html에서 ajax request를 본 코드로 보내어 
	 *	새로운 id와 PW를 설정합니다.
	 * 
	 *	Update이기 때문에 PUT이 맞겠지만, 핸들링이 다소 번거롭기 때문에 편의상 POST를 사용합니다...
	 *	@Param(POST)
	 *	SID: login.html에서 입력받았던 학번
	 *	newID: 새로 설정할 id
	 *	newPW: 새로 설정할 pw
	 *
	 *	@Return(JSON)
	 *	reason: 변경 실패시의 이유입니다.
	 *	resultCode: 리턴 코드는 다음과 같습니다.
	 *  
	 *	Setting Success	  :  1	200
	 *	Setting Failed	  :  0	404
	 *	Exception/Error	  : -1	400
	 */

	/*	2a. 
	 *	알파뱃과 숫자만으로 이루어지지 않은 id, 
	 *	숫자만으로 이루어진 id,
	 *	24자리가 넘는 id, 
	 *	이미 존재하는 id, 
	 *	학번과 같은 pw,
	 *	ID와 같은 pw인 경우에 각각의 error reason 리턴
	 *	정상적이면 null 리턴
	 */
	function validation($id, $pw){
		global $sid, $table;
		$maxLen = 24;//ID 최대 자리수
		//ID, PW 최소 자리수
		$minIdLen = 6;
		$minPwLen = 8;
		$clause = "WHERE id=".quote($id);
		if(strlen($id)>$maxLen)
			return $maxLen."자리가 넘는 ID는 사용하실 수 없습니다.";
		if(strlen($id)<$minIdLen)
			return "최소 ".$minIdLen."자리가 넘는 ID를 사용하셔야 합니다.";
		if(is_numeric($id))
			return "숫자만으로 이루어진 ID는 사용하실 수 없습니다.";
		if(!ctype_alnum($id))
			return "ID는 알파뱃과 숫자만으로 이루어져야 합니다.";
		if($pw==$id)
			return "ID와 동일한 비밀번호는 사용하실 수 없습니다.";
		if(quote($pw)==$sid)
			return "학번과 동일한 비밀번호는 사용하실 수 없습니다.";
		if(is_numeric($pw))
			return "숫자만으로 이루어진 패스워드는 사용하실 수 없습니다.";
		if(strlen($pw)<$minPwLen)
			return "최소 ".$minPwLen."자리가 넘는 패스워드를 사용하셔야 합니다.";
		if(counts($table, $clause)!=0)
			return "이미 사용되고있는 ID입니다.";
		return null;
	}


	http_response_code(400);
	header("Content-type: application/json");
	$err = json_encode(Array("reason"=>"Exception/Error", "resultCode"=>-1));
	if(!isset($_POST["SID"])){
		echo $err;
		exit;
	}
	if(!isset($_POST["newID"])){
		echo $err;
		exit;
	}
	if(!isset($_POST["newPW"])){
		echo $err;
		exit;
	}
	$sid = $_POST["SID"];
	$id = $_POST["newID"];
	$email = $id."@hanyang.ac.kr";
	$newPW = $_POST["newPW"];
	$pw = pwd($newPW);

	$table = "User";
	//1. 진짜 첫 로그인인지 DB조회
	$clause = "WHERE SID=".quote($sid)." AND ID IS NULL";//해당 학번의 ID필드가 null이면 첫 로그인으로 판정.
	//만약 검색된 레코드가 1개라면(정상적인 첫 로그인인 경우) 
	if(counts($table, $clause)==1){
		//2. id, PW 검증
		$reason = validation($id, $newPW);
		//새로운 id 와 pw가 모두 valid함
		if($reason==null){
			//3. db update
			$set = Array("id"=>$id, "pw"=>$pw, "email"=>$email);
			$cnt = update($table, $set, $clause);
			//정상적인 update
			if($cnt==1){
				http_response_code(200);
				echo json_encode(Array("resultCode"=>1,"reason"=>null));
			}
			//3a. 만약 update로 영향받은 레코드 갯수가 1개가 아니라면 -1리턴(비정상)
			else{
				echo $err;
				exit;
			}
		}
		//invalid
		else{
			http_response_code(404);
			echo json_encode(Array("reason"=>$reason, "resultCode"=>0));
		}
	}
	//검색된 레코드가 0개 혹은 2개 이상인 경우(비정상적인 결과)
	else{
		echo $err;
	}

?>