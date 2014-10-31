<?php  
class CustomerController extends controller 
{
	var $helpers=array('paginator','rating');
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('customer/index');
		
		$this->wtRedirect('customer/listing');
	}
	
	function listing()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('customer/listing');

		$args = func_get_args();
		$customer_id = $args[0];
		
		if ($_POST["frmSubmit"] == "yes") 
		{
			//Implementing footer action 
			if($_POST['footerAction']=='Activate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['estado']='activo';
				$this->model('user')->updateuser($columnA,' id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been activate successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Deactivate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['estado']='inactivo';
				$this->model('user')->updateuser($columnA,' id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deactivate successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Delete' && count($_POST['ids'])>=1)
			{
				$this->model('user')->deleteuser($_POST['ids']);
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deleted successfully.','alert-success');
			}
			//End
		}

        
		$searchitems = " AND usuarios.rol = 'cliente'  GROUP BY (usuarios.id) ;";
		$Total= $this->model('user')->selectAllUser('usuarios.*','',$searchitems);
		/*Paging start here*/
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
		/*Paging end here*/
		
		if($_SESSION["SESS_USER_ROLE"] == "cliente" )//SII ERES CLIENTE, SOLO MODFICAR TU ID
		{
			$searchitems = " AND usuarios.id = ".$_SESSION["SESS_USER_ID"]; 
		}
		$this->tempVars['CustomerList'] = $this->model('user')->selectAllUser('usuarios.*','',$searchitems);
		$this->view('customer/list');
	}	
	
	//EDITAR CUSTOMER
	function edit()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('customer/edit');
		
		$args = func_get_args();
		$customer_id = $args[0];
		
		if($_POST['customer_form_edit'] == 'yes')
		{
			if($_SESSION["SESS_USER_ROLE"] == "cliente")
			{
				$customer_id = $_SESSION["SESS_USER_ID"]; 
			}
			else
			{
				$customer_id = $_POST['customer_id'];
			}	
			$error ='';
			$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
			if(!preg_match($regex, strtolower($_POST['email'])))
			{
				$error = CommonFunctions::DisplayMsg("Invalid customer email (username).",'alert-warning');
			}
			if(!preg_match($regex, strtolower($_POST['detail_email'])))
			{
				$error = CommonFunctions::DisplayMsg("Invalid contact customer email.",'alert-warning');
			}
			//VALIDAR MAIL
			$existingemail = $this->model('commonfuns')->CheckExistingCustomerEmail('*',array('nombre'=>$_POST['name']),' AND usuarios.id !='.$customer_id);
			if(count($existingemail) && $error=='')
			{
				$error = CommonFunctions::DisplayMsg("Customer username already exist(s) Please choose another username.",'alert-warning');
			}
			if($error == '')
			{
				$columnsArrUser = array();
				$columnsArrUser['nombre']   				= $_POST['name'];
				$columnsArrUser['password']   				= $_POST['password'];
				$columnsArrUser['correo']   				= $_POST['email'];
				$columnsArrUser['direccion']   				= $_POST['address'];
				$columnsArrUser['nombres']   				= $_POST['detail_name'];
				$columnsArrUser['correo_alternativo']  		= $_POST['detail_email'];
				$columnsArrUser['telefono']  				= $_POST['detail_phone'];
				$columnsArrUser['fecha']					= date('Y-m-d');
				$this->model('user')->updateUser($columnsArrUser, "usuarios.id = '".$customer_id."'");
				
				$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Customer data updated successfully.",'alert-success');
				$this->wtRedirect('customer/listing');
			}	
		}
		
		if($_SESSION["SESS_USER_ROLE"] == "cliente")
		{
			$customer_id = $_SESSION["SESS_USER_ID"]; 
		}
		$searchitems = " AND usuarios.id = ".$customer_id. " GROUP BY (usuarios.id) ";
		$this->tempVars['CustomerList'] = $this->model('user')->selectAllUser('*','',$searchitems);
		
		//$this->tempVars['CustomerList'] = $this->model('customer')->selectCustomer('*', array('customer_id'=>$customer_id), '');
		$this->tempVars['MSG'] = $error;
		$this->view('customer/edit');
	}
	
	//VER ENCUESTA
	function survey(){

		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();  //PARA EL PUBLICO
		CommonFunctions::isUsersAccessable('customer/survey');

		$args = func_get_args();
		$survey = $args[0];
		$this->tempvars["PROPIEDAD"] = TRUE;
		$this->view('repo/'.$survey);

	}
	
	//VER ENCUESTAS
	function surveys()// URL = customer/syrveys
	{
		
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('customer/surveys');
		
		//ALTA
		if ($_POST["frmSubmitInsert"] == "yes")
		{	
			$columnsArr = array();
			$columnsArr['id_usuario']   			= $_SESSION["SESS_USER_ID"];
			$columnsArr['difusion']   				= 'inactiva';
			$columnsArr['titulo']   				= $_POST['titulo'];
			$columnsArr['descripcion']   			= $_POST['descripcion'];
			$columnsArr['mensaje_bienvenida']   	= $_POST['mensaje_bienvenida'];
			$columnsArr['mensaje_despedida']   		= $_POST['mensaje_despedida'];
			$columnsArr['fecha_inicio'] 			= date('Y-m-d');
			$columnsArr['fecha_final'] 				= $_POST['fecha_final'];
			if( $_POST['permisos'] == "on" )
				  $columnsArr['permisos']			= "publica";
			else
				  $columnsArr['permisos']			= "privada";
			
			//INSERT ENCUESTA
			$this->model('encuestas')->insertEncuesta($columnsArr);
			
			//INSERT TOKENS // ESTOS SE ACTIVA NUEVAMENTE AL PUBLICAR LA ENCUESTA
			if( $_POST['permisos'] != "on" ){
				$rs = mysql_query("SELECT @@identity AS id");//ULTIMO ID INGRESADO
				if ($row = mysql_fetch_row($rs)) {$id = trim($row[0]);}
				
				$Correos= explode(",", $_POST["correos"]);
				foreach ($Correos as $key => $value){
					if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
					    $columnsArrToken = array();
					    $token = md5(rand().$value);
					    $columnsArrToken['token'] = $token;
					    $columnsArrToken['email'] = $value;
					    $columnsArrToken['usuario']  = $_SESSION["SESS_USER_ID"];
					    $columnsArrToken['encuesta'] = $id;
					    $columnsArrToken['fecha_creacion'] = date('Y-m-d');
					    $columnsArrToken['fecha_final'] = $_POST['fecha_final'];
					    $this->model('encuestas')->insertTokens($columnsArrToken);
					}
				 }
			}
			$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Question inserted successfully.".$link,'alert-success');
			$this->wtRedirect('customer/surveys');
		}
		
		//==================================================================|
		// MODIFICACION - Estado y eliminacion via checkbox
		//==================================================================|
		$args = func_get_args();
		$survey_id = $args[0];
		if ($_POST["frmSubmit"] == "yes") 
		{
			//Implementing footer action  
			if($_POST['footerAction']=='Activate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['estado']='activa';
				$this->model('encuestas')->updateEncuesta($columnA,' encuestas.id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been activate successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Deactivate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['estado']='inactiva';
				$this->model('encuestas')->updateEncuesta($columnA,' encuestas.id in('.implode(',',$_POST["ids"]).')');
				$columnsArr = array();
				$columnsArr['difusion']	= 'inactiva';
				$this->model('encuestas')->updateEncuesta($columnsArr,' encuestas.id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deactivate successfully.','alert-success');
			}
			
			elseif($_POST['footerAction']=='Delete' && count($_POST['ids'])>=1)
			{
				$this->model('encuestas')-> deleteEncuesta($_POST['ids']);
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deleted successfully.','alert-success');
				//ENVIARCORREO SUBMISSION CONTROLLER -> NUEVO SUBMISSIOn
				
			}
			//End
		}
		
		
		//==================================================================|
		// LLENAR MODAL PARA EDITAR INFORMACION DE LA ENCUESTA
		//==================================================================|
		if ($_GET["edit"]!= "" and is_numeric($_GET["edit"]) )//SI LLEVA EL ID DE LA ENCUESTA
		{
			if( $_SESSION["SESS_USER_ROLE"] == "cliente" || $_SESSION["SESS_USER_ROLE"] == "administrador"){
			  $searchitemsEdit = " AND encuestas.id_usuario = ".$_SESSION["SESS_USER_ID"];
			}
			$searchitemsEdit .= " AND encuestas.id=".$_GET["edit"]."";
			$this->tempVars['ENCUESTA_EDIT'] =  $this->model('encuestas')->selectAllEncuestasCliente('encuestas.*',$searchitemsEdit);
			$this->tempVars['MODAL-EDIT'] = "TRUE";
			
			//OBTENER CORREOS DE LA ENCUESTA PRIVADA
			if( strcmp($this->tempVars['ENCUESTA_EDIT'][0]->permisos,"privada") == 0  ){
				$sql = "select email from usuario_tokens where encuesta= ".$_GET["edit"];
				$result = mysql_query($sql);				
				$this->tempVars['CORREOS-TOKENS']= "";
				while($fila = mysql_fetch_object($result)){
					$this->tempVars['CORREOS-TOKENS'] .= $fila->email.",";
				}
			}
		}
		//==================================================================|
		// ACTUALIZAR (UPDATE) ENCUESTA
		//==================================================================|
		if ($_POST["frmSubmitEdit"] == "yes" ){
			$columnsArr = array();
			$columnsArr['id_usuario']   			= $_SESSION["SESS_USER_ID"];
			$columnsArr['titulo']   				= $_POST['edit_titulo'];
			$columnsArr['mensaje_bienvenida']   	= $_POST['edit_mensaje_bienvenida'];
			$columnsArr['fecha_final'] 				= $_POST['edit_fecha_final'];
			$columnsArr['descripcion']   			= $_POST['edit_descripcion'];
			$columnsArr['mensaje_despedida']   		= $_POST['edit_mensaje_despedida'];
			$columnsArr['fecha_inicio'] 			= date('Y-m-d');
			$columnsArr['difusion']   				= 'inactiva';
			if( $_POST['edit_publica'] == "on" )
				  $columnsArr['permisos']			= "publica";
			else
				  $columnsArr['permisos']			= "privada";
			//UPDATE ENCUESTA
			$this->model('encuestas')->updateEncuesta($columnsArr, " id = ".$_POST['edit_id']);
				  
			//INSERT TOKENS - ENCUESTA PRIVADA
			if( $_POST['edit_publica'] != "on" ){
				mysql_query("delete from usuario_tokens where encuesta =".$_POST['edit_id']);
								
				$Correos= explode(",", $_POST["edit_correos"]);
				foreach ($Correos as $key => $value){
					if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
					    $columnsArrToken = array();
					    $token = md5(rand().$value);
					    $columnsArrToken['token'] = $token;
					    $columnsArrToken['email'] = $value;
					    $columnsArrToken['usuario']  = $_SESSION["SESS_USER_ID"];
					    $columnsArrToken['encuesta'] = $_POST['edit_id'];
					    $columnsArrToken['fecha_creacion'] = date('Y-m-d');
					    $columnsArrToken['fecha_final'] = $_POST['edit_fecha_final'];
					    $this->model('encuestas')->insertTokens($columnsArrToken);
					}
				 }
			}// ELIMINAR TOKENS - ENCUESTA PUBLICA
			else{
				mysql_query("delete from usuario_tokens where encuesta =".$_POST['edit_id']);
			}
			$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Survey updated successfully.".$link,'alert-success');
			$this->wtRedirect('customer/surveys');
			
		}

		//LISTAR 	//Para asegurar que solo aparescan los del customer o administrador
		if( $_SESSION["SESS_USER_ROLE"] == "cliente" || $_SESSION["SESS_USER_ROLE"] == "administrador"){
			$searchitems = " AND encuestas.id_usuario = ".$_SESSION["SESS_USER_ID"];
			$Total= $this->model('encuestas')->selectAllEncuestasCliente('encuestas.*',$searchitems);
		}
		

		/*Paging start here*/
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
		/*Paging end here*/
		if( $_SESSION["SESS_USER_ROLE"] == "cliente" || $_SESSION["SESS_USER_ROLE"] == "administrador"){
			$this->tempVars['ENCUESTAS'] =  $this->model('encuestas')->selectAllEncuestasCliente('encuestas.*',$searchitems);
		}
		
		$this->view('customer/encuestas');
	}
	
	
}
