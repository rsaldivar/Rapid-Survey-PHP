<?php
class Project extends model
{
	protected $tbl_project 		= "tbl_project";
	protected $tbl_customers	= 'tbl_customers';
	protected $tbl_subcustomers = "tbl_subcustomers";
	protected $tbl_users 		= "tbl_user";
	
	function insertProject($condArr) 
	{
		$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
        return $this->insert($this->tbl_project, $condArr);
    }
	
	function updateProject($column, $cond) 
    {       
		$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
        return $this->update($this->tbl_project, $column, $cond);
    }
	
	 function selectAllProject($columns, $condArr, $search) 
	{
        return $this->selectAll($this->tbl_project, $columns, $condArr, $search);
    }
	
	function selectAllCustomerProjectJoin($columns,$searchitems)
	{
		$tableNameItems = $this->tbl_project . " as pro";
		$joinColumnsItems = array($this->tbl_customers . " as cust",$this->tbl_users . " as user");
		$joinCondItems = array('pro.customer= cust.customer_id','cust.customer_id= user.user_id');
		$columnItems =$columns;
		$condItems ='';
		$data = $this->selectAllJoin($tableNameItems, $columnItems, $joinColumnsItems, $joinCondItems, $condItems, $searchitems);
        return $data; 
	}
	function selectAllSubCustomerProjectJoin($columns,$searchitems)
	{
		$tableNameItems = $this->tbl_project . " as proyecto";
		$joinColumnsItems = array($this->tbl_customers . " as cust ",$this->tbl_subcustomers . " as sub ",$this->tbl_users . " as user ");
		$joinCondItems = array('proyecto.customer= cust.customer_id',' sub.customers_id = cust.customer_id ','cust.customer_id= user.user_id');
		$columnItems =$columns;
		$condItems ='';
		$data = $this->selectAllJoin($tableNameItems, $columnItems, $joinColumnsItems, $joinCondItems, $condItems, $searchitems);
        return $data; 
	}
	//Nueva Funcion para Seleccionar Managers, de un projecto en concreto
	function selectAllManagerProjectJoin($columns,$searchitems)
	{
		$tableNameItems = $this->tbl_project ;
		$joinColumnsItems = array( $this->tbl_users );
		$joinCondItems = array('tbl_project.manager = tbl_user.user_id');
		$columnItems =$columns;
		$condItems ='';
        $data = $this->selectAllJoin($tableNameItems, $columnItems, $joinColumnsItems, $joinCondItems, $condItems, $searchitems);
        return $data; 
	}
	
	
	function deleteProject($ids) 
    {
		if (is_array($ids) && count($ids)) 
        {
            $this->delete($this->tbl_customers,"project_id in (".implode(",",$ids).")");
        }
    }
}
?>