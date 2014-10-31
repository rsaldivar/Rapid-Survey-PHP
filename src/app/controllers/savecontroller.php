<?php 
class SaveController extends controller  
{

	//VARIEABLES
	// $_SERVER['REMOTE_ADDR'] : Direccion de respuesta
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		//CommonFunctions::isUserLogedIn();
		//CommonFunctions::isUsersAccessable('save/index');


		$ID_ENCUESTA = $_POST["encuesta_id"];
		$SAVE_ERROR = TRUE;
		$RESULTADO = " RESULTADO NO DESEADO ";
		
		//===============================================================================|
		// USUARIO LOGEADO
// 		//===============================================================================|
// 		if( $_SESSION["SESS_USER_ID"] != NULL){
// 			$ID_USUARIO = $_SESSION["SESS_USER_ID"];
// 			
// 			//SE CREA UN REGISTRO DE LAS RESPUESTAS CON EL ID DEL USUARIO 
// 			$SQL = 'INSERT INTO  usuario_respuestas (respondente_id , fecha, hora, lugar, encuesta_id) VALUE ( '.$ID_USUARIO.', CURDATE() , CURTIME() ,"'.$_SERVER['REMOTE_ADDR'].'" ,'.$ID_ENCUESTA.')'; 
// 			mysql_query($SQL);
// 			$SAVE_ERROR = FALSE;
// 			$RESULTADO = "GRACIAS POR RESPONDER USUARIO";
// 		}
// 	
		//===============================================================================|
		// USUARIO TOKEN
		//===============================================================================|
		if( $_POST["token"] != NULL){
			$TOKEN =  $_POST["token"];
			//FALTA LIGAR TOKEN A LA ENCUESTA
			ECHO "ID USUARIO TOKEN ".$TOKEN."<br/>";
			$SQL = 'select * from usuario_tokens where token = "'.$TOKEN.'" AND estado="activo" ';
			$RESULT = mysql_query($SQL);
			if( !$RESULT) {
				die('No se pudo consultar el token <br/> Mysql error : ' . mysql_error());
				$SAVE_ERROR = TRUE;
				$RESULTADO = " TU TOKEN ES INVALIDO " ;
			}
			else {
				//SE CREA UN REGISTRO DE LAS RESPUESTAS CON EL EMAIL DEL TOKEN 
				$EMAIL_TOKEN = mysql_result($RESULT,0,2);//OBTENER EL EMAIL(3 column) DEL PRIMER(0) ITEM
				$SQL = 'INSERT INTO  usuario_respuestas (email , fecha, hora, lugar, encuesta_id) VALUE ( "'.$EMAIL_TOKEN.'", CURDATE() , CURTIME() ,"'.$_SERVER['REMOTE_ADDR'].'" ,'.$ID_ENCUESTA.')'; 
				mysql_query($SQL);
				$SQL = 'UPDATE usuario_tokens SET estado="inactivo" where email="'.$EMAIL_TOKEN.'"';
				mysql_query($SQL);
				$SAVE_ERROR = FALSE;
				$RESULTADO = " Respuesta guardada code(1)" ;
				//CODE 1 = Guardada desde token
			}
		}
		
		//===============================================================================|
		// USUARIO PUBLICO
		//===============================================================================|
		else  {
			$SQL = 'select encuestas.permisos from encuestas where id = '.$ID_ENCUESTA.'';
			$RESULT = mysql_query($SQL);
			if( !$RESULT) {
				die('No se pudo consultar la privacidad de la encuesta <br/> Mysql error : ' . mysql_error());
			}
			$RESULT = mysql_result($RESULT,0);
			if ( $RESULT == "privada"){ 
				$SAVE_ERROR = TRUE;
				//$RESULTADO = " Respuesta no guardada FALTA EL TOKEN " ;
			}
			else{
				//OBTENER EL EMAIL(3 column) DEL PRIMER(0) ITEM
				$SQL = 'INSERT INTO  usuario_respuestas (email , fecha, hora, lugar, encuesta_id) VALUE ( "anonimo", CURDATE() , CURTIME() ,"'.$_SERVER['REMOTE_ADDR'].'" ,'.$ID_ENCUESTA.')'; 
				mysql_query($SQL);
				$SAVE_ERROR = FALSE;
				$RESULTADO = "Respuesta guardada code(2)";
				//CODE 2 = Guardada como anonimo
			}
		}
		
		
	//===============================================================================|
	//===============================================================================|
	//OBTENEMOS EL ULTIMO ID INSERTADO: El cual es de la tabla usuario_respuestas ya sea por un usuario logeado, usuario con token o usuario anonimo 
	$ULTIMO_RESULTADO = mysql_query('select last_insert_id()'); $ULTIMO_RESULTADO = mysql_result($ULTIMO_RESULTADO,0);
	$ID_LAST_FOLIO = $ULTIMO_RESULTADO; 
	
  
	foreach( $_POST as $NAMEs=>$VALUE)
	{ 
		/*NAME EJEMPLO  	:  tipo_X&TT&ID1%ID2"
		$TABLA            	: X  -> Representa el tipo de tabla, simple (s)o matriz(m)
		$TIPO 		        : TT -> Representa el tipo de resultado que se almacenara
		$IDPREGUNTA	    	: ID1 -> Representa el id de la 'pregunta_id'
		$IDLABEL		    : ID2 -> Representa el id de la 'sub_label_id'     */
		$VAR_POST 	= explode("&",$NAMEs);
		$TIPO_PREGUNTA 	= $VAR_POST[0]; 
		$ID_PREGUNTA	= $VAR_POST[1]; 
		$ID_LABEL		= $VAR_POST[2]; 
		/*
			echo "<hr/><pre>";
			print_r ($VAR_POST);
			echo "</pre>";
	    */
		
		//===============================================================================|
		// INSERT SINGLE
		//===============================================================================|
		//INSERT TIPO SINGLE
		//TIPO  TIPO DE TABLA
		//00	text,date,number etc... string 
		//01	radiobutton... votos 
		//02	checkboxs	     
		//===============================================================================|
		if( $TIPO_PREGUNTA == 's_text' || $TIPO_PREGUNTA == 's_number' || $TIPO_PREGUNTA == 's_date' || $TIPO_PREGUNTA == 's_time' || 		$TIPO_PREGUNTA == 's_tel' || $TIPO_PREGUNTA == 's_textarea'  && $VALUE != "" )
		{ 
			$SQL = 'INSERT INTO  respuestas (pregunta_id , resultado, folio_respuesta) VALUE ( '.$ID_PREGUNTA.', "'.$VALUE.'" , '.$ID_LAST_FOLIO.' )'; 
			mysql_query($SQL);  
		}

		//===============================================================================|
		//	INSERT TPO MATRIZ
		//	TIPO	TIPO DE TABLA
		//	00		radiobuttons
		//	01		checkbox     
		//	02		string
		//	03		lista de opciones "select"
		//	Nota :  Estos se asignaron al crear el html : archivo -> generador.php
		//===============================================================================|
		
		
		//===============================================================================|
		// INSERT ARRAY
		//===============================================================================|
		//SELECT DE PREGUNTA_ID - Para agregar un dato auxiliar de las respuesta tipo matriz 
		else {
			
			$ARRAY_pregunta_id_SQL  = 'SELECT P.id  from preguntas as P LEFT JOIN sub_preguntas as SP ON P.id = SP.pregunta_id WHERE SP.id = '.$ID_PREGUNTA.''; 
			$ARRAY_pregunta_id_RES = mysql_query($ARRAY_pregunta_id_SQL);
			$ARRAY_pregunta_id = mysql_result($ARRAY_pregunta_id_RES,0);
		    
			//INSERT DE ARRAY DE SELECT - Se realizan varios insert a la tabla sub_respuestas
			if( $TIPO_PREGUNTA == "a_select" &&  $VALUE != "" ) {  
				$SQL = 'INSERT INTO  sub_respuestas (sub_pregunta_id, sub_respuesta_id, resultado , pregunta_id , folio_respuesta) VALUE ( '.$ID_PREGUNTA.', '. $VALUE.', "true" ,'.$ARRAY_pregunta_id.' , '.$ID_LAST_FOLIO.' )';
				mysql_query($SQL);  
			}
			//INSERT DE ARRAY RADIOBUTTON - Se realiza un insert a la tabla sub_resputas
			if( $TIPO_PREGUNTA == "a_radio" &&  $VALUE[0] != "" ){   
				foreach ( $VALUE as $valor)// POR SER UN ARRAY
				{ 
					$SQL = 'INSERT INTO  sub_respuestas (sub_pregunta_id, sub_respuesta_id, resultado , pregunta_id , folio_respuesta) VALUE ( '.$ID_PREGUNTA.', '. $VALUE[0].', "true" ,'.$ARRAY_pregunta_id.' , '.$ID_LAST_FOLIO.' )';
					mysql_query($SQL);  
				}
			}
			//INSERT DE ARRAY DE CHECKBOX - Se realizan varios insert a la tabla sub_respuestas
			if( $TIPO_PREGUNTA == "a_checkbox" &&  $VALUE[0] != "" ) {  
				foreach ( $VALUE as $valor)// POR SER UN ARRAY
				{ 
					$SQL = 'INSERT INTO  sub_respuestas (sub_pregunta_id, sub_respuesta_id, resultado , pregunta_id , folio_respuesta) VALUE ( '.$ID_PREGUNTA.', '. $VALUE[0].', "true" ,'.$ARRAY_pregunta_id.' , '.$ID_LAST_FOLIO.' )';
					mysql_query($SQL);  
				}
			}
		    
			
			//===============================================================================|
			// INSERT MATRIZ
			//===============================================================================|
			//INSERT DE MATRIZ RADIOBUTTON - Se realiza un insert a la tabla sub_resputas
			if( $TIPO_PREGUNTA == "m_radio" && $VALUE != "" ) {   
				$SQL = 'INSERT INTO  sub_respuestas (sub_pregunta_id, sub_respuesta_id, resultado ,pregunta_id, folio_respuesta) VALUE ( '.$ID_PREGUNTA.', '.$VALUE.', "true" , '.$ARRAY_pregunta_id.' , '.$ID_LAST_FOLIO.' )';
				mysql_query($SQL);  
			}
		    
			//INSERT DE MATRIZ DE CHECKBOX - Se realizan varios insert a la tabla sub_respuestas
			if( $TIPO_PREGUNTA == "m_checkbox" && $VALUE != "" ) {  
				foreach ( $VALUE as $valor)
				{
					$SQL = 'INSERT INTO  sub_respuestas (sub_pregunta_id, sub_respuesta_id, resultado , pregunta_id , folio_respuesta) VALUE ( '.$ID_PREGUNTA.', '.$valor.', "true" ,'.$ARRAY_pregunta_id.' , '.$ID_LAST_FOLIO.' )';
					mysql_query($SQL);  
				}
			}
		    
			//INSERT DE MATRIZ DE INPUT TEXT - Se inserta un string a la tala sub_respuestas
			if(  $TIPO_PREGUNTA == "m_text" && $VALUE != "" ) {  
				$SQL = 'INSERT INTO sub_respuestas (sub_pregunta_id, sub_respuesta_id , resultado , pregunta_id, folio_respuesta ) VALUE ('.$ID_PREGUNTA.', '.$ID_LABEL.', "'.$VALUE.'" ,'.$ARRAY_pregunta_id.' , '.$ID_LAST_FOLIO.' )';
				mysql_query($SQL);  
			}
		    
			  //INSERT DE MATRIZ DE SELECT - Se inserta un string a la tabla sub_respuestas
			if(  $TIPO_PREGUNTA == "m_select" && $VALUE != "" ) {  
				$SQL = 'INSERT INTO  sub_respuestas (sub_pregunta_id, sub_respuesta_id , resultado , pregunta_id, folio_respuesta ) VALUE ( '.$ID_PREGUNTA.', '.$ID_LABEL.', "'.$VALUE.'",'.$ARRAY_pregunta_id.' , '.$ID_LAST_FOLIO.' )';
				mysql_query($SQL);  
			}
		    
		}//ELSE - GUARDAR TIPO : ARRAY-MATRIZ
	}//FOREACH
	
	
	if($SAVE_ERROR == TRUE)
	{
		$_SESSION['MSG'] = CommonFunctions::DisplayMsg($RESULTADO,'alert-danger'); 
	}
	else
	{
		$_SESSION['MSG'] = CommonFunctions::DisplayMsg($RESULTADO,'alert-success'); 
	}
	
	$this->wtRedirect('customer/surveys');
	$this->wtRedirect('home');
		
	}//FUNCTION INDEX. SALVAR RESULTADOS
	
	//VER ENCUESTA - REALIZAR VALIDACIONES ANTES DE VISUALIZAR LA VISTA GUARDADA EN EL REPOSITO
	function survey(){

		include("app/includes/commonfunctions.inc.php");
		
		$args = func_get_args();
		$survey = $args[0];
		$this->view('repo/'.$survey);

	}
	
}
