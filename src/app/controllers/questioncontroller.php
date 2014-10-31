<?php  
class QuestionController extends controller  
{
	var $helpers=array('paginator','simpleimage');		
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('question/index');
		
		//die('User related functions coming soon.');
		$this->wtRedirect('customer/surveys');
	}
	
	
	function listing()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('question/listing'); 
		
		$args = func_get_args();
		$survey_id = $args[0];
		
		
		
		//ENVIAR POST AJAX ORDEN DE LAS COLUMNAS
		if (isset($_POST['orders'])) {
			$orders = explode('&', $_POST['orders']);
			$array = array();
			foreach($orders as $item) {
				$item = explode('=', $item);
				$item = explode('_', $item[1]);
				$array[] = $item[1];
			}
			$this->pr($array);
			foreach($array as $ordenNuevo => $idpregunta) {
				$ordenNuevo = $ordenNuevo + 1;
				$columns=array();
				$columns['prioridad']=$ordenNuevo;
				$this->model('question')->updateQuestion($columns,' id = '.$idpregunta);
				echo "id = ".$idpregunta."   order nuevo =".$ordenNuevo."<br/>\n";
			}
		}//FIN DE ORDENAMIENTO

		//ENVIAR POST ACCIONES DEL FOOTER TABLE
		if ($_POST["frmSubmit"] == "yes") 
		{
			//Implementing footer action  
			if($_POST['footerAction']=='Activate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['estado']='activada';
				$this->model('question')->updateQuestion($columnA,' preguntas.id in('.implode(',',$_POST["ids"]).')');
				$columnsArr = array();
				$cond = ' id = "'.$survey_id.'"';
				$columnsArr['difusion']   	= "inactiva";
				$this->model('encuestas')->updateEncuesta($columnsArr,$cond);
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been activate successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Deactivate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['estado']='desactivada';
				$this->model('question')->updateQuestion($columnA,' preguntas.id in('.implode(',',$_POST["ids"]).')');
				$columnsArr = array();
				$cond = ' id = "'.$survey_id.'"';
				$columnsArr['difusion']   	= "inactiva";
				$this->model('encuestas')->updateEncuesta($columnsArr,$cond);
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deactivate successfully.','alert-success');
			}
			
			elseif($_POST['footerAction']=='Delete' && count($_POST['ids'])>=1)
			{
				$this->model('question')-> deleteQuestions($_POST['ids']);
				$columnsArr = array();
				$cond = ' id = "'.$survey_id.'"';
				$columnsArr['difusion']   	= "inactiva";
				$this->model('encuestas')->updateEncuesta($columnsArr,$cond);
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deleted successfully.','alert-success');
				//ENVIARCORREO SUBMISSION CONTROLLER -> NUEVO SUBMISSIOn
				
			}
			//End
        }

		// ALTA DE UNA PREGUNTA
		if ($_POST["frmSubmitUp"] == "yes")
		{
			$columnsArr = array();
			$columnsArr['grupo_id']   			= $survey_id;
			$columnsArr['titulo']   			= $_POST['titulo'];
			$columnsArr['mensaje_ayuda']   		= $_POST['mensaje_ayuda'];
			$columnsArr['dimension']   			= $_POST['dimension'];
			$columnsArr['tipo']   				= $_POST['tipo'];
			$columnsArr['tipo_descripcion']		= $_POST['tipo_descripcion'];
			$columnsArr['prioridad']   			= $_POST['prioridad'];
			$columnsArr['class'] 				= $_POST['class'];
			$columnsArr['mensaje_validacion']	= $_POST['mensaje_validacion'];
			$columnsArr['min'] 					= $_POST['min'];
			$columnsArr['max'] 					= $_POST['max'];
			
			$this->model('question')->insertQuestion($columnsArr);
			$id = mysql_insert_id();
				if( $_POST["dimension"] == "array" || $_POST["dimension"] == "matriz" )
				{
					foreach ($_POST["pregunta"] as $valor) {
						$QUERY = 'INSERT INTO sub_preguntas (titulo,pregunta_id)  VALUES ("'.$valor.'",'.$id.')';
						mysql_query($QUERY);
					}
					foreach ($_POST["label"] as $valor) {
						$QUERY = 'INSERT INTO sub_labels (titulo,pregunta_id)  VALUES ("'.$valor.'",'.$id.')';
						mysql_query($QUERY);
					}
				}
			
			//DESACTIVAR LA DIFUSION DE LA ENCUESTA
			$columnsArr = array();
			$cond = ' id = "'.$survey_id.'"';
			$columnsArr['difusion']   	= "inactiva";
			$this->model('encuestas')->updateEncuesta($columnsArr,$cond);

			//ELIMINAR RESULTADOS DE LA ENCUESTA 
			$this->model('results')->deleteResultsEncuesta($survey_id);
			$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Question inserted successfully.".$link,'alert-success');
			$this->wtRedirect('question/listing/'.$survey_id);
				
		}

		
		$searchitems = " AND grupo_id = ".$survey_id . " ORDER BY prioridad" ;
		//$searchitems .= " AND usuarios.id = ".$_SESSION["SESS_USER_ID"];
		$Total= $this->model('question')->selectAllQuestions('*', array(), $searchitems);
		//Paging start here
		$pages = new Paginator;
		$pages->items_total = count($Total);
		$pages->mid_range = 10;
		$pages->default_ipp =10;
		$pages->paginate();
		$this->tempvars["PAGING"] = $pages->display_pages();
		if (count($Total) > 10) 
		{
			$searchitems.= $pages->limit;
		}
		//Paging end here
		$this->tempVars['QuestionList'] = $this->model('question')->selectAllQuestions('*', array(), $searchitems);
		$this->tempVars['SurveyId'] = $survey_id;
		$this->view('pregunta/list');
	}	
	
	
	// EDICION DE PREGUNTA
	function edit()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('question/edit');
		
		$args = func_get_args();
		$question_id = $args[0];
		
		
		// BOTON DE SUBMIT EDITAR CLICKEADO
		// EN ESTA SECCION DE CODIGO SE ACTUALIZARA LA INFORMACION DE LA QUESTION,
		// SI ES DE TIPO ARRAY SE ELIMINARAN  Y CREARAN DE NUEVO LAS SUB_LABELS Y SUB_PREGUNTAS
		// SI NO, SE ELIMINARAN PARA MANTERNER EL MENOR NUMERO DE REGISTROS EN LA BD
		if($_POST['submission_form_edit'] == 'yes')
		{
			$columnsArr = array();
			$columnsArr['titulo']   			= $_POST['titulo'];
			$columnsArr['mensaje_ayuda']   		= $_POST['mensaje_ayuda'];
			$columnsArr['dimension']   			= $_POST['dimension'];
			$columnsArr['tipo']   				= $_POST['tipo'];
			$columnsArr['tipo_descripcion'] 	= $_POST['tipo_descripcion'];
			$columnsArr['prioridad']   			= $_POST['prioridad'];
			$columnsArr['class'] 				= $_POST['class'];
			$columnsArr['mensaje_validacion']	= $_POST['mensaje_validacion'];
			$columnsArr['min'] 					= $_POST['min'];
			$columnsArr['max'] 					= $_POST['max'];
			
			$cond = ' preguntas.id = "'.$question_id.'"';//ACTUALIZACION DE PREGUNTA
			$this->model('question')->updateQuestion($columnsArr,$cond);
			
			//ACTUALIZACION DE SUB_PREGUNTAS Y SUB_LABELS
			
			$this->model('question')->deleteSubQuestionAndSubLabels($question_id);//ELIMINACION DE SUBLABELS Y SUBPREGUNTAS

			if( $_POST["dimension"] == "array"  || $_POST["dimension"] == "matriz")
			{
				foreach ($_POST["pregunta"] as $valor) {
					$QUERY = 'INSERT INTO sub_preguntas (titulo,pregunta_id)  VALUES ("'.$valor.'",'.$question_id.')';
					mysql_query($QUERY);
				}
				foreach ($_POST["label"] as $valor) {
					$QUERY = 'INSERT INTO sub_labels (titulo,pregunta_id)  VALUES ("'.$valor.'",'.$question_id.')';
						mysql_query($QUERY);
				}
			}
			
			// OBTENER EL ID DE LA ENCUESTA DE LA PREGUNA A MODIFICAR
			$sql = 'select encuesta_id from grupos join preguntas on preguntas.grupo_id = grupos.id where preguntas.id = '.$question_id;
			$result = mysql_query($sql);

			$survey_id = mysql_result($result, 0);

			// CAMBIAR LA DIFUSION DE LA ENCUESTA A INACTIVA
			$columnsArr = array();
			$cond = ' id = "'.$survey_id.'"';
			$columnsArr['difusion']   	= "inactiva";
			$this->model('encuestas')->updateEncuesta($columnsArr,$cond);
			
			$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Question update successfully.".$link,'alert-success');
			$this->wtRedirect('question/listing/'.$survey_id);
			
		}
		
		//OBTENCION DE LAS SUB_PREGUNTAS DE LA QUESTION A EDITAR
		$searchitems = " AND id = ".$question_id;
		$this->tempVars['question_list'] = $this->model('question')->selectAllQuestions('*', array(), $searchitems);
		$this->tempVars['survey_id'] = $question_id;
		
		$sql = 'select sub_preguntas.* from preguntas join sub_preguntas on sub_preguntas.pregunta_id = preguntas.id where true and preguntas.id = '.$question_id;
		$result = mysql_query($sql);
		$this->tempVars['sub_preguntas']= array();
		$this->tempVars['sub_preguntas_count'] = 0;
		while ($fila = mysql_fetch_object($result)) {
			$this->tempVars['sub_preguntas'][$this->tempVars['sub_preguntas_count']]=$fila->titulo;
			$this->tempVars['sub_preguntas_count']++;
		}
		
		//OBTENCION DE LAS SUB_LABELS DE LA QUESTION A  EDITAR
		$sql = 'select sub_labels.* from preguntas join sub_labels on sub_labels.pregunta_id = preguntas.id where true and preguntas.id = '.$question_id;
		$result = mysql_query($sql);
		$this->tempVars['sub_labels']= array();
		$this->tempVars['sub_labels_count']= 0;
		while ($fila = mysql_fetch_object($result)) {
			$this->tempVars['sub_labels'][$this->tempVars['sub_labels_count']]=$fila->titulo;
			$this->tempVars['sub_labels_count']++;
		}
		
		$this->view('pregunta/edit');
	}	
	
	
	
	function findexts($filename) 
  	{ 
	 $filename = strtolower($filename) ; 
	 $exts = explode('.',$filename) ; 
	 $n = count($exts)-1; 
	 $exts = $exts[$n]; 
	 return $exts; 
	}
	
}
