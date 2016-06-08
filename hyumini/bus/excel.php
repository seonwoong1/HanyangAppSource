<?php

	/* 
	 * @Authorship
	 * Origin	: http://phpexcel.codeplex.com/
	 * Bring	: 성다혜
	 * ReWrite	: 안윤근
	 * 
	 * @Description
	 * PHP에서 Excel파일을 로드하여 파싱하는 모듈입니다.
	 * 인터페이스로 활용할 수 있게끔 기능별로 함수화 해놓았습니다.
	 *
	 */

	//PHPExcel.php, IOFactory.php are Required.
	require_once("./Classes/PHPExcel.php"); 
	require_once("./Classes/PHPExcel/IOFactory.php");

	function getValue($cell){
		return $cell->getCalculatedValue();
	}

	function getTimeValue($cell){
		$time = PHPExcel_Style_NumberFormat::toFormattedString(getValue($cell), 'hh:mm');
		return $time;
	}

	function getExcel($filename){
		try{
			//$phpExcel = new PHPExcel();
			$reader = PHPExcel_IOFactory::createReaderForFile($filename);
			$reader->setReadDataOnly(true);
			$excel = $reader->load($filename);
		}catch (exception $e) {
			return -1;
		}
		return $excel;
	}

	function getSheet($sheetname, $excel){
		try{
			$sheet = $excel->getSheetByName($sheetname);
		}catch (exception $e) {
			return -1;
		}
		return $sheet;
	}

	function getTable($sheet){
		$table = Array();
		try {
			$first = true;
			$columns = Array();
			//echo "Sheet: ";
			//print_r($sheet);
			$rows = $sheet->getRowIterator();
			foreach ($rows as $row) {
				$cells = $row->getCellIterator();
				$cells->setIterateOnlyExistingCells(true);
				//컬럼 가져오기
				if($first){
					$first = false;
					foreach ($cells as $cell) {
						$column = getValue($cell);
						array_push($columns, $column);
						$table[$column]=Array();
					}
					continue;
				}
				//컬럼별로 저장하기
				$cnt = 0;
				$mod = count($columns);
				foreach ($cells as $cell) {
					$value = getTimeValue($cell);
					$column = $columns[$cnt];
					array_push($table[$column], $value);
					$cnt++;
					$cnt = $cnt % $mod;
				}
			}
		} catch (exception $e) {
			return -1;
		}
		if(count($table)==0){
			return -1;
		}
		return $table;
	}

	function getCellsInColumn($column, $sheet){
		$table = getTable($sheet);
		if(gettype($table)=="integer" && $table==-1){
			return -1;
		}else if($table==null){
			return -1;
		}
		if(!isset($table[$column])){
			return -1;
		}
		return $table[$column];
	}

	
?>