<?php include_once('app/view/header.inc.php'); ?>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
 <script>
$(function() {
$( "#project_deadline" ).datepicker({ dateFormat: 'dd-M-yy' });
});
</script>
<div class="container">
		<div class="row">
		<div class="pull-left"> 
			<h2>Add Project</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listproject" value="Project List" class="btn btn-default" 
			onclick="location.href='<?php echo $this->buildUrl("project/listing"); ?>'" />
		</div>
	</div>
	<div class="row"><div class="clear">&nbsp;</div><hr></div>		
	<div class="row">
		<div class="col-md-12">
			<?php 
			echo $_SESSION['MSG'];
			echo $this->tempVars['MSG'];
			$_SESSION['MSG'] = '';
			$this->tempVars['MSG'] ='';
			?>
			<form role="form" action="" method="post"  enctype="multipart/form-data" onsubmit="return validateFrm(this)" >
				<div class="form-group">
					<label for="project_customer">Customer <span class="required-img"> *</span></label>
					<div class="form-control">
						<select id="project_customer" name="project_customer">
                         <?php if(count($this->tempVars['CUSTOMERLIST'])){ 
						       		foreach($this->tempVars['CUSTOMERLIST'] as $custlist){?>
										
										<option value="<?php echo $custlist->customer_id;?>"><?php echo $custlist->user_name;?></option>
										
									<?php 	}
						
							
						 }
						 ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="project_customer">Project Manager <span class="required-img"> *</span></label>
					<div class="form-control">
						<select id="project_customer" name="project_manager">
                         <?php if(count($this->tempVars['MANAGERLIST'])){ 
						       		foreach($this->tempVars['MANAGERLIST'] as $managerlist){?>
										
										<option value="<?php echo $managerlist->user_id;?>"><?php echo $managerlist->user_name;?></option>
										
									<?php 	}
						
							
						 }
						 ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					
					<label for="project_name">Project Name<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="project_name" name="project_name" placeholder="Project Name" 
					value="<?php echo $_POST['project_name']; ?>" required />
				</div>
				
				
				<div class="form-group">
					<label for="project_description">Project Description</label>
					<textarea class="form-control" id="project_description" name="project_description" placeholder="Project  Description" cols="50" rows="10" />&nbsp;<?php echo $_POST['project_description']; ?></textarea>
				</div>
				
				
				<div class="form-group">
					<label for="project_attachments ">Attachment</label>
					<input type="file" class="form-control filevalidate"   extentions="docx,pdf,txt,gif,png,jpg,jpeg"  id="project_attachments" name="project_attachments"/>
				</div>
				
				
				<div class="form-group">
					<label for="project_status">Status <span class="required-img"> *</span></label>
					<div class="form-control">
						<select id="project_status" name="project_status">
							<option value="design">Design</option>
							<option value="open">Open</option>
							<option value="decision">Decision</option>
							<option value="closed">Closed</option>
							<option value="aborted">Aborted</option>
						
						</select>
					</div>
				</div>
				
				
				
				<div class="form-group">
					
					<label for="project_deadline">Project Deadline <span class="required-img"> *</span></label>
					<input type="text" class="form-control datepicker" id="project_deadline" name="project_deadline" placeholder="Project Deadline" 
					value="<?php echo $_POST['project_deadline']; ?>" />
				</div>
				
				
				
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="project_form" value="yes"/>
			</form>
		</div>	
	</div>
</div>

<?php include_once('app/view/footer.inc.php'); ?>

<script>
function validateFrm(obj) {
	if(!validate()) {
		return false;
	}
}
</script>

