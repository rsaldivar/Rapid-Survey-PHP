<?php  
class SubmissionController extends controller  
{
	var $helpers=array('paginator','simpleimage');		
	function index()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('submission/index');
		
		//die('User related functions coming soon.');
		$this->wtRedirect('project/listing');
	}
	
	
	function listing()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('submission/listing'); 
		
		$args = func_get_args();
		$project_id = $args[0];
		if ($_POST["frmSubmit"] == "yes") 
        {
	  	    //Implementing footer action  
			if($_POST['footerAction']=='Activate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['submission_status']='active';
				$this->model('submission')->updateSubmission($columnA,'submission_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been activate successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Deactivate' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['submission_status']='inactive';
				$this->model('submission')->updateSubmission($columnA,' submission_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deactivate successfully.','alert-success');
			}
			if($_POST['footerAction']=='Discard' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['customer_status']='discard';
				$this->model('submission')->updateSubmission($columnA,' submission_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been discard successfully.','alert-success');
			}
			if($_POST['footerAction']=='Approve' && count($_POST['ids'])>=1)
			{
				$columnA=array();
				$columnA['customer_status']='not discard';
				$this->model('submission')->updateSubmission($columnA,' submission_id in('.implode(',',$_POST["ids"]).')');
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been approve successfully.','alert-success');
			}
			elseif($_POST['footerAction']=='Delete' && count($_POST['ids'])>=1)
			{
				$this->model('submission')-> deleteSubmissions($_POST['ids']);
				$this->tempVars['MSG']= CommonFunctions::DisplayMsg('Selected record(s) has been deleted successfully.','alert-success');
				//ENVIARCORREO SUBMISSION CONTROLLER -> NUEVO SUBMISSIOn
				
			}
			//End
        }
		
		$searchitems = " AND project_id = ".$project_id;
		if( $_POST['campo'] != "" )$searchitems  .= " ORDER BY ". $_POST['campo']." ".$_POST['order'] ;
		$Total= $this->model('submission')->selectAllSubmissions('*', array(), $searchitems);
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
		$this->tempVars['SubmissionList'] = $this->model('submission')->selectAllSubmissions('*', array(), $searchitems);
		$this->tempVars['ProjectNAME'] = $this->model('project')->selectAllProject('project_name', array('project_id'=>$project_id), '');
		$this->tempVars['ProjectID'] = $project_id;
		$this->view('submission/list');
	}	
	
	function add()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('submission/add');
		$args = func_get_args();
		$project_id = $args[0];
		
		if($_POST['submission_form'] == 'yes')
		{
			$error ='';
			if($error == '')
			{
				$columnsArr = array();
				$columnsArr['project_id']   		= $_POST['project_id'];
				if($_FILES["image"]["name"] != '')
				{
					$imageObj = new SimpleImage();
					$filename = time() . rand(1, 100) .$_FILES["image"]["name"];
					$fileext=array('docx','pdf','txt','gif','png','jpg','jpeg');
					$ext=$this->findexts($filename) ;
					if(in_array($ext,$fileext))
					{
						move_uploaded_file($_FILES['image']['tmp_name'], "media/submissionfile/" . $filename);
			 			$columnsArr['submission_image']= $filename;
					}
					else
					{
						$error.="<li>please upload a valid file.</li>";
					}
				}
				$columnsArr['submission_date'] 		= date('Y-m-d');
				
				if($error == '')
				{
					$this->model('submission')->insertSubmission($columnsArr);
					$id = mysql_insert_id();
					$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Submission inserted successfully.".$link,'alert-success');
					//ENVIARCORREO SUBMISSION CONTROLLER -> NUEVO SUBMISSIOn
					$mailcontent = $this->model('submission')->selectAllSubProjCustUser(array("sub.submission_id"=>$id),$searchItem);
					$mailcontent['accion']="add";
					$this->sendMail($mailcontent);
					$this->wtRedirect('submission/listing/'.$project_id);
				}
			}	
		}
		$this->tempVars['MSG'] = $error;
		$this->tempVars['ProjectID'] = $project_id;
		$this->view('submission/add');
	}
	
	function edit()
	{
		include("app/includes/commonfunctions.inc.php");
		CommonFunctions::isUserLogedIn();
		CommonFunctions::isUsersAccessable('submission/edit');
		$args = func_get_args();
		$submission_id = $args[0];
		
		if($_POST['submission_form_edit'] == 'yes')
		{
			$error ='';
			if($error == '')
			{
				$columnsArr = array();
				if($_FILES["image"]["name"] != '')
				{
					$imageObj = new SimpleImage();
					$filename = time() . rand(1, 100) .$_FILES["image"]["name"];
					$fileext=array('docx','pdf','txt','gif','png','jpg','jpeg');
					$ext=$this->findexts($filename) ;
					if(in_array($ext,$fileext))
					{
						move_uploaded_file($_FILES['image']['tmp_name'], "media/submissionfile/" . $filename);
						if(is_file(ROOT.'/media/submissionfile/'.$_POST['prev_file']))
						{
			 				unlink(ROOT.'/media/submissionfile/'.$_POST['prev_file']);
						}
			 			$columnsArr['submission_image']   	= $filename;
					}
					else
					{
						$error.="<li>please upload valied file.</li>";
					}	
				}
				$columnsArr['submission_date'] 		= date('Y-m-d');
				$cond = " submission_id = ".$_POST['submission_id'];
				$this->model('submission')->updateSubmission($columnsArr,$cond);
				$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Submission updated successfully.".$link,'alert-success');
				$mailcontent = $this->model('submission')->selectAllSubProjCustUser(array("sub.submission_id"=>$_POST['submission_id']),$searchItem);
				$mailcontent['accion']="edit";
				$this->sendMail($mailcontent);
				//ENVIAR CORREO SUBMISSION CONTROLLER -> EDIT SUBMISSION
				
				
				$this->wtRedirect('submission/listing/'.$_POST['project_id']);
			}	
		}
		$this->tempVars['MSG'] = $error;
		$this->tempVars['SubmissionList'] = $this->model('submission')->selectAllSubmissions('*', array('submission_id'=>$submission_id), $searchitems);
		$this->view('submission/edit');
	}	
	
	function views()
	{
		include("app/includes/commonfunctions.inc.php");
		if($_POST['comment_form'] =='yes')
		{
			$condArr = array();
			$condArr['submission_comments'] = $_POST['comments'];
			$condArr['submission_focus'] = $_POST['focus'];
			$condArr['submission_creativity'] = $_POST['creativity'];
			$condArr['submission_design'] = $_POST['design'];
			$condArr['submission_fonts'] = $_POST['fonts'];
			$condArr['submission_colors'] = $_POST['colors'];
			
			$cond = " submission_id = ".$_POST['submission_id'];
			$this->model('submission')->updateSubmission($condArr, $cond);
			$_SESSION['MSG'] = CommonFunctions::DisplayMsg("Comments updated successfully.",'alert-success'); 
			$this->wtRedirect('submission/listing/'.$_POST['project_id']);	
		}
		
		$args = func_get_args();
		$submission_id = $args[0];
		$this->tempVars['SubmissionList'] = $this->model('submission')->selectAllSubmissions('*', array('submission_id'=>$submission_id), '');
		$this->view('submission/view');
	}
	
	function findexts($filename) 
  	{ 
	 $filename = strtolower($filename) ; 
	 $exts = explode('.',$filename) ; 
	 $n = count($exts)-1; 
	 $exts = $exts[$n]; 
	 return $exts; 
	}
	
	function sendMail($contentArray)
	{
		$email_to = $contentArray[0]->user_name;
		
		if( $contentArray['accion'] == "edit")
		{	$email_subject = SITE_TITLE.' - '.$contentArray[0]->project_name.' - Update Submission ';
			$emailMsg = "<table border='0' cellpadding='2' cellspacing='0' width='100%'>
					<tr>
						<td colspan='50%'><b>Project:</b> ".$contentArray[0]->project_name."</td>
						<td colspan='50%'><b>Submission Date:</b> ".$contentArray[0]->submission_date."</td>
					</tr>
					<tr>
						<td colspan='100%'><b>Comments </b></td>
						<td colspan='100%'>".$contentArray[0]->submission_comments."</td>
					</tr>
					<tr>
						<th width='20%'><b>Focus</b></th>
						<th width='20%'><b>Creativity</b></th>
						<th width='20%'><b>Design</b></th>
						<th width='20%'><b>Fonts</b></th>
						<th width='20%'><b>Colors</b></th>
					</tr>
					<tr>
						<th>".$contentArray[0]->submission_focus."</th>
						<th>".$contentArray[0]->submission_creativity."</th>
						<th>".$contentArray[0]->submission_design."</th>
						<th>".$contentArray[0]->submission_fonts."</th>
						<th>".$contentArray[0]->submission_colors."</th>
					</tr>
					<tr>
							<td colspan='100%'>Thanks<br/>Admin<br>".SITE_TITLE."</td>
					</tr>
				</table>";
		}
		if( $contentArray['accion'] == "add")
		{	$email_subject = SITE_TITLE.' - '.$contentArray[0]->project_name.' - New Submission ';
			$emailMsg = "<table border='0' cellpadding='2' cellspacing='0' width='100%'>
					<tr>
						<td colspan='50%'><b>Project: </b>".$contentArray[0]->project_name."
						</td>
						<td> New Submission 
						</td>
						<td colspan='50%'><b>Submission Date: ".$contentArray[0]->submission_date."</b></td>
					</tr>
					<tr>
							<td colspan='100%'>Thanks<br/>Admin<br>".SITE_TITLE."</td>
					</tr>
				</table>";
		  
		}
		
		require 'framework/PHPMailerAutoload.php';	
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->setFrom(EMAIL_FROM);//Establecer quien envia
		$mail->FromName = SITE_TITLE;
                $mail->AddReplyTo(EMAIL_FROM,SITE_TITLE);
                $mail->AddBCC(EMAIL_FROM,SITE_TITLE);
		$mail->addAddress($email_to);//Establecer a quién es mail que se enviará 
		$mail->Subject = $email_subject ;
		$mail->WordWrap = 50;                              // set word wrap
                $mail->IsHTML(true);                               // send as HTML
		$mail->msgHTML($emailMsg);//HTML
		//$mail->addAttachment('images/');
		if (!$mail->send()) { echo "Mailer Error: " . $mail->ErrorInfo; } else { echo "Message sent!"; }
	}
	
	
	
}



	
	
