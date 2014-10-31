<?php include_once('app/view/header.inc.php'); ?>
<script src="<?php echo SITE_URL;?>app/view/js/common.js" language="javascript"></script>
<div class="container">
		
			<div class="row">
				<div class="pull-left"> 
					<h2>Customers List</h2>
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
					if(count($this->tempVars['CustomerList']))
					{ 
						?>
						<tr>
							<th>Customer #</th>
							<th>Customer Name</th>
							<th>Customer Email</th>
							<th>Status</th>
							<th>Action</th>
							<?php
							if($_SESSION["SESS_USER_ROLE"] != "cliente")
							{
							?>
								<th><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
							<?php
							}
							?>	
						</tr>
						</thead>
						<tbody>
						<?php
						foreach($this->tempVars['CustomerList'] as $customerlist)
						{
						?>
							<tr>
								<td><?php echo $customerlist->id; ?></td>
								<td><?php echo $customerlist->nombre; ?></td>
								<td><?php echo $customerlist->correo; ?></td>
								<td><?php echo ucfirst($customerlist->estado); ?></td>
								<td><a href="<?php echo $this->buildUrl('customer/edit/'.$customerlist->id); ?>" >Edit</a></td>
								<?php
								if($_SESSION["SESS_USER_ROLE"] != "cliente")
								{
								?>
									<td><input type="checkbox" name="ids[]" value="<?php echo $customerlist->id; ?>"></td>
								<?php
								}
								?>	
							</tr>
						<?php
						} 
					}
					else
					{
					?>
						<tr><td colspan="100%">Currently their are no records to display.</td></tr>
					<?php
					}
					?>
					
				</tbody>
				<?php
				if($_SESSION["SESS_USER_ROLE"] != "customer")
				{
				?>
				<?php 
					if(count($this->tempVars['CustomerList']))
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
				}
				?>
			  </table>
			  </form>
			   <div class="text-center"><?php echo $this->tempvars["PAGING"];?></div>
		</div>			  
	</div>
</div>
<?php include_once('app/view/footer.inc.php'); ?>
