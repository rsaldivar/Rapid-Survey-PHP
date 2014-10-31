<?php
class Submission extends model
{
	protected $tbl_project 		= "tbl_project";
	protected $tbl_submission	= 'tbl_submission';
	protected $tbl_comments		= 'tbl_comments';
	protected $tbl_customers	= 'tbl_customers';
	protected $tbl_users 		= "tbl_user";
	
	function insertSubmission($condArr) 
	{
		//$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
        return $this->insert($this->tbl_submission, $condArr);
    }
	
	function updateSubmission($column, $cond) 
    {       
		//$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
        return $this->update($this->tbl_submission, $column, $cond);
    }
	
	function selectAllSubmissions($columns, $condArr, $search) 
	{
        return $this->selectAll($this->tbl_submission, $columns, $condArr, $search);
    }
	
	function deleteSubmissions($ids) 
    {
		if (is_array($ids) && count($ids)) 
        {
            $this->delete($this->tbl_submission,"submission_id in (".implode(",",$ids).")");
        }
    }
	
	function insertComments($condArr) 
	{
		$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
        return $this->insert($this->tbl_comments, $condArr);
    }
	
	function selectAllComments($columns, $condArr, $search) 
	{
        return $this->selectAll($this->tbl_comments, $columns, $condArr, $search);
    }
	
	function updateComments($column, $cond) 
    {       
		$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
        return $this->update($this->tbl_comments, $column, $cond);
    }
	
	/*
	SELECT sub . * , proj.project_name, cust.customer_name, user.user_name
	FROM `tbl_submission` AS sub
	INNER JOIN `tbl_project` AS proj ON proj.project_id = sub.project_id
	INNER JOIN `tbl_customers` AS cust ON cust.customer_id = proj.customer
	INNER JOIN `tbl_user` AS user ON user.user_id = cust.customer_id
	*/
	//MAIL INFORMACION
	function selectAllSubProjCustUser($condItems,$searchitems)
	{
	$tableNameItems = $this->tbl_submission . " as sub";
        $joinColumnsItems = array($this->tbl_project . " as proj",$this->tbl_customers . " as cust",$this->tbl_users . " as user");
        $joinCondItems = array('proj.project_id = sub.project_id','cust.customer_id = proj.customer','user.user_id = cust.customer_id');
		$columnItems ='sub . * , proj.project_name, cust.customer_name, user.user_name';
        $data = $this->selectAllJoin($tableNameItems, $columnItems, $joinColumnsItems, $joinCondItems, $condItems, $searchitems);
        return $data; 
	}
}
?>