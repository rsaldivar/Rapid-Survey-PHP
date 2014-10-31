<?php  
class ProjectController extends controller 
{
	var $helpers=array('paginator','simpleimage');		
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('project/index');
		
		$this->wtRedirect('project/listing');
		
	}
	
	function listing()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('project/listing');

		if ($_POST["frmSubmit"] == "yes") 
        {
	  	    /*Implementing footer action*/
			if($_POST['footerAction']=='Design' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['project_status']='design';
				$this->model('project')->updateProject($columnA,' project_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been Design successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Open' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['project_status']='open';
				$this->model('project')->updateProject($columnA,' project_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been Open successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Decision' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['project_status']='decision';
				$this->model('project')->updateProject($columnA,' project_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been Decision successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Closed' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['project_status']='closed';
				$this->model('project')->updateProject($columnA,' project_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been Closed successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Aborted' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['project_status']='aborted';
				$this->model('project')->updateProject($columnA,' project_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been Aborted successfully.','alert-success');
			}
			
			foreach($_POST['ids'] as $projid)
			{
				$searchitems = ' AND pro.project_id = '.$projid;
				$mailcontent = $this->model('project')->selectAllCustomerProjectJoin('pro.*,cust.customer_name,user.user_name',$searchitems);
				$this->sendMail($mailcontent);
				$searchitems ='';
				$this->wtRedirect('project/listing');
			}
			
			
			/*End*/ 
        }
		$searchitems .= " GROUP BY (project_id)";
		$Total= $this->model('project')->selectAllCustomerProjectJoin('pro.*,cust.customer_name',$searchitems);
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
		$this->tempVars['ProjectList'] =  $this->model('project')->selectAllCustomerProjectJoin('pro.*,cust.customer_name',$searchitems);
		//$this->pr($this->tempVars['ProjectList']); $this->view('project/list');
		if($_SESSION["SESS_USER_ROLE"] == 'Project Manager' )
		{
		  $this->view('project/list');
		
		
		}else
		{
		  $this->view('project/list_admin');
		
		
		}
	}	
	
	function add()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('project/add');
		
		if($_POST['project_form'] == 'yes')
		{
			$error ='';
			if($_POST['project_customer']==''){$error='Please select  customer.';}
			if($_POST['project_name']=='' && $error=='' ){$error='Please enter project name.';}
			if($_POST['project_description']=='' && $error==''){$error='Please enter project  description.';}
			if($_POST['project_deadline']=='' && $error==''){$error='Please enter project  deadline.';}
			$existingproject = $this->model('commonfuns')->CheckExistingproject('*',array('project_name'=>trim($_POST['project_name'])),'');
			
			if(count($existingproject) && $error=='')
			{
				$error = CommonFunctions::DisplayMsg(" Project name already exist(s), Please choose another project name.",'alert-warning');
			}
			$column = array();
			$column['customer']=mysql_real_escape_string($_POST['project_customer']);
			$column['manager']=mysql_real_escape_string($_POST['project_manager']);
			$column['project_name']=mysql_real_escape_string($_POST['project_name']);
			$column['project_description']=mysql_real_escape_string($_POST['project_description']);
			$column['project_deadline']=mysql_real_escape_string(date('Y-m-d', strtotime($_POST['project_deadline'])));
			$column['project_status']=mysql_real_escape_string($_POST['project_status']);
			$column['project_date_added']=date('Y-m-d');
			$column['project_posted_by']=$_SESSION["SESS_USER_ID"];
			if($_FILES["project_attachments"]["name"] != '')
			{
				$imageObj = new SimpleImage();
				$filename = time() . rand(1, 100) .$_FILES["project_attachments"]["name"];
				$fileext=array('docx','pdf','txt','gif','png','jpg','jpeg');
				$ext=$this->findexts($filename) ;
			
				if(in_array($ext,$fileext))
				{
					move_uploaded_file($_FILES['project_attachments']['tmp_name'], "media/projectfile/" . $filename);
					$column['project_attachment']=  $filename;		
				}
				else
				{
					$error.="<li>please upload valied file.</li>";
				}
			}
			if($error == '')
			{
				$this->model('project')->insertProject($column);
			 	$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Project added successfully.",'alert-success');
			 	//ENVIARCORREO CUSTOMER -> NUEVO PROYECTO
			 	$mailcontent =array( );
				$mailcontent['customer']=$_POST['project_customer'];//Trae el ID se usa en query $mailCustomer
				$mailcontent['manager']=$_POST['project_manager'];//Trae el ID se usa en query $mailManager
				$mailcontent['project_name']=mysql_real_escape_string($_POST['project_name']);
				$mailcontent['project_description']=mysql_real_escape_string($_POST['project_description']);
				$mailcontent['project_status']=mysql_real_escape_string($_POST['project_status']);
				$mailCustomer = $this->model('user')->selectAllUser("*","",'AND user_id = '.$_POST['project_customer']);
				$mailProjectManager = $this->model('user')->selectAllUser("*","",'AND user_id = '.$_POST['project_manager']);
				$this->sendMailAddProject($mailcontent,$mailCustomer,$mailProjectManager);
				$this->wtRedirect('project/listing');
			}	
		}
		$this->tempVars['CUSTOMERLIST']=$this->model("customer")->selectAllCustomerJoin(" And customer_status = 'active'") ;
		$this->tempVars['MANAGERLIST']=$this->model("user")->selectAllUser("*","","And user_role = 'Project Manager'") ;//columnas,condArr,condicionExtra
		$this->tempVars['MSG'] = $error;
		$this->view('project/add');
	}
	
	function edit()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('project/edit');
		
		$mailcontentrgs = func_get_args();
		$project_id = $mailcontentrgs[0];
		
		if($_POST['project_edit_form'] == 'yes')
		{
			$error ='';
			  
			if($_POST['project_customer']==''){$error='Please select  customer.';}
			if($_POST['project_name']=='' && $error=='' ){$error='Please enter project name.';}
			if($_POST['project_description']=='' && $error==''){$error='Please enter project  description.';}
			if($_POST['project_deadline']=='' && $error==''){$error='Please enter project  deadline.';}
			$existingproject = $this->model('commonfuns')->CheckExistingproject('*',array('project_name'=>trim($_POST['project_name'])),' and project_id !='.intval($mailcontentrgs[0]));
			if(count($existingproject) && $error=='')
			{
				$error = CommonFunctions::DisplayMsg(" Project  already exist(s), Please choose another project.",'alert-warning');
			}
			$column = array();
			$column['customer']=mysql_real_escape_string($_POST['project_customer']);
			$column['project_name']=mysql_real_escape_string($_POST['project_name']);
			$column['project_description']=mysql_real_escape_string($_POST['project_description']);
			$column['project_deadline']=mysql_real_escape_string(date('Y-m-d', strtotime($_POST['project_deadline'])));
			$column['project_status']=mysql_real_escape_string($_POST['project_status']);
			$column['project_modified_by']=$_SESSION["SESS_USER_ID"];
			if($_FILES["project_attachments"]["name"] != '')
			{
				$imageObj = new SimpleImage();
				$filename = time() . rand(1, 100) .$_FILES["project_attachments"]["name"];
				$fileext=array('docx','pdf','txt','gif','png','jpg','jpeg');
				$ext=$this->findexts($filename) ;
			
				if(in_array($ext,$fileext))
				{
					move_uploaded_file($_FILES['project_attachments']['tmp_name'], "media/projectfile/" . $filename);
			 		if(is_file(ROOT.'/media/projectfile/'.$_POST["prev_file"]))
					{
			 			unlink(ROOT.'/media/projectfile/'.$_POST["prev_file"]);
			 		}
					$column['project_attachment']=  $filename;			
				}
				else
				{
					$error.="<li>please upload valied file.</li>";
				}	
			}
			if($error == '')
			{
				$this->model('project')->updateProject($column,'  project_id ='.$project_id);
			 	$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Project updated successfully.",'alert-success');
			 	//ENVIARCORREO CUSTOMER -> EDICION PROYECTO
			 	//ENVIARCORREO CUSTOMER -> NUEVO PROYECTO
			 	$mailcontent =array( );
				$mailcontent['customer']=$_POST['project_customer'];//Trae el ID se usa en query $mailCustomer
				$mailcontent['project_name']=mysql_real_escape_string($_POST['project_name']);
				$mailcontent['project_description']=mysql_real_escape_string($_POST['project_description']);
				$mailcontent['project_status']=mysql_real_escape_string($_POST['project_status']);
				$mailCustomer = $this->model('user')->selectAllUser("*","",'AND user_id = '.$_POST['project_customer']);
				$this->sendMailEditProject($mailcontent,$mailCustomer,$mailProjectManager);
			 	$this->wtRedirect('project/listing');
			}	
		}
		$searchitems = " AND project_id = ".$project_id;
		$this->tempVars['ProjectList'] =  $this->model('project')->selectAllCustomerProjectJoin('pro.*,cust.customer_id',$searchitems);
		$this->tempVars['CUSTOMERLIST']=$this->model("customer")->selectAllCustomerJoin(" And customer_status = 'active'") ;
		$this->tempVars['MSG'] = $error;
		$this->view('project/edit');
	}		
	
	function findexts($filename) 
  	{ 
	 $filename = strtolower($filename) ; 
	 $exts = explode('.',$filename) ; 
	 $n = count($exts)-1; 
	 $exts = $exts[$n]; 
	 return $exts; 
    }
     //Add Project
	function sendMailAddProject($contentArray,$mailCustomer,$mailProjectManager)
	{
		//echo "<pre>"; print_r ($contentArray);echo "</pre>";
		$email_to = $mailCustomer[0]->user_name;
		$email_subject = SITE_TITLE.' - New Project : '.$contentArray[project_name].' -  ';
		$emailMsg = "<table border='0' cellpadding='2' cellspacing='0' width='100%'>
						<tr>
							<td> Project Manager :  <b>".$mailProjectManager[0]->user_name."</b>,</td>
						</tr>
						<tr>
							<td> Customer : <b>".$email_to."</b>,</td>
						</tr>
						<tr>
							<td> Project new <b>".$contentArray[project_name]."</b> status : <b>".$contentArray[project_status]."</b></td>
						</tr>
						<tr>
							<td>Thanks<br/>Admin<br>".SITE_TITLE."</td>
						</tr>
					</table>";
		//SMTP EXAMPLE		
		require 'framework/PHPMailerAutoload.php';	
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->setFrom(EMAIL_FROM);//Establecer a quien enviara
		$mail->FromName = SITE_TITLE;
		$mail->addAddress($email_to);//Establecer a quién es mail que se enviará 
		$mail->AddReplyTo(EMAIL_FROM,SITE_TITLE);
		$mail->AddBCC(EMAIL_FROM,SITE_TITLE);
		$mail->Subject = $email_subject ;
		$mail->IsHTML(true);  
		$mail->msgHTML($emailMsg);//HTML
		$mail->AltBody = $emailMsg;
		//$mail->addAttachment('images/');
		$mail->send();
	} //Add Project

	function sendMailEditProject($contentArray,$mailCustomer,$mailProjectManager)
	{
		//echo "<pre>"; print_r ($contentArray);echo "</pre>";
		$email_to = $mailCustomer[0]->user_name;
		$email_subject = SITE_TITLE.' - Edit Project : '.$contentArray[project_name].' ';
		$emailMsg = "<table border='0' cellpadding='2' cellspacing='0' width='100%'>
						<tr>
							<td> Customer : <b>".$email_to."</b>,</td>
						</tr>
						<tr>
							<td> Project <b>".$contentArray[project_name]."</b> </td>
						</tr>
						<tr>	
							<td> Description : <b>".$contentArray[project_description]."</b></td>
						</tr>
							<td> Project Status:  <b>".$contentArray[project_status]."</b> </td>
						</tr>
						<tr>
							<td>Thanks<br/>Admin<br>".SITE_TITLE."</td>
						</tr>
					</table>";

		require 'framework/PHPMailerAutoload.php';	
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->setFrom(EMAIL_FROM);//Establecer a quien enviara
		$mail->FromName = SITE_TITLE;
		$mail->addAddress($email_to);//Establecer a quién es mail que se enviará 
		$mail->AddReplyTo(EMAIL_FROM,SITE_TITLE);
		$mail->AddBCC(EMAIL_FROM,SITE_TITLE);
		$mail->Subject = $email_subject ;
		$mail->IsHTML(true);  
		$mail->msgHTML($emailMsg);//HTML
		$mail->AltBody = "Project Manager :  <b>".$mailProjectManager[0]->user_name."Project new : <b>".$contentArray[project_name]."</b> status : <b>".$contentArray[project_status];//TEXT
		//$mail->addAttachment('images/');
		$mail->send();
	}
	
        //Project Change Status
	function sendMail($contentArray)
	{
		$email_to = $contentArray[0]->user_name;
		$email_subject = SITE_TITLE.' - '.$contentArray[0]->project_name.' - Change Status ';
		$emailMsg = "<table border='0' cellpadding='2' cellspacing='0' width='100%'>
						<tr>
							<td> Dear <b>".$contentArray[0]->customer_name."</b>,</td>
						</tr>
						<tr>
							<td> New Project <b>".$contentArray[0]->project_name."</b> has been changed to <b>".$contentArray[0]->project_status."</b></td>
						</tr>
						<tr>
							<td>Thanks<br/>Admin<br>".SITE_TITLE."</td>
						</tr>
					</table>";	 

		require 'framework/PHPMailerAutoload.php';	
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->setFrom(EMAIL_FROM);//Establecer a quien enviara
		$mail->FromName = SITE_TITLE;
		$mail->addAddress($email_to);//Establecer a quién es mail que se enviará 
		$mail->AddReplyTo(EMAIL_FROM,SITE_TITLE);
		$mail->AddBCC(EMAIL_FROM,SITE_TITLE);
		$mail->Subject = $email_subject ;
		$mail->IsHTML(true);  
		$mail->msgHTML($emailMsg);//HTML
		$mail->AltBody = $emailMsg;
		//$mail->addAttachment('images/');
		$mail->send();
	}

	
	
	//function zipFilesAndDownload($file_names,$mailcontentrchive_file_name,$file_path)
	function zipFilesAndDownload($id)
	{
		$mailcontentrgs = func_get_args();
		$project_id = $mailcontentrgs[0];
		$Total= $this->model('submission')->selectAllSubmissions('*', array('project_id'=>$project_id), $searchitems);
		$file_names = array();
		foreach($Total as $tot)
		{
			$file_names[] = $tot->submission_image;
		}
		$file_path = ROOT.'/media/submissionfile/';
		$mailcontentrchive_file_name = "Project".$project_id."Submissions.zip";

		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		if ($zip->open($mailcontentrchive_file_name, ZIPARCHIVE::CREATE )!==TRUE) 
		{
    		exit("cannot open <$mailcontentrchive_file_name>\n");
		}
		//add each files of $file_name array to archive
		foreach($file_names as $files)
		{
  			$zip->addFile($file_path.$files,$files);
			//echo $file_path.$files,$files."<br />";
		}
		$zip->close();
		//then send the headers to foce download the zip file
		header("Content-type: application/zip"); 
		header("Content-Disposition: attachment; filename=$mailcontentrchive_file_name"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		readfile("$mailcontentrchive_file_name");
		exit;
	}
	
	
	
	
}
