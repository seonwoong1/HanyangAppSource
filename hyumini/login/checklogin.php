<?php
	require("../db.php");
	/*
	 *	Author: 안윤근
	 *
	 *	@Description
	 *	로그인 시, login.html에서 ajax request를 본 코드로 보내어 
	 *	로그인이 성공했는지, 실패했는지, 첫 로그인인지, 관리자인지, 학생인지 등의 
	 *	상태와 결과 레코드를 리턴합니다.
	 * 
	 *	@Param(GET)
	 *	id: 입력받은 id(혹은 학번)
	 *	pw: 입력받은 패스워드
	 *
	 *	@Return(JSON)
	 *	records: 조회한 레코드 정보입니다.
	 *	resultCode: 리턴 코드는 다음과 같습니다.
	 *
	 *								HTTP Response Code
	 *	Error			:	-1		400/500
	 *	Fail			:	 0		404
	 *	Success			:	 1		200
	 *	First Login		:	 2		200
	 *	Admin			:	 3		200
	 *	Admin & First	:	 4		200
	 *
	 */
	http_response_code(400);
	header("Content-type: application/json");
	$err = json_encode(Array("resultCode"=>-1,"records"=>null));
	if(!isset($_GET["id"])){
		echo $err;
		exit;
	}
	if(!isset($_GET["pw"])){
		echo $err;
		exit;
	}
	$id = quote($_GET["id"]);
	$pw = quote(pwd($_GET["pw"]));

	$table = "User";
	$clause = "WHERE (ID=".$id." OR SID=".$id.") AND PW = ".$pw;
	$cnt = counts($table, $clause);

	$resultCode = -1;
	//입력된 ID/PW가 매칭되는 레코드가 없음
	if($cnt==0){
		$clause = "WHERE ID IS NULL AND SID=".$id." AND PW=".quote($_GET["pw"]);//pwd함수에 넣지 않음.
		//하지만 그 중에서도 First Login인 경우.
		if(counts($table, $clause)==1){
			$column = "master";
			$master = selectOne($table, $column, $clause);
			//근데 하필 관리자인 경우 - master가 1
			if($master==1){
				$resultCode = 4;	
			}
			//관리자는 아니고 일반 학생인 경우
			else{
				$resultCode = 2;
			}	
		}
		//First Login도 아닌경우
		else{
			$resultCode = 0;
		}
	}
	//입력된 ID/PW가 매칭되는 레코드가 '단 하나' 존재하는 경우.
	else if($cnt==1){
		$column = "master";
		$master = selectOne($table, $column, $clause);
		//로그인한게 Admin인 경우
		if($master==1){
			$resultCode = 3;
		}
		//로그인한게 일반 학생인 경우
		else{
			$resultCode = 1;
		}
	}
	//매칭되는 레코드가 두개 이상 존재 => 비정상적인 쿼리
	else{
		echo $err;
		exit;
	}

	//로그인에 실패한 경우
	if($resultCode==0){
		http_response_code(404);
		echo json_encode(Array("resultCode"=>0,"records"=>null));
	}
	//첫 로그인인 경우 레코드 조회가 필요 없음.
	else if($resultCode%2==0){
		http_response_code(200);
		echo json_encode(Array("resultCode"=>$resultCode,"records"=>null));
	}
	//그냥 정상적으로 성공한 경우에는 레코드도 조회해서 정보를 넘겨줌.
	else{
		//ID와 PW는 넘겨줄 필요 없음.
		//어차피 master는 resultCode로 관리자인지 아닌지 알 수 있음.
		//따라서 학번, 이름, 이메일만 조회해서 넘겨준다.
		$columns = Array("SID", "name", "email");
		$records = selectAll($table, $columns, $clause);
		
		$arr = Array("records"=>$records, "resultCode"=>$resultCode);
		$json = json_encode($arr);
		http_response_code(200);
		echo $json;
	}

?>