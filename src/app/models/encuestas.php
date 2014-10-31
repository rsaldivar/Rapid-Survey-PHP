<?php
class Encuestas extends model
{
	protected $tbl_encuestas 		= "encuestas";
	protected $tbl_users 			= "usuarios";
	protected $tbl_tokens 			= "usuario_tokens";
	
	function insertEncuesta($condArr) 
	{
		$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
        return $this->insert($this->tbl_encuestas, $condArr);
	}
	
	function insertTokens($condArr) 
	{
		$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
        return $this->insert($this->tbl_tokens, $condArr);
	}
	
	
	function updateEncuesta($column, $cond) 
	{       
		$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
        return $this->update($this->tbl_encuestas, $column, $cond);
	}
	
	function selectAllEncuesta($columns, $condArr, $search) 
	{
        return $this->selectAll($this->tbl_encuestas, $columns, $condArr, $search);
	} 
	
	function selectAllTokens($columns, $condArr, $search) 
	{
        return $this->selectAll($this->tbl_tokens, $columns, $condArr, $search);
	}
	
	function updateToken($column, $cond) 
	{       
		$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
        return $this->update($this->tbl_tokens, $column, $cond);
	}
	
	
	function selectAllEncuestasCliente($columns,$searchitems)
	{
		$tableNameItems = $this->tbl_encuestas ;
		$joinColumnsItems = array($this->tbl_users );
		$joinCondItems = array('usuarios.id = 1');
		$columnItems =$columns;
		$condItems ='';
		$data = $this->selectAllJoin($tableNameItems, $columnItems, $joinColumnsItems, $joinCondItems, $condItems, $searchitems);
        return $data; 
	}	
	
	function deleteEncuesta($ids) 
	{
		if (is_array($ids) && count($ids)) 
		{
			$this->delete($this->tbl_encuestas," encuestas.id in (".implode(",",$ids).")");
		}
    }
}
?>