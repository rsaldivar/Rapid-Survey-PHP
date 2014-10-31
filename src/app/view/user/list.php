<?php include_once('app/view/header.inc.php'); ?>
<script src="<?php echo SITE_URL;?>app/view/js/common.js" language="javascript"></script>
<div class="container">
		<div class="row">
		<div class="pull-left"> 
			<h2>Users List</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listuser" value="Add User" class="btn btn-success" 
			onclick="location.href='<?php echo $this->buildUrl("user/add"); ?>'" />
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
			<form name="user_list" role="form" action="" method="post">
			<table class="table table-bordered table-striped">
				<thead>
					<?php 
					if(count($this->tempVars['UserList']))
					{ 
						?>
						<tr>
							<th>User #</th>
							<th>User Name</th>
							<th>User Role</th>
							<th>Status</th>
							<th>Action</th>
							<th><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
						</tr>
						</thead>
						<tbody>
						<?php
						foreach($this->tempVars['UserList'] as $userlist)
						{
						?>
							<tr>
								<td><?php echo $userlist->id; ?></td>
								<td><?php echo $userlist->nombre; ?></td>
								<td><?php echo $userlist->rol; ?></td>
								<td><?php echo ucfirst($userlist->estado); ?></td>
								<td><a href="<?php echo $this->buildUrl('user/edit/'.$userlist->id); ?>" >Edit</a></td>
								<td><input type="checkbox" name="ids[]" value="<?php echo $userlist->id; ?>"></td>
							</tr>
						<?php
						} 
					}
					else
					{
					?>
						<tr><td>Currently their are no records to display.</td></tr>
					<?php
					}
					?>
					
				</tbody>
				<?php 
					if(count($this->tempVars['UserList']))
					{ 
						?>
				<tfoot>
					<tr>
						<td colspan="100%">
							<div class="pull-right">
								<input name="footerAction" type="hidden" id="what" />
								<input type="button" name="btnActivate" value="Activate" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
								<input type="button" name="btnDeActivate" value="Deactivate" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
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
