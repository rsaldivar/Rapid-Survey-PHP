<?php
class results extends model
{
	protected $tbl_usuarios 			= "usuarios";
	protected $tbl_usuarios_respuestas	= "usuario_respuestas";
	protected $tbl_encuestas			= "encuestas";
	protected $tbl_preguntas			= "preguntas";
	protected $tbl_sub_preguntas		= "sub_preguntas";
	protected $tbl_sub_labels			= "sub_labels";
	
	function selectAllResultadosUsuario($searchitems)
	{
		 $SQL =" select usuario_respuestas.* , usuario_respuestas.id as idRespuesta, usuario_respuestas.fecha as fechaRespuesta , usuarios.* , encuestas.titulo as titulo
			from usuario_respuestas
			inner join encuestas  	on encuestas.id = usuario_respuestas.encuesta_id
			left join usuarios 	on usuario_respuestas.respondente_id = usuarios.id
			WHERE 1=1 " .$searchitems ;
		$RESULT = mysql_query($SQL);
		while ( $obj = mysql_fetch_object($RESULT))
		{  $data[] = $obj;
		}
		return $data; 
	}

	 function Preguntas($ENCUESTA,$GRUPO){
		$sql_quesions = "select pregunta.id , pregunta.titulo , pregunta.dimension , pregunta.estado , pregunta.tipo, pregunta.class
		from preguntas as pregunta
		inner join grupos ON grupos.id = pregunta.grupo_id
		inner join encuestas ON encuestas.id  = grupos.id
		where encuestas.id =  ".$ENCUESTA."  AND grupos.id =  ".$GRUPO;
		//Query Questions
		$req_questions = mysql_query($sql_quesions);
		$numero_questions = mysql_num_rows($req_questions);
		$questions = array();
		while($result = mysql_fetch_object($req_questions)){ $questions[] = $result;} 
		return $questions;
	}
	function subPreguntas($ENCUESTA,$GRUPO,$PREGUNTA){
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
	function subLabels($ENCUESTA,$GRUPO,$PREGUNTA){
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
	
	function valueSimple($RESPUESTA,$PREGUNTA){
	  //OBTENCION DE RESULTADO
	 	$SQL = 'SELECT respuestas.resultado, count(respuestas.resultado) as cantidad 
			FROM encuestas
			INNER JOIN usuario_respuestas
				ON usuario_respuestas.encuesta_id = encuestas.id
			INNER JOIN preguntas
				ON preguntas.grupo_id = encuestas.id
			INNER JOIN respuestas
				ON respuestas.pregunta_id = preguntas.id
			where true 
			AND respuestas.folio_respuesta =  usuario_respuestas.id ';
		if($RESPUESTA != NULL)$SQL .= ' AND usuario_respuestas.id = '.$RESPUESTA;
		$SQL .= ' AND preguntas.id =  '.$PREGUNTA.'
			GROUP BY (resultado)';
			$RESULT = mysql_query($SQL);
			$ARRAY = array();
			while($I = mysql_fetch_object($RESULT)){ $ARRAY[] = $I;}
		return  $ARRAY;
	}
	
	
	function valueMatriz($RESPUESTA,$PREGUNTA,$FILA,$COLUMN){
		//OBTENCION DE RESULTADO
		$SQL = 'SELECT  sub_respuestas.resultado, count(sub_respuestas.resultado) as cantidad 
			FROM encuestas
			INNER JOIN usuario_respuestas
				ON usuario_respuestas.encuesta_id = encuestas.id
			INNER JOIN preguntas
				ON preguntas.grupo_id = encuestas.id
			LEFT JOIN sub_respuestas
				ON sub_respuestas.pregunta_id = preguntas.id
			where true 
			AND sub_respuestas.folio_respuesta =  usuario_respuestas.id ';
		if($RESPUESTA != NULL)$SQL .= ' AND usuario_respuestas.id = '.$RESPUESTA;
		$SQL .=	' AND preguntas.id =  '.$PREGUNTA.'
			AND sub_pregunta_id = '.$FILA.'
			AND sub_respuesta_id = '.$COLUMN.'
			GROUP BY resultado';
			$RESULT = mysql_query($SQL);
			$ARRAY = array();
			while($I = mysql_fetch_object($RESULT)){ $ARRAY[] = $I;}
		return  $ARRAY;
	}
	
	function deleteResults($ids) 
	{
		if (is_array($ids) && count($ids)) 
		{
			$this->delete($this->tbl_usuarios_respuestas," usuario_respuestas.id in (".implode(",",$ids).")");
		}
	}
	
	function deleteResultsEncuesta($idEncuesta) 
	{
		$this->delete($this->tbl_usuarios_respuestas," encuesta_id =".$idEncuesta);
	
    }
}
?>