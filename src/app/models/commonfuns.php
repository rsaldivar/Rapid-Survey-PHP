<?php
class CommonFuns extends model
{
	protected $tbl_user = "usuarios";
	
	function CheckExistingCustomerEmail($columns, $condArr, $search='') 
	{
        return $this->selectAll($this->tbl_user, $columns, $condArr, $search);
    }
	
	function CheckExistingproject($columns, $condArr, $search='') 
	{
        return $this->selectAll($this->tbl_project, $columns, $condArr, $search);
    }
	
}
?>