<?php 
class GeneradorController extends controller  
{
	var $helpers=array('paginator');
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('generador/index');
		
		$this->wtRedirect('generador/publish');
	}
	
	
	function publish()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('generador/publish');
		
		$args = func_get_args();
		$id = $args[0];
		$ID_ENCUESTA= $id;
		$ID_GRUPO	= $id;
		$ID_USUARIO	= $_SESSION["SESS_USER_ID"];
		
		//MARCAR COMO ACTIVA PARA RESPONDER
		$columnsArr = array();
		$columnsArr['difusion']	= 'activa';
		$this->model('encuestas')->updateEncuesta($columnsArr,'id="'.$ID_ENCUESTA.'"');
		
		//CARGAR INFORMACION DE LA ENCUESTA
		$searchitems = " AND encuestas.id_usuario = ".$_SESSION["SESS_USER_ID"];
		$searchitems = ' AND encuestas.id = '.$ID_ENCUESTA;
		$this->tempVars['ENCUESTA'] =  $this->model('encuestas')->selectAllEncuestasCliente('encuestas.*',$searchitems);
		
		//HEADER ENCUESTA
		$HTML = $this->headerEncuesta($this->tempVars['ENCUESTA'][0]); 
		
		
		//LECTURA PREGUNTAS
		$PREGUNTAS = $this->model('generador')->Preguntas($ID_ENCUESTA,$ID_GRUPO); 
		
		//GENERACION DE LAS PREGUNTAS
		foreach($PREGUNTAS as $pregunta){
			if( $pregunta->estado == "activada" ){
				
				$sub_preguntas = $this->model('generador')->subPreguntas($ID_ENCUESTA,$ID_GRUPO,$pregunta->id);//OBTENECION PREGUNTAS
				$sub_respuestas = $this->model('generador')->subLabels($ID_ENCUESTA,$ID_GRUPO,$pregunta->id);//OBTENECION LABELS
				
				$sql_titulo = " select preguntas.titulo from preguntas where preguntas.id = ".$pregunta->id;//OBTENCION TITULO
				$titulo = mysql_query($sql_titulo);	$titulo_fetch  = mysql_fetch_array($titulo);
				
				$sql_help = " select preguntas.mensaje_ayuda from preguntas where preguntas.id = ".$pregunta->id;
				$help = mysql_query($sql_help);		$help_fetch  = mysql_fetch_array($help);
				
				//GENERACION TIPO SIMPLE
				if( $pregunta->dimension == "simple")
					$HTML .= $this->tablaSimple($pregunta->id,$pregunta->tipo,$pregunta->class,$titulo_fetch[0],$help_fetch[0],$pregunta->min,$pregunta->max,$pregunta->mensaje_validacion);
				//GENERACION TIPO ARRAY
				if( $pregunta->dimension == "array")
					$HTML .= $this->tablaMatriz($pregunta->id,$pregunta->tipo,$pregunta->class,$sub_preguntas,$sub_respuestas,$titulo_fetch[0],$help_fetch[0]);
				if( $pregunta->dimension == "matriz")
					$HTML .= $this->tablaMatriz($pregunta->id,$pregunta->tipo,$pregunta->class,$sub_preguntas,$sub_respuestas,$titulo_fetch[0],$help_fetch[0]);
			}
		}//FIN FOR EACH x PREGUNTAS
		
		
		//VALIDAR CREACION DE HTML
		if( $this->escribirHtml($HTML,$ID_USUARIO,$ID_ENCUESTA) == FALSE ){
			echo ("Error en la Generacion del Archivo".$fichero_salida);
			$_SESSION['MSG'] = CommonFunctions::DisplayMsg("No se pudo crear la encuesta.",'alert-danger');
			$this->wtRedirect('customer/surveys');
		}
		else
		{
			//TRAER LA INFORMACION DE LOS TOKENS 
			$searchitemsToken .= " AND usuario_tokens.encuesta=".$ID_ENCUESTA."";
			$this->tempVars['ENCUESTA_PUBLICADA'] =  $this->model('encuestas')->selectAllTokens('usuario_tokens.*',array(),$searchitemsToken);
			
			//REACTIVAR LOS TOKENS PARA QUE PUEDAN RESPONDER
			foreach($this->tempVars['ENCUESTA_PUBLICADA'] as $TOKENS => $KEY){
				$columnA=array();
				$columnA['estado']='activo';
				$this->model('encuestas')->updateToken($columnA,$this->tempVars['ENCUESTA_PUBLICADA'][$TOKENS]->id);
			}
			
			$searchitemsEncuesta = " AND encuestas.id=".$ID_ENCUESTA."";
			$this->tempVars['ENCUESTA'] =  $this->model('encuestas')->selectAllEncuesta('encuestas.*',array(),$searchitemsEncuesta);
			
			$TITULO_ENCUESTA =  $this->tempVars['ENCUESTA'][0]->titulo;
			$DESCRIPCION_ENCUESTA =  $this->tempVars['ENCUESTA'][0]->descripcion;
			
			//ENVIO DEL TOKEN POR MAIL
 			$this->sendMailPrivada( $this->tempVars['ENCUESTA_PUBLICADA'],$ID_USUARIO,$ID_ENCUESTA,$TITULO_ENCUESTA,$DESCRIPCION_ENCUESTA);
 			$this->wtRedirect('customer/surveys');
		}
	}	
	
	//Project Change Status
	function sendMailPublica($email,$tituloEncuesta)
	{
	}
	function sendMailPrivada($informacion,$ID_USUARIO,$ID_ENCUESTA,$TITULO_ENCUESTA,$DESCRIPCION_ENCUESTA )
	{
		$this->pr($informacion);
		
		require "framework/PHPMailerAutoload.php";
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->setFrom(EMAIL_FROM);//Establecer a quien enviara
		$mail->FromName = SITE_TITLE;
		
		foreach($informacion as $item=>$campo){
		$email_to = $campo->email;
		$email_subject = "Nueva Encuesta" ;
		$url = 'http://10.14.4.22:8080/rapidsurvey/src/save/survey/'.$ID_USUARIO.''.$ID_ENCUESTA.'&t='.$campo->token;
		
			$email_subject = SITE_TITLE.' ';
			$emailMsg = "<table border='0' cellpadding='2' cellspacing='0' width='100%'>
							<tr>	<td>La encuesta se llama : ".$TITULO_ENCUESTA."</td>
							</tr>
							<tr>	<td>".$DESCRIPCION_ENCUESTA."</td>
							</tr>
							<tr>
								<td>Contestala : <a href=\"".$url."\" >".$url."</a></td>
							</tr>
							<tr>
								<td>Thanks<br/>Admin<br>".SITE_TITLE."</td>
							</tr>
						</table>";	 

			
			$mail->addAddress($email_to);//Establecer a quién es mail que se enviará 
			$mail->AddBCC(EMAIL_FROM,SITE_TITLE);
			$mail->Subject = $email_subject ;
			$mail->IsHTML(true);  
			$mail->msgHTML($emailMsg);//HTML
			$mail->AltBody = $emailMsg; //$mail->addAttachment('images/');
			$exito = $mail->Send();
			$mail->ClearAddresses(); 
			$intentos=0; 
			while ((!$exito) && ($intentos < 10)) {
			      sleep(3000);
			      echo $mail->ErrorInfo;
			      $exito = $mail->Send();
			      $intentos=$intentos+1;	
			}
			if(!$exito)
			{
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Error al enviar correo.','alert-danger');
			      echo "<br/>".$mail->ErrorInfo;	
			}
			else
			{
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Email enviado.','alert-success');
			}
		}
	}
	
	function headerEncuesta($ENCUESTA)
	{
		$HTML .= '<?php include_once("app/view/header.inc.php"); ?>';//PARA MANTENER EL HEADER
		///VALIDACIONES ENCUESTA DATOS AL GENERAR
		$HTML .= '<?php
		//GUARDAR TOKEN
		$token = "VACIO";
		if($_GET["t"] != NULL)$token = $_GET["t"];
		
		if($this->tempvars["PROPIEDAD"] == FALSE){
		
				if(strcmp( "'.$ENCUESTA->estado.'" , "activa") != 0  ){
					$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Encuesta Inactiva.","alert-warning"); 
					$this->wtRedirect("customer/surveys");
				}   
				if(strcmp( "'.$ENCUESTA->difusion.'" , "activa") != 0 ){
					$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Encuesta no esta en difusion.","alert-danger"); 
					$this->wtRedirect("customer/surveys");
				}   
				if("'.$ENCUESTA->fecha_final.'" < date("Y-m-d")){
					$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Fecha de Respuesta vencida.","alert-danger"); 
					$this->wtRedirect("customer/surveys");
				}
		
				?>';
				
		///VALIDACIONES ENCUESTA DATOS AL CONTESTAR - BASE DE DATOS 
		$HTML .= '<?php
				$SQL = "select * from encuestas where id = '.$ENCUESTA->id.'" ;
				$RESULT = mysql_query($SQL);
				$ENCUESTA =  mysql_fetch_object($RESULT);
				
				if(strcmp( $ENCUESTA->estado , "activa") != 0  ){
					$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Encuesta Inactiva.","alert-warning"); 
					$this->wtRedirect("customer/surveys");
				}   
				if(strcmp( $ENCUESTA->difusion , "activa") != 0 ){
					$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Encuesta no esta en difusion.","alert-danger"); 
					$this->wtRedirect("customer/surveys");
				}   
				if( $ENCUESTA->fecha_final < date("Y-m-d")){
					$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Fecha de Respuesta vencida.","alert-danger"); 
					$this->wtRedirect("customer/surveys");
				}
				
				//VALIDAR PERMISOS DE RESPUESTA = TOKEN
				$SQL = "select estado from usuario_tokens where token = \"".$token."\" ";
				$RESULT = mysql_query($SQL);
				$ESTADO_TOKEN = "NULL";
				$ESTADO_TOKEN = mysql_result($RESULT,0);
				
				if($_SESSION["SESS_USER_ID"] != NULL)$ESTADO_USUARIO = "activo";else $ESTADO_USUARIO="inactivo";
				
				//echo "La encuesta es : ".$ESTADO_PERMISO." el token recivido es : ".$ESTADO_TOKEN." pero hay un usuario : ".$ESTADO_USUARIO;
				if($ENCUESTA->permisos == "privada" ){
						if($ESTADO_TOKEN == NULL)
						{
							$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Solicita Token.","alert-warning"); 
							$this->wtRedirect("customer/surveys");
						}
						else if($ESTADO_TOKEN == "inactivo")
						{
							$_SESSION["MSG"] = CommonFunctions::DisplayMsg("Token Invalido.","alert-danger"); 
							$this->wtRedirect("customer/surveys");
						}
				}
		}//VALIDACIONES PARA EL USUARIO QUE DESEA RESPONDER
				?>';
				
 		$HTML .='	<script language="javascript">
 					$(document).keypress(function(){
 						if ( event.which == 13 ) {
 							 event.preventDefault();
 						}
 					});
 					</script>';

		//WIZARD PLUGIN
		$HTML .= '<link rel="stylesheet" href="'.SITE_URL.'app/view/js/jquery-steps-master/demo/css/jquery.steps.css">';
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
							form.submit();
				    	}
					});
				});
				
				$( document ).ready(function() {
					$("#wizard").validate();
				});
			</script>';
		
		$HTML .= '<body >'; 
		
		//$HTML .= '<div style="background: none repeat scroll 0 0 #000000; height: 1000%; position: absolute; width: 100%; z-index: 2147483647;" id="shadow" ></div>';
		
		$HTML .= '<!--[if lt IE 7]>
		    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->';
		
		$HTML .= '<div class="container">';
		$HTML .= '<div class="row">';
		$HTML .= '<div class="pull-left">'; 
		$HTML .= '</div>';
		$HTML .= '</div>';
		$HTML .= '<div class="row"><div class="clear">&nbsp;</div><hr></div>';
		if(REWRITEURL)$HTML .= '<form id="wizard" action="'.SITE_URL.'save/index" method="post" >';
		else $HTML .= '<form id="wizard" action="'.SITE_URL.'index.php/save/index" method="post" >';
		
		$HTML .= '<input type="hidden" name="encuesta_id" value="'.$ENCUESTA->id.'"  >';
		$HTML .= '<input type="hidden" name="encuesta_titulo" value="'.$ENCUESTA->titulo.'">';
		$HTML .= '<input type="hidden" name="encuesta_fecha_final" value="'.$ENCUESTA->fecha_final.'">';
		
		$HTML .= '<input type="hidden" id="token" name="token" value="<?php echo $_GET["t"] ?>" >';//LLEVA ESCRITO RECIBIR LA VARIABLE "T" POR EL METODO GET.. PASA ASI MANDAR SAVECONTROLLER.php
		return $HTML;
	}
	
	function tablaSimple($PREGUNTA,$TIPO,$CLASS,$TITULO,$HELP,$MIN,$MAX,$MENSAJE_VALIDACION){
		$HTML .= '<h2>'.$TITULO.'</h2>';
		$HTML .= '<section>';
		$HTML .= '<div class="table-responsive">';
		$HTML .= '<table class="table table-bordered table-hover preguntaSimple">';
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
			if( $TIPO == "s_text" )
			{
				$HTML .= '<input type="text"  minlength="'.$MIN.'"  maxlength="'.$MAX.'"   title="'.$MENSAJE_VALIDACION.'" name="'.$TIPO.'&'.$PREGUNTA.'" id="'.$TIPO.'&'.$PREGUNTA.'" class="'.$CLASS.' form-control"  >';
			}
			if( $TIPO == "s_number" )
			{
				$HTML .= '<input type="number" min="'.$MIN.'"  max="'.$MAX.'"   title="'.$MENSAJE_VALIDACION.'" name="'.$TIPO.'&'.$PREGUNTA.'" id="'.$TIPO.'&'.$PREGUNTA.'" class="'.$CLASS.' form-control"  >';
			}
			if( $TIPO == "s_date" )
			{
				$HTML .= '<input type="date" name="'.$TIPO.'&'.$PREGUNTA.'" id="'.$TIPO.'&'.$PREGUNTA.'" class="'.$CLASS.' form-control"  >';
			}
			if( $TIPO == "s_time" )
			{
				$HTML .= '<input type="time" name="'.$TIPO.'&'.$PREGUNTA.'" id="'.$TIPO.'&'.$PREGUNTA.'" class="'.$CLASS.' form-control"  >';
			}
			if( $TIPO == "s_tel" )
			{
				$HTML .= '<input type="tel" name="'.$TIPO.'&'.$PREGUNTA.'" id="'.$TIPO.'&'.$PREGUNTA.'" class="'.$CLASS.' form-control"  >';
			}
			if( $TIPO == "s_textarea" )
			{
				$HTML .= '<textarea name="'.$TIPO.'&'.$PREGUNTA.'" id="'.$TIPO.'&'.$PREGUNTA.'" class="'.$CLASS.' form-control"  ></textarea>';
			}
			$HTML .= '</td>';
			$HTML .= '</tr>';
			$HTML .= '<tr class="success"><td colspan="7" >'.$HELP.'</td></tr>';
			$HTML .= '</tbody>';
		$HTML .= '</table>';
		$HTML .= '</div>';
		$HTML .= '</section>';
		RETURN $HTML;
	}
	
	function tablaMatriz($PREGUNTA,$TIPO,$CLASS,$SUB_PREGUNTAS,$SUB_RESPUESTAS,$TITULO,$HELP){
		$HTML .= '<h2>'.$TITULO.'</h2>';
		$HTML .= '<section>';
		$HTML .= '<div class="table-responsive">';
		$HTML .= '<table class="table table-bordered table-hover preguntaMultiple">';
			$HTML .= '<thead>';
				$HTML .= '<tr>';
				$HTML .= '<th>';
				$HTML .= $TITULO;
				$HTML .= '</th>';

				if( $TIPO == "a_select" ){
					$HTML .="<th>Opciones</th>";
				}
				else {
					foreach($SUB_RESPUESTAS as $sub_respuesta){
						if($TIPO == "m_select" ){
							$opciones = explode(";", $sub_respuesta->tituloLabel);
							$HTML .= "<th>".$opciones[0]."</th>";
						}
						else  $HTML .= "<th>".$sub_respuesta->titulo."</th>";
					}
				}
				$HTML .='</tr>';
			$HTML .= '</thead>';
			$HTML .= '<tbody>';

			foreach($SUB_PREGUNTAS as $sub_pregunta){
				$HTML .= '<tr id="'.$sub_pregunta->titulo.'-id-'.$sub_pregunta->idSub.'">';
				$HTML .= '<td>'.$sub_pregunta->titulo.'</td>';
					
					if( $TIPO == "a_checkbox"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
							$HTML .= '<td><input name="'.$TIPO.'&'.$sub_pregunta->idSub.'[]" type="checkbox"  rol="'.$sub_respuesta->tituloLabel.'" value="'.$sub_respuesta->idLabel.'"  class="'.$CLASS.'" ></td>';
						  }
					}
					if( $TIPO == "a_radio"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
							$HTML .= '<td><input name="'.$TIPO.'&'.$sub_pregunta->idSub.'[]" type="radio"  rol="'.$sub_respuesta->tituloLabel.'" value="'.$sub_respuesta->idLabel.'"  class="'.$CLASS.'" ></td>';
						  }
					}
					if( $TIPO == "a_select"){
						$HTML .= '<td><select name="'.$TIPO.'&'.$sub_pregunta->idSub.'" class="'.$CLASS.' form-control ">';
							foreach($SUB_RESPUESTAS as $sub_respuesta){
							$HTML .= '<option  value="'.$sub_respuesta->idLabel.'" >'.$sub_respuesta->titulo.'</option>';
						  	}
						$HTML .= '</select></td>';
					}
					if( $TIPO == "m_radio"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
							$HTML .= '<td><input name="'.$TIPO.'&'.$sub_pregunta->idSub.'" type="radio"  rol="'.$sub_respuesta->tituloLabel.'" value="'.$sub_respuesta->idLabel.'"  class="'.$CLASS.'" ></td>';
						}
					}
					if( $TIPO == "m_checkbox"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
							$HTML .= '<td><input name="'.$TIPO.'&'.$sub_pregunta->idSub.'[]" type="checkbox"  rol="'.$sub_respuesta->tituloLabel.'" value="'.$sub_respuesta->idLabel.'"  class="'.$CLASS.'" ></td>';
						  }
					}
					if( $TIPO == "m_text"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
							$HTML .= '<td><input name="'.$TIPO.'&'.$sub_pregunta->idSub.'&'.$sub_respuesta->idLabel.'" type="text" class="'.$CLASS.' form-control"></td>';
						  }
					}
					if( $TIPO == "m_select"){
						foreach($SUB_RESPUESTAS as $sub_respuesta){
							$opciones = explode(";", $sub_respuesta->tituloLabel);
							$HTML .= '<td><select name="'.$TIPO.'&'.$sub_pregunta->idSub.'&'.$sub_respuesta->idLabel.'" class="'.$CLASS.' form-control ">';
							for($i=1;$i < count($opciones); $i++){
								$HTML .= '<option>'.$opciones[$i].'</option>';
							}
							$HTML .= '</select></td>';
						  }
					}
				$HTML .= '</tr>';
			}
			$HTML .= '<tr class="success"><td colspan="7" >'.$HELP.'</td></tr>';
			$HTML .= '</tbody>';
		$HTML .= '</table>';
		$HTML .= '</div>';//FIN TABLA
		$HTML .= '</section>';
		RETURN $HTML;
	}

	function escribirHtml($HTML,$USUARIO,$ID_ENCUESTA){
// 		$HTML .= '<button class="btn btn-primary btn-lg" type="submit">Guardar</button>'; este fue eliminado, se agrega la funcion jquery para aprovechar el boton finish wizard
		$HTML .= '</form>';
		$HTML .= '</div>';
		$HTML .= '</body>';
		$HTML .= '</html>';
		
		$CARPETA = ''.$USUARIO.''.$ID_ENCUESTA;
		mkdir("app/view/repo", 0775);
		$fichero_salida="app/view/repo/".$CARPETA.".php";
		$fp=fopen($fichero_salida,"w+");
		fwrite($fp,$HTML);
		fclose($fp);
		if(!file_exists($fichero_salida))return FALSE; else return TRUE;
	}

}
