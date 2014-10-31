<!--ARCHIVO QUE MUESTRA DE MANERA EDITABLE UNA SUBMISSION-->
<?php include_once('app/view/header.inc.php'); ?>
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
			<h2>Submission Details</h2>
		</div>
	</div>
	<div class="row"><div class="clear">&nbsp;</div><hr></div>		
	<div class="row">
		<div class="col-md-10">
			<div class="form-group">   
  				<label for="image">Image</label>
  				<?php 
				if(is_file(ROOT.'/media/submissionfile/'.$this->tempVars['SubmissionList'][0]->submission_image))
				{
				?>
					<p><a class="fancybox" href="<?php echo SITE_URL."/media/submissionfile/".$this->tempVars['SubmissionList'][0]->submission_image;?>" data-fancybox-group="gallery"> 
						<img src='<?php echo SITE_URL."/media/submissionfile/".$this->tempVars['SubmissionList'][0]->submission_image;?>' width="100px" /> 
					</a></p>
				<?php 
				} 
				?>
			</div>
		</div>	
	</div>
	
	<div class="row">
	<hr/ class='hr'>
	</div>
	<div class="row">
		<div class="col-md-10">
			<?php 
			echo $_SESSION['MSG'];
			echo $this->tempVars['MSG'];
			$_SESSION['MSG'] = '';
			$this->tempVars['MSG'] ='';
			?>
			<form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validateFrm(this)" >
				
				<div class="form-group">
					<label for="customer_name">Comments</label>
					<textarea class="form-control" id="comments" name="comments" cols="50" rows="10" /><?php echo $this->tempVars['SubmissionList'][0]->submission_comments; ?></textarea>
				</div>
				
				<div class="form-group">   
					<div class="input-group input-group-lg">
  						Focus &nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="focus" value="1" id="focus1" <?php if($this->tempVars['SubmissionList'][0]->submission_focus == 1){echo 'checked';} else{echo 'checked';}?>  >&nbsp;<label for="focus1">1</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="focus" value="2" id="focus2" <?php if($this->tempVars['SubmissionList'][0]->submission_focus == 2){echo 'checked';}?> >&nbsp;<label for="focus2">2</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="focus" value="3" id="focus3" <?php if($this->tempVars['SubmissionList'][0]->submission_focus == 3){echo 'checked';}?> >&nbsp;<label for="focus3">3</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="focus" value="4" id="focus4" <?php if($this->tempVars['SubmissionList'][0]->submission_focus == 4){echo 'checked';}?> >&nbsp;<label for="focus4">4</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="focus" value="5" id="focus5" <?php if($this->tempVars['SubmissionList'][0]->submission_focus == 5){echo 'checked';}?> >&nbsp;<label for="focus5">5</label>&nbsp;&nbsp;&nbsp;
					</div>
				</div>	
				
				<div class="form-group">   
					<div class="input-group input-group-lg">
  						Creativity
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="creativity" value="1" id="creativity1" <?php if($this->tempVars['SubmissionList'][0]->submission_creativity == 1){echo 'checked';} else{echo 'checked';}?> >&nbsp;<label for="creativity1">1</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="creativity" value="2" id="creativity2" <?php if($this->tempVars['SubmissionList'][0]->submission_creativity == 2){echo 'checked';}?> >&nbsp;<label for="creativity2">2</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="creativity" value="3" id="creativity3" <?php if($this->tempVars['SubmissionList'][0]->submission_creativity == 3){echo 'checked';}?> >&nbsp;<label for="creativity3">3</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="creativity" value="4" id="creativity4" <?php if($this->tempVars['SubmissionList'][0]->submission_creativity == 4){echo 'checked';}?> >&nbsp;<label for="creativity4">4</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="creativity" value="5" id="creativity5" <?php if($this->tempVars['SubmissionList'][0]->submission_creativity == 5){echo 'checked';}?> >&nbsp;<label for="creativity5">5</label>&nbsp;&nbsp;&nbsp;
					</div>
				</div>	
				<div class="form-group">   
					<div class="input-group input-group-lg">
  						Design &nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="design" value="1" id="design1" <?php if($this->tempVars['SubmissionList'][0]->submission_design == 1){echo 'checked';} else{echo 'checked';}?> >&nbsp;<label for="design1">1</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="design" value="2" id="design2" <?php if($this->tempVars['SubmissionList'][0]->submission_design == 2){echo 'checked';}?> >&nbsp;<label for="design2">2</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="design" value="3" id="design3" <?php if($this->tempVars['SubmissionList'][0]->submission_design == 3){echo 'checked';}?> >&nbsp;<label for="design3">3</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="design" value="4" id="design4" <?php if($this->tempVars['SubmissionList'][0]->submission_design == 4){echo 'checked';}?> >&nbsp;<label for="design4">4</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="design" value="5" id="design5" <?php if($this->tempVars['SubmissionList'][0]->submission_design == 5){echo 'checked';}?> >&nbsp;<label for="design5">5</label>&nbsp;&nbsp;&nbsp;
					</div>
				</div>	
				
				<div class="form-group">   
					<div class="input-group input-group-lg">
  						Fonts &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="fonts" value="1" id="fonts1" <?php if($this->tempVars['SubmissionList'][0]->submission_fonts == 1){echo 'checked';} else{echo 'checked';}?> >&nbsp;<label for="fonts1">1</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="fonts" value="2" id="fonts2" <?php if($this->tempVars['SubmissionList'][0]->submission_fonts == 2){echo 'checked';}?> >&nbsp;<label for="fonts2">2</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="fonts" value="3" id="fonts3" <?php if($this->tempVars['SubmissionList'][0]->submission_fonts == 3){echo 'checked';}?> >&nbsp;<label for="fonts3">3</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="fonts" value="4" id="fonts4" <?php if($this->tempVars['SubmissionList'][0]->submission_fonts == 4){echo 'checked';}?> >&nbsp;<label for="fonts4">4</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="fonts" value="5" id="fonts5" <?php if($this->tempVars['SubmissionList'][0]->submission_fonts == 5){echo 'checked';}?> >&nbsp;<label for="fonts5">5</label>&nbsp;&nbsp;&nbsp;
						
					</div>
				</div>	
				
				<div class="form-group">   
					<div class="input-group input-group-lg">
  						Colors &nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="colors" value="1" id="colors1" <?php if($this->tempVars['SubmissionList'][0]->submission_colors == 1){echo 'checked';} else{echo 'checked';}?> >&nbsp;<label for="colors1">1</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="colors" value="2" id="colors2" <?php if($this->tempVars['SubmissionList'][0]->submission_colors == 2){echo 'checked';}?> >&nbsp;<label for="colors2">2</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="colors" value="3" id="colors3" <?php if($this->tempVars['SubmissionList'][0]->submission_colors == 3){echo 'checked';}?> >&nbsp;<label for="colors3">3</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="colors" value="4" id="colors4" <?php if($this->tempVars['SubmissionList'][0]->submission_colors == 4){echo 'checked';}?> >&nbsp;<label for="colors4">4</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="colors" value="5" id="colors5" <?php if($this->tempVars['SubmissionList'][0]->submission_colors == 5){echo 'checked';}?> >&nbsp;<label for="colors5">5</label>&nbsp;&nbsp;&nbsp;
					</div>
				</div>	
				
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="comment_form" value="yes"/>
				<input type="hidden" name="project_id" value="<?php echo $this->tempVars['SubmissionList'][0]->project_id; ?>"/>
				<input type="hidden" name="submission_id" value="<?php echo $this->tempVars['SubmissionList'][0]->submission_id; ?>"/>
			</form>
		</div>	
	</div>
	
</div>

<?php include_once('app/view/footer.inc.php'); ?>

