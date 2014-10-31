<?php include_once('app/view/header.inc.php'); 
?>
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
			<h2>Edit Submission</h2>
		</div>
								<div class="pull-right lead"> 
			<input type="button" name="listsubmissions" value="List Submissions" class="btn btn-default" 
			onclick="location.href='<?php echo $this->buildUrl("submission/listing/".$this->tempVars['SubmissionList'][0]->project_id); ?>'" />
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
			<form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validateFrm(this)" >

				<div class="form-group">   
					<label for="image">Image</label><span class="required-img"> *</span>
  					<input type="file" class="form-control input-lgfilevalidate" extentions="docx,pdf,txt,gif,png,jpg,jpeg"  name="image" id="image" placeholder="image" />
				</div>
				<div class="form-group">   
					<?php if(is_file(ROOT.'/media/submissionfile/'.$this->tempVars['SubmissionList'][0]->submission_image))
					{
				    ?>
				     	<a class="fancybox" href="<?php echo SITE_URL."/media/submissionfile/".$this->tempVars['SubmissionList'][0]->submission_image;?>" data-fancybox-group="gallery"> <img src='<?php echo SITE_URL."/media/submissionfile/".$this->tempVars['SubmissionList'][0]->submission_image;?>'  width="100px" /> </a>
					<?php 
					} 
					?>
				</div>		
				
				<!-- <div class="form-group">   
					<div class="input-group input-group-lg">
  						<span class="input-group-addon">Focus &nbsp;&nbsp;&nbsp;&nbsp;</span>
  						<select class="form-control input-lg" name="focus">
							<?php 
							for($i=1; $i<=5; $i++)
							{
								$sel ='';
								if($this->tempVars['SubmissionList'][0]->submission_focus == $i)
								{
									$sel = "selected = selected";
								}
								echo "<option ".$sel.">".$i."</option>";
							}
							?>
						</select>
					</div>
				</div> -->	
				
				<!-- <div class="form-group">   
					<div class="input-group input-group-lg">
  						<span class="input-group-addon">Creativity</span>
  							<select class="form-control input-lg" name="creativity">
							<?php 
							for($i=1; $i<=5; $i++)
							{
								$sel ='';
								if($this->tempVars['SubmissionList'][0]->submission_creativity == $i)
								{
									$sel = "selected = selected";
								}
								echo "<option ".$sel.">".$i."</option>";
							}
							?>
						</select>
					</div>
				</div> -->	
				<!-- <div class="form-group">   
					<div class="input-group input-group-lg">
  						<span class="input-group-addon">Design &nbsp;&nbsp;&nbsp;</span>
  						<select class="form-control input-lg"  name="design">
							<?php 
							for($i=1; $i<=5; $i++)
							{
								$sel ='';
								if($this->tempVars['SubmissionList'][0]->submission_design == $i)
								{
									$sel = "selected = selected";
								}
								echo "<option ".$sel.">".$i."</option>";
							}
							?>
						</select>
					</div>
				</div> -->	
				
				<!-- <div class="form-group">   
					<div class="input-group input-group-lg">
  						<span class="input-group-addon">Fonts &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
  						<select class="form-control input-lg" name="fonts">
							<?php 
							for($i=1; $i<=5; $i++)
							{
								$sel ='';
								if($this->tempVars['SubmissionList'][0]->submission_fonts == $i)
								{
									$sel = "selected = selected";
								}
								echo "<option ".$sel.">".$i."</option>";
							}
							?>
						</select>
					</div>
				</div> -->	
				
				<!-- <div class="form-group">   
					<div class="input-group input-group-lg">
  						<span class="input-group-addon">Colors &nbsp;&nbsp;&nbsp;&nbsp;</span>
  						<select class="form-control input-lg" name="colors" >
							<?php 
							for($i=1; $i<=5; $i++)
							{
								$sel ='';
								if($this->tempVars['SubmissionList'][0]->submission_colors == $i)
								{
									$sel = "selected = selected";
								}
								echo "<option ".$sel.">".$i."</option>";
							}
							?>
						</select>
					</div>
				</div> -->	
				
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="submission_form_edit" value="yes"/>
				<input type="hidden" name="project_id"  value="<?php echo $this->tempVars['SubmissionList'][0]->project_id; ?>" />
				<input type="hidden" name="submission_id"  value="<?php echo $this->tempVars['SubmissionList'][0]->submission_id; ?>" />
				<input type="hidden" name="prev_file" value="<?php echo $this->tempVars['SubmissionList'][0]->submission_image; ?>" />
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


