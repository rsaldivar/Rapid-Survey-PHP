<?php
class generador extends model
{
	protected $tbl_usuarios 			= "usuarios";
	protected $tbl_encuestas			= "encuestas";
	protected $tbl_preguntas			= "preguntas";
	protected $tbl_sub_preguntas			= "sub_preguntas";
	protected $tbl_sub_labels			= "sub_labels";
	
	
	public function Preguntas($ENCUESTA,$GRUPO){
		$sql_quesions = "select pregunta.* from preguntas as pregunta
		inner join grupos ON grupos.id = pregunta.grupo_id
		inner join encuestas ON encuestas.id  = grupos.id
		where encuestas.id =  ".$ENCUESTA."  AND grupos.id =  ".$GRUPO. " ORDER BY prioridad";
		//Query Questions
		$req_questions = mysql_query($sql_quesions);
		$numero_questions = mysql_num_rows($req_questions);
		$questions = array();
		while($result = mysql_fetch_object($req_questions)){ $questions[] = $result;} 
		return $questions;
	}

	public function subPreguntas($ENCUESTA,$GRUPO,$PREGUNTA){
		$sql_preguntas = "select preguntas.id , preguntas.titulo,  preguntas.tipo, sub_preguntas.id as idSub , sub_preguntas.titulo 
		from encuestas 
		inner join grupos
			ON grupos.encuesta_id = encuestas.id
		inner join preguntas 
			ON preguntas.grupo_id =  grupos.id
		inner join sub_preguntas
			ON sub_preguntas.pregunta_id = preguntas.id
		where encuestas.id = ".$ENCUESTA." AND grupos.id = ".$GRUPO." AND preguntas.id = ".$PREGUNTA;
		$req_preguntas = mysql_query($sql_preguntas);
		$numero_preguntas = mysql_num_rows($req_preguntas);
		$SUB_PREGUNTAS = array();
		while($result = mysql_fetch_object($req_preguntas)){  $SUB_PREGUNTAS[] = $result; }
		return $SUB_PREGUNTAS;
	}
	public function subLabels($ENCUESTA,$GRUPO,$PREGUNTA){
		$sql_labels = "select preguntas.id , preguntas.titulo,  preguntas.tipo, sub_labels.titulo, sub_labels.id as idLabel, sub_labels.titulo as tituloLabel
		from encuestas 
		inner join grupos
			ON grupos.encuesta_id = encuestas.id
		inner join preguntas 
			ON preguntas.grupo_id =  grupos.id
		inner join sub_labels
			ON sub_labels.pregunta_id = preguntas.id
		where encuestas.id = ".$ENCUESTA." AND grupos.id = ".$GRUPO." AND preguntas.id = ".$PREGUNTA;
		$req_labels = mysql_query($sql_labels);
		$numero_labels = mysql_num_rows($req_labels);
		$sub_respuestas = array();
		while($resultLabel = mysql_fetch_object($req_labels)){ $sub_respuestas[] = $resultLabel;}
		return $sub_respuestas;
	}
	
}
?>
