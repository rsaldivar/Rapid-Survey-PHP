<?php
class Customer extends model
{
	protected $tbl_customers = "tbl_customers";
	protected $tbl_subcustomers = "tbl_subcustomers";
	protected $tbl_user = "tbl_user";
	
	function insertCustomer($condArr) 
	{
		$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
		return $this->insert($this->tbl_customers, $condArr);
	}
	function insertSubCustomer($condArr) 
	{
		$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
		return $this->insert($this->tbl_subcustomers, $condArr);
	}
	function updateCustomer($column, $cond) 
	{       
		$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
		return $this->update($this->tbl_customers, $column, $cond);
	}
	function updateSubCustomer($column, $cond) 
	{       
		$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
		return $this->update($this->tbl_subcustomers, $column, $cond);
	}
	
	function selectCustomer($columns, $condArr, $search) 
	{
		return $this->select($this->tbl_customers, $columns, $condArr, $search);
	}
	function selectSubCustomer($columns, $condArr, $search) 
	{
		return $this->select($this->tbl_subcustomers, $columns, $condArr, $search);
	}

	function selectAllCustomer($columns, $condArr, $search) 
	{
		return $this->selectAll($this->tbl_customers, $columns, $condArr, $search);
	}
	function selectAllSubCustomer($columns, $condArr, $search) 
	{
		return $this->selectAll($this->tbl_subcustomers, $columns, $condArr, $search);
	}
	
	function selectAllCustomerJoin($searchitems)
	{
		$tableNameItems = $this->tbl_user . " as tu";
		$joinColumnsItems = array($this->tbl_customers . " as tc");
		$joinCondItems = array('tu.user_id = tc.customer_id');
		$columnItems ='*';
		$condItems ='';
		$data = $this->selectAllJoin($tableNameItems, $columnItems, $joinColumnsItems, $joinCondItems, $condItems, $searchitems);
		return $data; 
	}
	
	
	function selectAllSubCustomerJoin($searchitems)
	{
		$tableNameItems = $this->tbl_user . " as tu";
		$joinColumnsItems = array($this->tbl_customers . " as tc");
		$joinCondItems = array('tu.user_id = tc.customer_id');
		$columnItems ='*';
		$condItems ='';
		$data = $this->selectAllJoin($tableNameItems, $columnItems, $joinColumnsItems, $joinCondItems, $condItems, $searchitems);
		return $data; 
	}
	
	
	function deleteCustomer($ids) 
	{
		if (is_array($ids) && count($ids)) 
		{
		    $this->delete($this->tbl_customers,"customer_id in (".implode(",",$ids).")");
		}
    }
}
?>