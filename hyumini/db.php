<?php

	/* 
	 *	Author: 안윤근 
	 *	Description:
	 *	db.php는 PDO를 사용하여 Database처리의 전반적인 CRUD 기본기능들을 일반화하여 사용하기 편하게 구현한 라이브러리입니다. 
	 *	왠만한 부분에서는 prepare를 사용하여 기본적인 Injection에 대비를 했으나, 
	 *	그럼에도 불구하고 보안에 다소 취약할 수 있으니 기본적인 입력값검증은 미리 하시고 사용하시길 바랍니다.
	 */

	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	/* 
	 *	assert_option과 handler 코드는 php.net의 예제에서 가져왔습니다. 
	 *	http://php.net/manual/kr/function.assert.php	
	 */
	assert_options(ASSERT_ACTIVE, 1);
	assert_options(ASSERT_WARNING, 0);
	assert_options(ASSERT_QUIET_EVAL, 1);
	// Create a handler function
	function my_assert_handler($file, $line, $code, $desc = null)
	{
		echo "Assertion failed at $file:$line: $code";
		if ($desc) {
			echo ": $desc";
		}
		echo "\n";
	}

	// Set up the callback
	assert_options(ASSERT_CALLBACK, 'my_assert_handler');

	//DB 연결을 위한 attribute들
	$dbkind = "mysql";
	$host = "127.0.0.1";
	$dbname = "hyumini";

	$dsn = $dbkind.":host=".$host.";dbname=".$dbname;

	$user = "hyumini";
	$passwd = "hyu(e)mini";
	$conf = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $user, $passwd, $conf);

	/* 
	 *	Author: 안윤근 
	 *	@Params
	 *	string table: table이름
	 *	[array columns: 필드 이름들]
	 *	array params: insert할 레코드의 파라미터들
	 *
	 *	@Return
	 *	OnSuccess: 0
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	기본적인 insert 쿼리를 지원합니다.
	 *	컬럼명들을 지정한다면 Insert into tableName(컬럼들) values(파라미터들) 쿼리를 실행합니다.
	 *	지정하지 않고 파라미터들만 지정한다면 Insert into tableName values(파라미터들) 쿼리를 실행합니다.
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 *
	 *	@Issue
	 *	insert 하는 데이터들은 자동으로 PDO::prepare를 거쳐 quote()되어 들어갑니다.
	 *	따라서 미리 quote를 해서 넣으면 의도와 다르게 입력될 수 있습니다.
	 *
	 */
	function insert(){

		//Assertions - 오버로딩을 위한 assertion들... 솔직히 지저분하네요..
		assert(func_num_args()>1);
		assert(gettype(func_get_arg(0))=="string");
		assert(gettype(func_get_arg(1))=="array");
		if(func_num_args()==3)	
			assert(gettype(func_get_arg(2))=="array");
		assert(func_num_args()<4);

		//Implementation
		//PHP에서 파라미터 갯수로 오버로딩 구현..

		global $pdo;

		$num = func_num_args();
		$args = func_get_args();

		$table = $args[0];
		$params = $args[$num-1];

		//prepare statement build
		$prepare = "INSERT INTO ".$table;
		if($num==3){
			$columns = $args[1];
			$prepare.=" (".implode(",",$columns).")";
		}
		$prepare.=" VALUES(";
		$count = count($params);
		$arr = Array();
		for($i=0;$i<$count;$i++){
			$arr[$i]="?";
		}
		$prepare.=implode(",",$arr);
		$prepare.=")";
	
		$stmt = $pdo->prepare($prepare);
		$stmt->execute($params);

		//디버깅용 오류메시지
		$err = $stmt->errorInfo();
		if(isset($err[2])){
			print_r($err);
			return -1;
		}
		return 0;
	}


	/* 
	 *	Author: 안윤근 
	 *	@Params
	 *	string table: table이름
	 *	string column: 단일 필드 이름
	 *	string/array clauses: where, order by 등의 조건절 들
	 *
	 *	@Return
	 *	OnSuccess: result value
	 *	OnEmptyResult: null
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	이 함수는
	 *	SELECT column FROM table clauses LIMIT 1
	 *	쿼리를 실행하고,
	 *	오로지 결과값 하나만을 리턴합니다. (레코드 한줄이 아닌 결과값 하나입니다.)
	 *
	 *	예를 들어, SELECT name from test where id=1; 
	 *	이런식의 쿼리를 요청시 레코드 전체가 아닌 "안윤근" 하나만을 리턴합니다.
	 *	편의상 만든 함수이기 때문에 여러 레코드를 리턴할 필요가 있을 때에는 selectAll 함수를 호출해주세요.
	 *
	 *	clauses가 만약,
	 *	key=>value array로 들어온 경우,
	 *	SELECT column FROM table key0 value0 key1 value1 ... LIMIT 1
	 *	쿼리를 실행합니다.
	 *
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 *
	 */
	function selectOne($table, $column, $clauses=""){

		//Assertions - 입력값 검증
		assert(gettype($table)=="string");
		assert(isset($column) || gettype($column)=="string");
		assert(gettype($clauses)=="array" || gettype($clauses)=="string");

		global $pdo;

		$query = "SELECT ";
		$query.=$column;
		$query.=(" FROM ".$table);

		$query.=clauseBuild($clauses);

		$query.=" LIMIT 1";
		//print($query);
		$stmt = rawQuery($query);
		$result = $stmt->fetchAll();
		if(count($result)==0){
			return null;
		}
		return $result[0][0];
		
	}

	/* 
	 *	Author: 안윤근
	 *	@Params
	 *	string table: table이름
	 *	string/array columns: 필드들 이름, default "*"
	 *	string/array clauses: where, order by 등의 조건절 들
	 *
	 *	@Return
	 *	OnSuccess: 결과 레코드들의 연관배열
	 *	OnEmptySet: null
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	쿼리로 검색된 레코드 전부를 리턴합니다.
	 *	SELECT columns FROM table clauses 쿼리를 실행합니다.
	 * 
	 *	clauses가 만약,
	 *	key=>value array로 들어온 경우,(ex: Array("WHERE" => "...", "ORDER BY"=>"..."))
	 *	SELECT columns FROM table key0 value0 key1 value1 ...
	 *	쿼리를 실행합니다.
	 * 
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 */
	function selectAll($table, $columns="*", $clauses=""){
		//Assertions - 입력값 검증
		assert(gettype($table)=="string");
		assert(gettype($columns)=="array" || gettype($columns)=="string");
		assert(gettype($clauses)=="array" || gettype($clauses)=="string");

		global $pdo;

		$query = "SELECT ";
		if(gettype($columns)=="array"){
			$columns=implode(",",$columns);
		}
		$query.=$columns;
		
		$query.=(" FROM ".$table);

		$query.=clauseBuild($clauses);

		$stmt = rawQuery($query);

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($result)==0){
			return null;
		}
		return $result;
	}

	/* 
	 *	Author : 안윤근 
	 *	@Params
	 *	string table : table이름
	 *	string/array set : 업데이트 하려는 필드=>값 연관배열
	 *	string/array clauses : where, order by 등의 조건절 들
	 *	set은
	 *	Array("num"=>1,"name"=>"홍길동","age"=>30) 혹은,
	 *	"num=1, name='홍길동', age=30" 
	 *	이렇게 set절을 raw string으로 주셔도 좋습니다.
	 *
	 *	@Return
	 *	OnSuccess: update문에 의해 영향받은 레코드 갯수 (>=0)
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	UPDATE table SET setkey0=setVal0, ... clauses 쿼리를 실행합니다.
	 * 
	 *	clauses가 만약,
	 *	key=>value array로 들어온 경우,
	 *	UPDATE ... key0 value0 key1 value1 ...
	 *	쿼리를 실행합니다.
	 * 
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 *
	 *	@Issue
	 *	insert 하는 데이터들은 자동으로 PDO::prepare를 거쳐 quote()되어 들어갑니다.
	 *	따라서 미리 quote를 해서 넣으면 의도와 다르게 입력될 수 있습니다.
	 *
	 */
	function update($table, $set, $clauses=""){
		assert(gettype($table)=="string");
		assert(gettype($set)=="array" || gettype($set)=="string");
		assert(gettype($clauses)=="array" || gettype($clauses)=="string");

		global $pdo;

		//prepare statement build
		$prepare = "UPDATE ".$table." SET ";
		if(gettype($set)=="array"){
			$setter=Array();
			foreach($set as $column=>$value){
				array_push($setter, $column."=?");
			}
			$prepare .= implode(", ",$setter);
		}else{
			$prepare .= $set;
		}

		$prepare.=clauseBuild($clauses);
		//print("<br/>".$prepare."<br/>");

		$stmt = $pdo->prepare($prepare);
		$stmt->execute(array_values($set));

		//디버깅용 오류메시지
		$err = $stmt->errorInfo();
		if(isset($err[2])){
			print_r($err);
			return -1;
		}
		return $stmt->rowCount();

	}

	/* 
	 *	Author: 안윤근 
	 *	@Params
	 *	string table: table이름
	 *	string/array clauses: where, order by 등의 조건절 들
	 *
	 *	@Return
	 *	OnSuccess: Delete문에 의해 영향받은 레코드 갯수 (>=0)
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	DELETE FROM table clauses 쿼리를 실행합니다.
	 *	이미 delete라는 함수명이 php에 존재하기 때문에 deletes라는 함수명을 사용합니다.
	 * 
	 *	clauses가 만약,
	 *	key=>value array로 들어온 경우,
	 *	DELETE FROM table key0 value0 key1 value1 ...
	 *	쿼리를 실행합니다.
	 * 
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 */
	function deletes($table, $clauses){
		assert(gettype($table)=="string");
		assert(gettype($clauses)=="array" || gettype($clauses)=="string");

		global $pdo;

		//prepare statement build
		$prepare = "DELETE FROM ".$table." ";
		$prepare.=clauseBuild($clauses);
		//print("<br/>".$prepare."<br/>");
		$stmt = rawQuery($prepare);
		return $stmt->rowCount();
	}


	/* 
	 *	Author: 안윤근 
	 *	@Params
	 *	string table: table이름
	 *	string/array clauses: where, order by 등의 조건절 들
	 *
	 *	@Return
	 *	OnSuccess: 검색된 레코드 갯수
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	SELECT count(*) FROM table clauses 쿼리를 실행합니다.
	 *	이미 count라는 함수가 php에 존재하기 때문에 counts라는 함수명을 사용합니다.
	 * 
	 *	clauses가 만약,
	 *	key=>value array로 들어온 경우,
	 *	SELECT count(*) FROM table key0 value0 key1 value1 ...
	 *	쿼리를 실행합니다.
	 * 
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 */
	function counts($table, $clauses){
		assert(gettype($table)=="string");
		assert(gettype($clauses)=="array" || gettype($clauses)=="string");
		return selectOne($table,"count(*)",$clauses);
	}

	/* 
	 *	Author: 안윤근 
	 *	@Params
	 *	string table: table이름
	 *	string column: column이름
	 *	string/array clauses: where, order by 등의 조건절 들
	 *
	 *	@Return
	 *	OnSuccess: 조건에 부합하는 레코드들의 합
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	SELECT sum(*) FROM table clauses 쿼리를 실행합니다.
	 *
	 *	clauses가 만약,
	 *	key=>value array로 들어온 경우,
	 *	SELECT sum(*) FROM table key0 value0 key1 value1 ...
	 *	쿼리를 실행합니다.
	 * 
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 */
	function sum($table, $column, $clauses){
		assert(gettype($table)=="string");
		assert(gettype($column)=="string");
		assert(gettype($clauses)=="array" || gettype($clauses)=="string");
		return selectOne($table,"sum(".$column.")",$clauses);
	}

	/* 
	 *	Author: 안윤근 
	 *	@Params
	 *	string table: table이름
	 *	string column: column이름
	 *	string/array clauses: where, order by 등의 조건절 들
	 *
	 *	@Return
	 *	OnSuccess: 조건에 부합하는 레코드들의 평균값
	 *	OnFailure: -1
	 *
	 *	@Description
	 *	SELECT avg(column) FROM table clauses 쿼리를 실행합니다.
	 * 
	 *	clauses가 만약,
	 *	key=>value array로 들어온 경우,
	 *	SELECT avg(column) FROM table key0 value0 key1 value1 ...
	 *	쿼리를 실행합니다.
	 * 
	 *	쿼리 오류시 디버깅용 오류메시지를 출력합니다.
	 */
	function avg($table, $column, $clauses){
		assert(gettype($table)=="string");
		assert(gettype($column)=="string");
		assert(gettype($clauses)=="array" || gettype($clauses)=="string");
		return selectOne($table,"avg(".$column.")",$clauses);
	}

	/* 
	 *	@Params
	 *	password: 패스워드 해시하고자 하는 문자열
	 *	@Return
	 *	패스워드 해시 값
	 *	@Description
	 *	패스워드를 받아서 패스워드 해시값을 리턴합니다.
	 *	패스워드 해시가 필요한 경우 쿼리에서 PASSWORD()쓰지 말고 이 함수를 써주세요.
	 */
	function pwd($password){
		$query = "select password(".quote($password).")";
		$stmt = rawQuery($query);
		$result = $stmt->fetchAll();
		return $result[0][0];
	}

	/*
	 *	@Params
	 *	param: 쿼팅 하고자 하는 문자열
	 *	@Return
	 *	'param'
	 *	@Description
	 *	PDO의 quote함수를 수행합니다. 쿼리 파라미터에 ' ' 직접넣지말고 이 함수를 써주세요.
	 */
	function quote($param){
		global $pdo;
		return $pdo->quote($param);
	}

	/*
	 *	Author: 안윤근
	 *	@Params
	 *	param: 쿼리문 문자열
	 *	@Return
	 *	statement 오브젝트.
	 *	@Description
	 *	raw query를 수행합니다. 수행결과를 오브젝트로 리턴합니다.
	 */
	function rawQuery($query){
		global $pdo;
		$stmt = $pdo->prepare($query);
		$stmt->execute();
		//디버깅용 오류메시지
		$err = $stmt->errorInfo();
		if(isset($err[2])){
			print_r($err);
			return -1;
		}
		return $stmt;
	}

	/* 
	 *	Author: 안윤근 
	 *	@Params
	 *	string/array clauses: where, order by 등의 조건절 들
	 *	string과 clause=>condition인 연관배열을 받습니다.
	 *	파라미터 예시1: Array("where"=>"id=1 and name LIKE 안윤근","LIMIT"=>3,"ORDER BY"=>"desc")
	 *	파라미터 예시2: "where id=1 and name like 안윤근 limit 3 ..."
	 *
	 *	@Return
	 *	OnSuccess: Parsed clauses
	 *
	 *	@Description
	 *	조건절들을 받아서 문자열로 빌드합니다.
	 *	오로지 db.php 내에서만 사용하기 위한 함수입니다.
	 */
	function clauseBuild($clauses){
		$result = "";
		if(gettype($clauses)=="array"){
			foreach($clauses as $clause => $condition){
				$result.=(" ".$clause." ".$condition);
			}
		}else{
			$result.=(" ".$clauses);
		}
		return $result;
	}


	//테스트코드
	//print("<br/>insert:<br/>");
	//insert("testTB",Array("name","age"),Array("Ahn2",221));
	//print("<br/>selectOne:<br/>");
	//print(selectOne("testTB","name","where age=3"));
	//print("<br/>selectAll:<br/>");
	//print_r(selectAll("testTB","name, num"));
	//print("<br/>update:<br/>");
	//print(update("testTB",Array("name"=>"AAAbbb","age"=>1),Array("where"=>"num=2")));
	//print("<br/>remove:<br/>");
	//print(deletes("testTB","where age=221 limit 2"));
	//print("<br/>counts:<br/>");
	//print(counts("testTB","where age=3"));

?>