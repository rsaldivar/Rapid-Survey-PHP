<?php
class Question extends model
{
	protected $tbl_question			= "preguntas";
	protected $tbl_sub_labels		= "sub_labels";
	protected $tbl_sub_preguntas	= "sub_preguntas";

	protected $tbl_users		= "usuarios";
	
	function insertQuestion($condArr) 
	{
		//$condArr = array_map('strip_tags',$condArr);
		$condArr = array_map('mysql_real_escape_string',$condArr);
		return $this->insert($this->tbl_question, $condArr);
	}
	
	function updateQuestion($column, $cond) 
	{       
		//$column = array_map('strip_tags',$column);
		$column = array_map('mysql_real_escape_string',$column);
		return $this->update($this->tbl_question, $column, $cond);
	}
	
	function selectAllQuestions($columns, $condArr, $search) 
	{
		return $this->selectAll($this->tbl_question, $columns, $condArr, $search);
	}
	
	
	
	function deleteQuestions($ids) 
	{
		if (is_array($ids) && count($ids)) 
		{
			$this->delete($this->tbl_question," preguntas.id in (".implode(",",$ids).")");
		}
	}
	function deleteSubQuestionAndSubLabels($idQuestion) 
	{
		$this->delete($this->tbl_sub_preguntas," pregunta_id = ". $idQuestion);
		$this->delete($this->tbl_sub_labels," pregunta_id = ". $idQuestion);
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
	
}
?>