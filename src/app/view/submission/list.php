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
	
<script type="text/javascript">
var antCampo="<?php echo $_POST["campo"];?>";
$("#<?php echo $_POST["columnSelect"];?>").addClass("order_active");

var ordenSelect="<?php echo $_POST["order"];?>";
if(ordenSelect == "ASC"){$("#<?php echo $_POST["columnSelect"];?>").append('<span style="position: relative;top: 2px;left: 10px;" class="glyphicon glyphicon-chevron-down"></span>');}
else {$("#<?php echo $_POST["columnSelect"];?>").append('<span style="position: relative;top: 2px; left: 10px;" class="glyphicon glyphicon-chevron-up"></span>');}

function ordenar(campo,columnSelect)
{ var parametros;
  if(campo == antCampo){orden="DESC";}else{ orden = "ASC";}
  if(ordenSelect == "DESC" ){orden="ASC";}
  parametros = {campo: campo , order:orden, campoant:campo, columnSelect:columnSelect };
  $.post("", parametros , function(server_response){   $("#ant").html(server_response); });
}

</script>
<div id="ant">
<div class="container">
		<div class="row">
		<div class="pull-left"> 
			<h2>Submission List</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listcustomer" value="Add Submission" class="btn btn-default" 
			onclick="location.href='<?php echo $this->buildUrl("submission/add/".$this->tempVars['ProjectID']); ?>'" />
		</div>
	</div>
	<div class="row"><div class="clear">&nbsp;</div><hr></div>	
	<div class="row">		
		<?php 
		echo $_SESSION['MSG'];
		echo $this->tempVars['MSG'];
		$_SESSION['MSG'] = '';
		$this->tempVars['MSG'] ='';
		?>
		<div class="table-responsive">
			<form name="submission_list" role="form" action="" method="post">
			<table class="table table-bordered table-striped">
				<thead>
					<?php 
					if(count($this->tempVars['SubmissionList']))
					{ 
						?>
						<tr style="cursor: pointer;">
							<th id="column1" onclick="ordenar('submission_id','column1')">Submission</th>
							<th id="column2" onclick="ordenar('submission_comments','column2')">Comments</th>
							<th id="column3" onclick="ordenar('submission_focus','column3')">Focus</th>
							<th id="column4" onclick="ordenar('submission_creativity','column4')">Creativity</th>
							<th id="column5" onclick="ordenar('submission_design','column5')">Design</th>
							<th id="column6" onclick="ordenar('submission_fonts','column6')">Fonts </th>
							<th id="column7" onclick="ordenar('submission_colors','column7')">Colors </th>
							<th id="column8" onclick="ordenar('submission_colors','column8')">Prom</th>
							<th id="column9" onclick="ordenar('submission_prom','column9')" style="min-width: 100px;">Date</th>
							<th>Status </th>
							<th>Customer Status</th>
							<th>Action</th>
							<th><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
						</tr>
						</thead>
						<tbody>
						<?php
						foreach($this->tempVars['SubmissionList'] as $submissionlist)
						{
						?>
							<tr>
								<td><?php 
								  
								    if(is_file(ROOT.'/media/submissionfile/'.$submissionlist->submission_image)){
				                          ?>
										  <a class="fancybox" href="<?php echo SITE_URL."/media/submissionfile/".$submissionlist->submission_image;?>" data-fancybox-group="gallery"> <img src='<?php echo SITE_URL."/media/submissionfile/".$submissionlist->submission_image;?>'  width="100px" /> </a>
				                    
				                               <?php } ?>
								</td>
								<td><?php echo $submissionlist->submission_comments; ?></td>
								<td><?php if($submissionlist->submission_focus !='0'){echo $submissionlist->submission_focus;} ?></td>
								<td><?php if($submissionlist->submission_creativity !='0'){echo $submissionlist->submission_creativity;} ?></td>
								<td><?php if($submissionlist->submission_design !='0'){echo $submissionlist->submission_design;} ?></td>
								<td><?php if($submissionlist->submission_fonts !='0'){echo $submissionlist->submission_fonts;} ?></td>
								<td><?php if($submissionlist->submission_colors !='0'){echo $submissionlist->submission_colors;} ?></td> 
								<td><?php if($submissionlist->submission_colors !='0'){
								  $promedio = ($submissionlist->submission_colors +$submissionlist->submission_fonts + $submissionlist->submission_design + $submissionlist->submission_creativity + $submissionlist->submission_focus )/5;
								  echo $promedio;
								
								} ?></td>
								
								<td><?php if($submissionlist->submission_date !=""){echo $submissionlist->submission_date;} ?></td> 
								<td><?php echo ucfirst($submissionlist->submission_status); ?></td>
								<td>
								  <?php echo $submissionlist->customer_status; ?>
								</td>
								<td>
									<a href="<?php echo $this->buildUrl('submission/views/'.$submissionlist->submission_id); ?>" >View</a>
									<br/>
									<a href="<?php echo $this->buildUrl('submission/edit/'.$submissionlist->submission_id); ?>" >Edit</a>
								</td>
								<td><input type="checkbox" name="ids[]" value="<?php echo $submissionlist->submission_id; ?>"></td>
							</tr>
						<?php
						} 
					}
					else
					{
					?>
						<tr><td colspan="100%">You have no submissions in this project.</td></tr>
					<?php
					}
					?>
					
				</tbody>
				<?php
				if(count($this->tempVars['SubmissionList']))
				{ 
				?>
				<tfoot>
					<tr>
						<td colspan="100%">
							<div class="pull-right">
								<input name="footerAction" type="hidden" id="what" />
								<input type="button" name="btnActivate" value="Activate" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnDeActivate" value="Deactivate" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnActivate" value="Approve" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnActivate" value="Discard" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnDelete" value="Delete" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="hidden" name="frmSubmit" value="yes" />
							</div>
						</td>
					</tr>
				</tfoot>
				<?php
				}
				?>
			  </table>
			  </form>
			   <div class="text-center"><?php echo $this->tempvars["PAGING"];?></div>
		</div>			  
	</div>
</div>
<?php include_once('app/view/footer.inc.php'); ?>
</div>