<?php include_once('app/view/header.inc.php'); ?>
<script src="<?php echo SITE_URL;?>app/view/js/common.js" language="javascript"></script>
<div class="container">
	<div class="row">
		<div class="pull-left"> 
			<h2>Projects List</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listproject" value="Add Project" class="btn btn-default" 
			onclick="location.href='<?php echo $this->buildUrl("project/add"); ?>'" />
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
			<form name="customer_list" role="form" action="" method="post">
			<table class="table table-bordered table-striped">
				<thead>
					<?php 
					if(count($this->tempVars['ProjectList']))
					{ 
						?>
						<tr>
							<th>Project  #</th>
							<th>Project Name</th>
							<th>Customer Name</th>
							<th>Customer Comments</th>
							<th># of entries</th>
							<th>Deadline</th>
							<th>Status </th>
							<th>Action</th>
							<th><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
						</tr>
						</thead>
						<tbody>
						<?php
						foreach($this->tempVars['ProjectList'] as $projectlist)
						{
						 $submissionData= $this->model('submission')->selectAllSubmissions('*', array('project_id'=>intval($projectlist->project_id)), '');
						?>
							<tr>
								<td><?php echo $projectlist->project_id; ?></td>
								<td><?php echo $projectlist->project_name; ?></td>
								<td><?php echo $projectlist->customer_name; ?></td>
								<td style="max-width:250px;"><?php echo $projectlist->project_comments; ?></td>
								<td><?php echo count($submissionData);?></td>
								<td><?php echo date("d M Y", strtotime($projectlist->project_deadline)); ?></td>
								<td><?php echo ucfirst($projectlist->project_status); ?></td>
								<td>
									<a href="<?php echo $this->buildUrl('project/edit/'.$projectlist->project_id); ?>" >Edit Project</a>
									<br/>
									<a href="<?php echo $this->buildUrl('submission/listing/'.$projectlist->project_id); ?>" >Manage Submissions</a>
									<br/>
									<a href="<?php echo $this->buildUrl('project/zipFilesAndDownload/'.$projectlist->project_id); ?>" >Download Submissions</a>
								</td>
								<td><input type="checkbox" name="ids[]" value="<?php echo $projectlist->project_id; ?>"></td>
							</tr>
						<?php
						} 
					}
					else
					{
					?>
						<tr><td colspan="100%">You have no projects.</td></tr>
					<?php
					}
					?>
					
				</tbody>
				<?php 
					if(count($this->tempVars['ProjectList']))
					{ 
						?>
				<tfoot>
					<tr>
						<td colspan="100%">
							<div class="pull-right">
								<input name="footerAction" type="hidden" id="what" />
								<input type="button" name="btnDesign" value="Design" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnOpen" value="Open" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnDecision" value="Decision" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnClosed" value="Closed" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnAborted" value="Aborted" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
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
