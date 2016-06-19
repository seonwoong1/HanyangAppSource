<?php
	require_once("../../db.php");
	/*
	 *	@Author
	 *	Author: 안윤근
	 *
	 *	@Description
	 *	해당 id/sid가 첫 로그인인지 확인합니다.
	 *
	 *	@Param(POST)
	 *	id: forgotpw.php에서 입력받은 ID 혹은 학번
	 *
	 *	@Return(JSON)
	 *	reason: 실패/성공 이유
	 *	resultCode: 다음과 같습니다.			
	 *	First Login					:	1	200
	 *	Not First Login				:	2	200
	 *	The ID/SID does not exist	:	0	404
	 *	Exception/Error				:	-1	400
	 */

	http_response_code(400);
	header("Content-type: application/json");
	$err = json_encode(Array("reason"=>"Exception/Error","resultCode"=>-1));
	if(!isset($_GET["id"])){
		echo $err;
		exit;
	}
	$id = $_GET["id"];
	$table = "User";
	$clause = "WHERE id=".quote($id)." OR SID=".quote($id);
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

	http_response_code(200);
	$clause = "WHERE ID IS NULL AND SID=".quote($id)." AND PW=".quote($id);//pwd함수에 넣지 않음.
	//First Login인 경우.
	if(counts($table, $clause)==1){	
		echo json_encode(Array("reason"=>"First Login","resultCode"=>1));
	}
	//First Login도 아닌경우
	else{
		echo json_encode(Array("reason"=>"Not First Login","resultCode"=>2));
	}

?>