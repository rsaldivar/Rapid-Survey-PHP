<?php   
class HomeController extends controller 
{
	function index()
	{
		if($_SESSION["SESS_USER_USERNAME"] !='')
		{
			$this->wtRedirect($_SESSION["ROLE_PATH"]);
		}
		include("app/includes/commonfunctions.inc.php");
		$_SESSION["SESS_USER_USERNAME"] = '';
		$_SESSION["SESS_USER_ROLE"] 	= '';
		if($_POST["username"]!="") 
		{
			$condArr = array();
			$condArr["nombre"] = $_POST["username"];
			$condArr["password"] = $_POST["password"];
			$row = $this->model("home")->selectAllUser("*",$condArr,'');
			if(count($row) && $row[0]->estado == 'activo')
			{
				$_SESSION["SESS_USER_USERNAME"] = $row[0]->nombre;
				$_SESSION["SESS_USER_ROLE"] = $row[0]->rol;
				$_SESSION["SESS_USER_ID"] = $row[0]->id;
				if($_SESSION["SESS_USER_ROLE"] == "administrador")
				{
					$_SESSION["ROLE_PATH"] = 'customer/surveys';
					$this->wtRedirect('customer/surveys');
				}
				elseif($_SESSION["SESS_USER_ROLE"] == "cliente"  )
				{
					$_SESSION["ROLE_PATH"] = 'customer/surveys';
					$this->wtRedirect('customer/surveys');
				}
			} 
			else
			{
				$this->tempVars["MSG"] = CommonFunctions::DisplayMsg("Please enter correct username/password.",'alert-danger');
			}
		}
		if( $args[0] != NULL)
		{
		  $args = func_get_args();
		  $ID_ENCUESTA = $args[0]; 
		  $this->wtRedirect('customer/survey/'.$ID_ENCUESTA);
		  
		}
		$this->view("home/login");
	}
	
	function logout()
	{
		$_SESSION["SESS_USER_USERNAME"] = '';
		$_SESSION["SESS_USER_ROLE"] 	= '';
		session_regenerate_id();
		$this->wtRedirect('home');
	}
	
	function change_password()
	{
		include("app/includes/commonfunctions.inc.php");
		if($_POST['change_password'] == 'yes')
		{
			$row = $this->model("home")->selectAllUser("*",array('id'=>$_SESSION["SESS_USER_ID"]),'');
			if($_POST['old_password'] == $row[0]->password)
			{
				$columnsArr =array();
				$columnsArr['password'] = $_POST['new_password'];
				$this->model('user')->updateuser($columnsArr, " id = ".$_SESSION["SESS_USER_ID"]);
				$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Password changed successfully.",'alert-success');
			}
			else
			{
				$this->tempVars['MSG'] = CommonFunctions::DisplayMsg("Wrong Old Password.",'alert-danger');
			}
		}
		$this->view("home/change_password");
	}

// END OF CLASS
}