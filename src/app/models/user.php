<?php
class user extends model
{
	protected $tbl_users 			= "usuarios";
	
	function selectUser($columns, $condArr, $search) 
	{
        return $this->select($this->tbl_users, $columns, $condArr, $search);
    }

    function selectAllUser($columns, $condArr, $search) 
	{
        return $this->selectAll($this->tbl_users, $columns, $condArr, $search);
    }
    
	function insertUser($condArr) 
	{
		$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
        return $this->insert($this->tbl_users, $condArr);
    }
	
	function updateUser($column, $cond) 
    {       
		$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
        return $this->update($this->tbl_users, $column, $cond);
    }
	
	
	function deleteuser($ids) 
	{
		if (is_array($ids) && count($ids)) 
		{
			//SE BORRAN PRIMERO LAS RESPUESTAS
			$this->delete($this->tbl_users," id in (".implode(",",$ids).")");
		}
	}
	
}
?>
