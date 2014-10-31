<?php include_once('app/view/header.inc.php'); ?>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script>
$(function() {
$( "#project_deadline" ).datepicker({ dateFormat: 'dd-M-yy' });
});
</script>
<script type="text/javascript">
		$(document).ready(function() {
			$('.fancybox').fancybox({
					padding : 5,
					width : 300,
					height : 250,
					openEffect	: 'elastic',
    	            closeEffect	: 'elastic',
					autoSize	: true,
			});
		});
	</script>
<div class="container">
  <div class="row">
		<div class="pull-left"> 
			<h2>Edit Project</h2>
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
              <option value="<?php echo $custlist->customer_id;?>" <?php if($this->tempVars['ProjectList'][0]->customer==$custlist->customer_id){echo 'selected';} ?> ><?php echo $custlist->customer_name;?></option>
              <?php 	}

						 }
						 ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="project_name">Project Name<span class="required-img"> *</span></label>
          <input type="text" class="form-control" id="project_name" name="project_name" placeholder="Project Name" 
					value="<?php echo $this->tempVars['ProjectList'][0]->project_name; ?>" required />
        </div>
        <div class="form-group">
          <label for="project_description">Project Description</label>
          <textarea class="form-control" id="project_description" name="project_description" placeholder="Project  Description" cols="20" rows="10"  required><?php echo nl2br($this->tempVars['ProjectList'][0]->project_description); ?></textarea>
        </div>
        <div class="form-group">
          <label for="project_description">Customer Comments</label>
          <textarea class="form-control" id="project_description" name="" placeholder="Not comments" cols="20" rows="10" readonly><?php echo nl2br($this->tempVars['ProjectList'][0]->project_comments); ?></textarea>
        </div>
        <div class="form-group">
          <label for="project_attachments ">Attachment</label>
          <input type="file" class="form-control filevalidate"   extentions="docx,pdf,txt,gif,png,jpg,jpeg"  id="project_attachments" name="project_attachments"   />
          <?php  if(is_file(ROOT.'/media/projectfile/'.$this->tempVars['ProjectList'][0]->project_attachment)){
				   ?>
          <a class="fancybox" href="<?php echo SITE_URL."/media/projectfile/".$this->tempVars['ProjectList'][0]->project_attachment;?>" data-fancybox-group="gallery"> <img src='<?php echo SITE_URL."/media/projectfile/".$this->tempVars['ProjectList'][0]->project_attachment;?>' title="<?php echo $this->tempVars['ProjectList'][0]->project_name;?>" width="100px" /> </a>
          <?php } ?>
        </div>
        <div class="form-group">
          <label for="project_status">Status <span class="required-img"> *</span></label>
          <div class="form-control">
            <select id="project_status" name="project_status">
              <option value="design" <?php if($this->tempVars['ProjectList'][0]->project_status=='design'){echo 'selected';} ?>  >Design</option>
              <option value="open" <?php if($this->tempVars['ProjectList'][0]->project_status=='open'){echo 'selected';} ?> >Open</option>
              <option value="decision" <?php if($this->tempVars['ProjectList'][0]->project_status=='decision'){echo 'selected';} ?> >Decision</option>
              <option value="closed" <?php if($this->tempVars['ProjectList'][0]->project_status=='Closed'){echo 'selected';} ?> >Closed</option>
              <option value="aborted" <?php if($this->tempVars['ProjectList'][0]->project_status=='aborted'){echo 'selected';} ?> >Aborted</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="project_deadline">Project Deadline <span class="required-img"> *</span></label>
          <input type="text" class="form-control datepicker" id="project_deadline" name="project_deadline" placeholder="Project Deadline" 
					value="<?php echo date("d M Y", strtotime($this->tempVars['ProjectList'][0]->project_deadline)); ?>" required />
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <input type="hidden" name="project_edit_form" value="yes"/>
        <input type="hidden" name="prev_file" value="<?php echo $this->tempVars['ProjectList'][0]->project_attachment; ?>" />
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
