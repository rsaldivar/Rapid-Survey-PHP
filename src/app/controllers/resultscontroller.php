<?php  
class ResultsController extends controller 
{
	var $helpers=array('paginator','simpleimage');		
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('results/index');
		
		$this->wtRedirect('results/listing');
		
	}
	
	function listing()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('results/listing');
		
		$args = func_get_args();
		
		
		if ($_POST["frmSubmit"] == "yes") 
		{
			if($_POST['footerAction']=='Delete' && count($_POST['ids'])>=1)
			{
				$this->model('results')->deleteResults($_POST['ids']);
				//$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deleted successfully.','alert-success');
			}
			//End
        }
        
		$this->tempVars['ID_SURVEY'] = $args[0];
		if(!$this->tempVars['ID_SURVEY'])$this->wtRedirect('home');//VALIDA QUE SE SELECCIONE UNA ENCUESTA VALIDA
		$searchitems = ' AND encuestas.id  = '.$this->tempVars['ID_SURVEY'];
		$this->tempVars['RESULTADOS'] = $this->model('results')->selectAllResultadosUsuario($searchitems);
		$this->view('resultados/resultados');
		
	}	
	
	
	function general()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('results/general');

		$args = func_get_args();
		$ID_ENCUESTA = $args[0]; 
		$ID_GRUPO = $ID_ENCUESTA;
		
		$HTML = $this->headerEncuesta(); //GENERAMOS EL HEADER DE LA ENCUESTA  
		$questions = $this->model('results')->Preguntas($ID_ENCUESTA,$ID_GRUPO); //OBTENEMOS TODAS LAS PREGUNTAS
		
		//GENERACION DE LAS PREGUNTAS******************************************************************************/
		foreach($questions as $pregunta){
			if( $pregunta->estado == "activada" ){
			
				$sub_preguntas = $this->model('results')->subPreguntas($ID_ENCUESTA,$ID_GRUPO,$pregunta->id);
				$sub_respuestas = $this->model('results')->subLabels($ID_ENCUESTA,$ID_GRUPO,$pregunta->id);
				$slq_titulo = " select preguntas.titulo from preguntas where preguntas.id = ".$pregunta->id;
				$titulo = mysql_query($slq_titulo);	$titulo_fetch  = mysql_fetch_array($titulo);
				$slq_help = " select preguntas.mensaje_ayuda from preguntas where preguntas.id = ".$pregunta->id;
				$help = mysql_query($slq_help);		$help_fetch  = mysql_fetch_array($help);
			    
				//GENERACION DE LA TABLA PARA LOS TIPO DE DIMENSION
				if( $pregunta->dimension == "simple")$HTML .= $this->tablaSimple($ID_RESPUESTA, $pregunta->id,$pregunta->tipo,$pregunta->class,$titulo_fetch[0],$help_fetch[0]);
				if( $pregunta->dimension == "array" ||  $pregunta->dimension == "matriz")$HTML .= $this->tablaMatriz($ID_RESPUESTA,$pregunta->id,$pregunta->tipo,$pregunta->class,$sub_preguntas,$sub_respuestas,$titulo_fetch[0],$help_fetch[0]);
			}
		}//FIN FOR EACH x PREGUNTAS
		$this->tempVars['RESULTADO'] = $HTML;
		$this->view('resultados/resultado');
	}	

	function single()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('results/single');

		$args = func_get_args();
		$ID_ENCUESTA = $args[0]; 
		$ID_RESPUESTA  = $args[1]; 
		$ID_GRUPO = $ID_ENCUESTA;
		
		$HTML = $this->headerEncuesta(); //GENERAMOS EL HEADER DE LA ENCUESTA  
		$questions = $this->model('results')->Preguntas($ID_ENCUESTA,$ID_GRUPO); //OBTENEMOS TODAS LAS PREGUNTAS
		
		//GENERACION DE LAS PREGUNTAS******************************************************************************/
		foreach($questions as $pregunta){
			if( $pregunta->estado == "activada" ){
			
				$sub_preguntas = $this->model('results')->subPreguntas($ID_ENCUESTA,$ID_GRUPO,$pregunta->id);
				$sub_respuestas = $this->model('results')->subLabels($ID_ENCUESTA,$ID_GRUPO,$pregunta->id);
				$slq_titulo = " select preguntas.titulo from preguntas where preguntas.id = ".$pregunta->id;
				$titulo = mysql_query($slq_titulo);	$titulo_fetch  = mysql_fetch_array($titulo);
				$slq_help = " select preguntas.mensaje_ayuda from preguntas where preguntas.id = ".$pregunta->id;
				$help = mysql_query($slq_help);		$help_fetch  = mysql_fetch_array($help);
			    
				//GENERACION DE LA TABLA PARA LOS TIPO DE DIMENSION
				if( $pregunta->dimension == "simple")$HTML .= $this->tablaSimple($ID_RESPUESTA, $pregunta->id,$pregunta->tipo,$pregunta->class,$titulo_fetch[0],$help_fetch[0]);
				if( $pregunta->dimension == "array" ||  $pregunta->dimension == "matriz" )$HTML .= $this->tablaMatriz($ID_RESPUESTA,$pregunta->id,$pregunta->tipo,$pregunta->class,$sub_preguntas,$sub_respuestas,$titulo_fetch[0],$help_fetch[0]);
			}
		}//FIN FOR EACH x PREGUNTAS
		$this->tempVars['RESULTADO'] = $HTML;
		$this->view('resultados/resultado');
		
		
	}	

	
	
	function headerEncuesta($ENCUESTA,$RESPONDENTE)
	{
		
		//WIZARD PLUGIN
		/*$HTML .= '<link rel="stylesheet" href="'.SITE_URL.'app/view/js/jquery-steps-master/demo/css/jquery.steps.css">';
		$HTML .= '<link rel="stylesheet" href="'.SITE_URL.'app/view/js/jquery-steps-master/demo/css/main.css">';
		$HTML .= '<script src="'.SITE_URL.'app/view/js/jquery-steps-master/build/jquery.steps.js"></script>';
		//WIZARD PLUGIN MANAGER
	$HTML .=' <script>
				$(function ()
				{
				  $("#wizard").steps({
				      headerTag: "h2",
				      bodyTag: "section",
				      transitionEffect: "slide",
				      stepsOrientation: "vertical",
				      onStepChanging: function (event, currentIndex, newIndex)
				      {
// 					if (currentIndex < newIndex)
// 					{	
// 						return true; //CUANDO CAMBIE ADELANTE TRUE
// 					}
					if (currentIndex > newIndex)
					{	
						return true; //CUANDO CAMBIE ATRAS TRUE
					}
					 // Needed in some cases if the user went back (clean up)
					if (currentIndex < newIndex)
					{
					    // To remove error styles
					    $("#wizard .body:eq(" + newIndex + ") label.error").remove();
					    $("#wizard .body:eq(" + newIndex + ") .error").removeClass("error");
					}
					$("#wizard").validate().settings.ignore = ":disabled,:hidden";
					return $("#wizard").valid();
					  
					  $( ".error" ).mouseover(function() { $("label.error").hide();});
				      },
				      onFinished: function (event, currentIndex)
				      {
					  var form = $(this);
				      }
				  });
				});
			</script>';
		
		*/
		/*$HTML .= '<link href="'.SITE_URL.'app/view/js/jquery-validate/style.css" rel="stylesheet">';
		$HTML .= '<script src="'.SITE_URL.'app/view/js/jquery-validate/assets/js/jquery.validate.min.js"></script>';
		*/
		$HTML .= '<body >'; 
		$HTML .= '<!--[if lt IE 7]>
		    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->';
		
		return $HTML;
	}
	
	
	function tablaSimple($RESPUESTA,$PREGUNTA,$TIPO,$CLASS,$TITULO,$HELP){
		//$HTML .= '<h2>'.$TITULO.'</h2>';
		$HTML .= '<section>';
		$HTML .= '<div class="table-responsive">';
		$HTML .= '<table class="table table-hover preguntaSimple">';
			$HTML .= '<thead>';
				$HTML .= '<tr>';
				$HTML .= '<th>';
				$HTML .= $TITULO;
				$HTML .= '</th>';
				$HTML .='</tr>';
			$HTML .= '</thead>';
			$HTML .= '<tbody>';
			$HTML .= '<tr>';
			$HTML .= '<td>';
			$HTML .= '<select class="form-control">';
			
			$RESULT = $this->model('results')->valueSimple($RESPUESTA,$PREGUNTA);
			foreach( $RESULT as $x){
				$HTML .= '<option>';
				$HTML .= $x->resultado;
				$HTML .= '</option>';
			}
			
			$HTML .=  '</select>';
			$HTML .= '</td>';
			$HTML .= '</tr>';
// 			$HTML .= '<tr class="success"><td colspan="7" >'.$HELP.'</td></tr>';
			$HTML .= '</tbody>';
		$HTML .= '</table>';
		$HTML .= '</div>';
		$HTML .= '</section>';
		RETURN $HTML;
	}
	
	function tablaMatriz($RESPUESTA,$PREGUNTA,$TIPO,$CLASS,$SUB_PREGUNTAS,$SUB_RESPUESTAS,$TITULO,$HELP){
		//$HTML .= '<h2>'.$TITULO.'</h2>';
		$HTML .= '<section>';
		$HTML .= '<div class="table-responsive">';
		$HTML .= '<table class="table table-hover preguntaMultiple">';
			$HTML .= '<thead>';
				$HTML .= '<tr>';
				$HTML .= '<th id="TituloTh" >';
				$HTML .= $TITULO;
				$HTML .= '</th>';
				foreach($SUB_RESPUESTAS as $sub_respuesta){
					if($TIPO == "select" ){
						$opciones = explode(";", $sub_respuesta->tituloLabel);
						$HTML .= "<th>".$opciones[0]."</th>";
					}
					else  $HTML .= "<th>".$sub_respuesta->titulo."</th>";
				}
				$HTML .='</tr>';
			$HTML .= '</thead>';
			$HTML .= '<tbody>';

			foreach($SUB_PREGUNTAS as $sub_pregunta){
				
				$HTML .= '<tr id="'.$sub_pregunta->titulo.'-id-'.$sub_pregunta->idSub.'">';
				if($TIPO == "a_select" || $TIPO == "a_checkbox"  || $TIPO == "a_radio" ){
					$HTML .= '<td></td>';
				}
				else{
					$HTML .= '<td>'.$sub_pregunta->titulo.'</td>';
				}
					
					if(	$TIPO == "a_select" || $TIPO == "a_radio" || $TIPO == "m_radio"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
						$RESULT = array();
						$RESULT =  $this->model('results')->valueMatriz($RESPUESTA,$PREGUNTA,$sub_pregunta->idSub,$sub_respuesta->idLabel);
						$HTML .= '<td>';
						if( $RESPUESTA != 0)
						{
							foreach($RESULT as $x){
								$HTML .= '<input type="radio" ';
								if($x->resultado == "true") $HTML .= ' checked' ;
								$HTML .= ' >';
							}
						}//RESULT USER
						else{
						$HTML .= '<select class="form-control">';
							foreach($RESULT as $x){
								$HTML .= '<option>';
								$HTML .= $x->cantidad;
								$HTML .= '</option>';
							}
						}//RESULTGENERAL
						$HTML .=  '</select>';
						$HTML .= '</td>';
						}
					}
					if( $TIPO == "a_checkbox" || $TIPO == "m_checkbox"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
						$RESULT = array();
						$RESULT =  $this->model('results')->valueMatriz($RESPUESTA,$PREGUNTA,$sub_pregunta->idSub,$sub_respuesta->idLabel);
						$HTML .= '<td>';
						if( $RESPUESTA != 0)
						{
							foreach($RESULT as $x){
								$HTML .= '<input type="checkbox" ';
								if($x->resultado == "true") $HTML .= ' checked' ;
								$HTML .= ' >';
							}
						}//RESULT USER
						else{
						$HTML .= '<select class="form-control">';
							foreach($RESULT as $x){
								$HTML .= '<option>';
								$HTML .= $x->cantidad;
								$HTML .= '</option>';
							}
						$HTML .='</select>';
						}//RESULTGENERAL
						$HTML .= '</td>';
						}
					}
					if( $TIPO == "m_text"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
						$RESULT = array();
						$RESULT =  $this->model('results')->valueMatriz($RESPUESTA,$PREGUNTA,$sub_pregunta->idSub,$sub_respuesta->idLabel);
						$HTML .= '<td>';
						$HTML .= '<select class="form-control">';
						foreach($RESULT as $x){
							$HTML .= '<option>';
							$HTML .= $x->resultado.' - '.$x->cantidad;
							$HTML .= '</option>';
						}
						$HTML .= '</select>';
						$HTML .= '</td>';	
						}
					}
					if( $TIPO == "m_select"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
						$RESULT = array();
						$RESULT =  $this->model('results')->valueMatriz($RESPUESTA,$PREGUNTA,$sub_pregunta->idSub,$sub_respuesta->idLabel);
						$HTML .= '<td>';
						if( $RESPUESTA != 0)//USUARIO = 0 ES ANONIMO
						{
							$HTML .= '<input type="text" class="form-control" ';
							foreach($RESULT as $x){
								$HTML .= 'value = "';
								$HTML .= 'value '.$x->resultado.' - Cant '.$x->cantidad;
								$HTML .= '"';
							}
							$HTML .= '/>';
						}else{
							$HTML .= '<select class="form-control">';
							foreach($RESULT as $x){
								$HTML .= '<option>';
								$HTML .= 'value '.$x->resultado.' - Cant '.$x->cantidad;
								$HTML .= '</option>';
							}
							$HTML .= '</select>';
						}
						$HTML .= '</td>';	
						}
					}
				$HTML .= '</tr>';
			}
// 			$HTML .= '<tr class="success"><td colspan="7" >'.$HELP.'</td></tr>';
			$HTML .= '</tbody>';
		$HTML .= '</table>';
		$HTML .= '</div>';//FIN TABLA
		$HTML .= '</section>';
		RETURN $HTML;
	}
	
}
