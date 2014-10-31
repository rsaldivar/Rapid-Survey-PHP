<?php
class model extends db{
	protected $_model;
	function __construct(){
		parent:: __construct(DB_NAME,DB_HOST,DB_USERNAME,DB_PASSWORD);
		$this->_model = get_class($this);
		
//		$this->_table = strtolower($this->_model)."s";
	}
	function select($tabName,$column, $cond) {
		$condStrArr = array();;
                
		if(is_array($cond) && count($cond)){
                    
			foreach($cond as $ind=>$val) {
				$condStrArr[] = "$ind='".$val."'";
			}
		}
                
                
		$condStr = implode(" and ", $condStrArr);
                if(count($condStrArr)){
                    $condStr="and ".$condStr;  
		}
                
		$selectString = "select $column from ".$tabName." where 1=1 $condStr";
		$rs = $this->query($selectString);
		return $this->fetchNextObject($rs);
	}
	function customQuery($queryvar,$noreturn=1) {
		$rs = $this->query($queryvar);
		if($noreturn) {
		return $this->fetchNextObject($rs);	
		}
	}
	function selectAll($tabName,$column, $cond, $search="",$dispalyQuery=-1) {
		$returnArr = array();
		$condStrArr = array();;
		if(is_array($cond) && count($cond)){
			foreach($cond as $ind=>$val) {
				$condStrArr[] = "$ind='".$val."'";
			}
		}
                
		$condStr = implode(" and ", $condStrArr);
		if(count($condStrArr)){
                    $condStr="and ".$condStr;  
		}
		$selectString = "select $column from ".$tabName." where 1=1  $condStr $search";
		
		//if($dispalyQuery) {
		//	$rs = $this->query($selectString);
		//} else {
			$rs = $this->query($selectString,$dispalyQuery);
		//}

		while($row = $this->fetchNextObject($rs)) {
			$returnArr[] = $row;
		}
		return $returnArr;
	}
	function update($tabName,$column,$cond,$additional=""){
		$columnStrArr = array();
		if(is_array($column) && count($column)){
			foreach($column as $ind=>$val) {
				$columnStrArr[] = "$ind='".mysql_real_escape_string(stripslashes($val))."'";
			}
		}
		$columnStr = implode(",", $columnStrArr);
			if(trim($additional)) {
			$selectString = "UPDATE ".$tabName." SET ".$columnStr." , $additional where $cond";
			} else {
			$selectString = "UPDATE ".$tabName." SET ".$columnStr." where $cond";
			}
			$this->execute($selectString);
	}
	function delete($tabName,$cond){
		$selectString = "delete from ".$tabName." where $cond";
		$rs = $this->query($selectString);
	
	}
	function insert($tabName,$column,$additional=""){
		$columnStrArr = array();
		if(is_array($column) && count($column)){
			foreach($column as $ind=>$val) {
				if(strtoupper($val)=='NOW()' || strtoupper($val)=='CURDATE()'){
					$columnStrArr[] = "$ind=".mysql_real_escape_string(stripslashes($val));
				}else{
					$columnStrArr[] = "$ind='".mysql_real_escape_string(stripslashes($val))."'";
				}
			}
		}
		$columnStr = implode(",", $columnStrArr);
			if(trim($additional)) {
			$selectString = "INSERT INTO ".$tabName." SET ".$columnStr." , $additional";
			} else {
			$selectString = "INSERT INTO ".$tabName." SET ".$columnStr;
			}
			
		$rs = $this->query($selectString);
		return $this->lastInsertedId();
	}
	function insertDate($tabName,$column,$date){
		$columnStrArr = array();
		if(is_array($column) && count($column)){
			foreach($column as $ind=>$val) {
				$columnStrArr[] = "$ind='".$val."'";
			}
		}
		$columnStr = implode(",", $columnStrArr);
		$selectString = "INSERT INTO ".$tabName." SET ".$columnStr.",".$date."=NOW()";
		$rs = $this->query($selectString);
		return $rs;
	}
	function selectAllJoin($tableName,$column,$joinColumns=array(),$joinCond=array(), $cond=array(), $search="",$joinType=array(),$debug=-1) {
		$innerJoinArr = array();
		for($i=0;$i < count($joinColumns);$i++) {
			if($joinType[$i]) {
			$innerJoinArr[] = " ".$joinType[$i]." join ".$joinColumns[$i]. " on ".$joinCond[$i];			
			} else {
			$innerJoinArr[] = " inner join ".$joinColumns[$i]. " on ".$joinCond[$i];
			}
		}
		$innerJoin = implode(" ",$innerJoinArr);
	
		$condStrArr = array();
		
		
		if(is_array($cond) && count($cond)){
			foreach($cond as $ind=>$val) {
				$condStrArr[] = "$ind='".$val."'";
			}
		}
		$condStr = implode(" and ", $condStrArr);
		
		if($condStr) {
			$condStr = " and ".$condStr;
		}


		$selectString = "select $column from $tableName $innerJoin where 1=1 $condStr $search";

		$rs = $this->query($selectString,$debug);
		while($row = $this->fetchNextObject($rs)) {
			$returnArr[] = $row;
		}
		return $returnArr;
	}
	
}