<!--CUSTOMERS VIEW-->
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
				<h2>Projects List</h2>
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
							<th># of entries</th>
							<th>Image</th>
							<th>Deadline</th>
							<th>Status </th>
							<th>Action</th>
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
								<td><?php echo $projectlist->project_name; ?></td><!--?php echo $this->tempVars['Project_Details'][0]->project_name; ?></p-->
								<td><?php echo $projectlist->customer_name; ?></td>
								<td><?php echo count($submissionData);?></td>
								
								<td>
								<?php if(is_file(ROOT.'/media/projectfile/'.$projectlist->project_attachment)){?>
								<a class="fancybox" href="<?php echo SITE_URL."/media/projectfile/".$projectlist->project_attachment;?>" data-fancybox-group="gallery"> 
								<img src='<?php echo SITE_URL."/media/projectfile/".$projectlist->project_attachment;?>'" width="100px" /> </a>
							   	<?php } ?>
								</td>
								<td><?php echo date('d M Y',strtotime($projectlist->project_deadline)); ?></td>
								<td><?php echo ucfirst($projectlist->project_status); ?></td>
								<td>
									<a href="<?php echo $this->buildUrl('customer/project-details/'.$projectlist->project_id); ?>" >Project Details</a>
									<br/>
									<a href="<?php echo $this->buildUrl('customer/view-submissions/'.$projectlist->project_id); ?>" >Manager Submissions</a>
									<br/>
									
									<a href="<?php echo $this->buildUrl('project/zipFilesAndDownload/'.$projectlist->project_id); ?>" >Download Submissions</a>
								</td>
							</tr>
						<?php
						} 
					}
					else
					{
						echo "<tr><td colspan='100%'><b>You have no open projects</b></td></tr>";
					}
					?>
					
				</tbody>
			  </table>
			  </form>
			   <div class="text-center"><?php echo $this->tempvars["PAGING"];?></div>
			  </div><!--tableResponsiveList-->
				  
	</div><!--row-->
</div><!--container-->
<span  style="display:none" id="mensaje"></span>
<?php include_once('app/view/footer.inc.php'); ?>
