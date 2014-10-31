<?php 
class UserController extends controller  
{
	var $helpers=array('paginator');
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('user/index');
		
		//die('User related functions coming soon.');
		$this->wtRedirect('user/listing');
	}
	
	
	function listing()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('user/listing');

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
		
		$searchitems = " AND rol !='administrador'";
		$Total= $this->model('user')->selectAlluser('*', array(), $searchitems);
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
		$this->tempVars['UserList'] = $this->model('user')->selectAlluser('*', array(), $searchitems);
		$this->view('user/list');
	}	
	
	function add()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('user/add');
		
		if($_POST['user_form'] == 'yes')
		{
		   
			$error ='';
			
			 if($_POST['user_name']==''){$error='Please enter user name.';}
			 if($_POST['user_mail']==''){$error='Please enter user email.';}
			 if($_POST['user_password']=='' && $error=='' ){$error='Please enter password.';}
			 if($_POST['user_role']=='' && $error==''){$error='Please select  user role.';}
			 
			 
			$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
			if(!preg_match($regex, strtolower($_POST['user_mail'])) && $error=='')
			{
				$error = CommonFunctions::DisplayMsg("Invalid  (email).",'alert-warning');
			}
			
			$existinguser = $this->model('commonfuns')->CheckExistingCustomerEmail('*',array('nombre'=>$_POST['user_name']),'');
			if(count($existinguser) && $error=='')
			{
				$error = CommonFunctions::DisplayMsg("Username  already exist. Please choose another email.",'alert-warning');
			}
			$existingemail = $this->model('commonfuns')->CheckExistingCustomerEmail('*',array('correo'=>$_POST['user_mail']),'');
			if(count($existingemail) && $error=='')
			{
				$error = CommonFunctions::DisplayMsg("User email  already exist. Please choose another email.",'alert-warning');
			}
			
			
			
			if($error == '')
			{
				$columnsArr = array();
				$columnsArr['nombre']   			= $_POST['user_name'];
				$columnsArr['correo']   			= $_POST['user_mail'];
				$columnsArr['rol']   				= $_POST['user_role'];
				$columnsArr['password']   			= $_POST['user_password'];
				$columnsArr['fecha'] 				= date('Y-m-d');
				$this->model('user')->insertuser($columnsArr);
				$id = mysql_insert_id();
				$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Dear General Manager, a new project manager has been added successfully.".$link,'alert-success');
				
				
				$this->wtRedirect('user/listing');
			}	
		}
		
		$this->tempVars['MSG'] = $error;
		$this->view('user/add');
	}
	
	function edit()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('user/edit');
		
		$args = func_get_args();
		$user_id = $args[0];
		
		if($_POST['user_form_edit'] == 'yes')
		{
			$error ='';
			
			 if($_POST['user_name']==''){$error='Please enter user name.';}
			 if($_POST['user_password']=='' && $error=='' ){$error='Please enter password.';}
			 if($_POST['user_role']=='' && $error==''){$error='Please select  user role.';}
			 
			 
			$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
			if(!preg_match($regex, strtolower($_POST['user_mail'])) && $error=='' )
			{
				$error = CommonFunctions::DisplayMsg("Invalid user email .",'alert-warning');
			}
			
			//VALIDAR CORREO
			$existingemail = $this->model('commonfuns')->CheckExistingCustomerEmail('*',array('correo'=>$_POST['user_mail']),' And id !='.$user_id);
			if(count($existingemail) && $error=='')
			{
				$error = CommonFunctions::DisplayMsg("user email already Exist's, Please choose another email.",'alert-warning');
			}
			if($error == '')
			{
				$columnsArr = array();
				$columnsArr['nombre']   = $_POST['user_name'];
				$columnsArr['correo']   = $_POST['user_mail'];
				$columnsArr['rol']  	= $_POST['user_role'];
				$columnsArr['password']   = $_POST['user_password'];
				
				print_r($columnsArr);
				$this->model('user')->updateuser($columnsArr, " id = ".$args[0]);
				if($_POST['user_role'] == 'cliente')
				{
					$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Dear General Manager, a new customer has been updated successfully.".$link,'alert-success');
				}	
				$this->wtRedirect('user/listing');
			}	
		}
		$this->tempVars['userList'] = $this->model('user')->selectAllUser('*', array('id'=>$user_id), '');
		
		$this->tempVars['MSG'] = $error;
		$this->view('user/edit');
	}	
	
}
