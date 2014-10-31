<?php include_once('app/view/header.inc.php'); ?>
<div class="container">		
	<div class="row">
		<div class="pull-left"> 
			<h2>Add Submission</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listsubmissions" value="List Submissions" class="btn btn-default" 
			onclick="location.href='<?php echo $this->buildUrl("submission/listing/".$this->tempVars['ProjectID']); ?>'" />
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
					<label for="customer_name">Image</label><span class="required-img"> *</span>
  					<input type="file" class="form-control filevalidate" extentions="docx,pdf,txt,gif,png,jpg,jpeg"  name="image" id="image" required />
				</div>	
				
				<!-- <div class="form-group">   
					<div class="input-group input-group-lg">
  						<span class="input-group-addon">Focus &nbsp;&nbsp;&nbsp;&nbsp;</span>
  						<select class="form-control input-lg" name="focus">
							<?php 
							for($i=1; $i<=5; $i++)
							{
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
								echo "<option ".$sel.">".$i."</option>";
							}
							?>
						</select>
					</div>
				</div> -->	
				
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="submission_form" value="yes"/>
				<input type="hidden" name="project_id" value="<?php echo $this->tempVars['ProjectID']; ?>"/>
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


