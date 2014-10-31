<?php include_once('app/view/header.inc.php'); ?>
<script>
  function mostrarCustomers()
  {
	if( $("#user_role").val() == 'SubCustomer' )$("#subcustomers").show();
	else $("#subcustomers").hide();
  }
</script>

<div class="container">
	
	<div class="row">
		<div class="pull-left"> 
			<h2>Add User</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listuser" value="User List" class="btn btn-success" 
			onclick="location.href='<?php echo $this->buildUrl("user/listing"); ?>'" />
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
			<form role="form" action="" method="post">
				<div class="form-group">
					<label for="user_name">User Name<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name" 
					value="<?php echo $_POST['user_name']; ?>" required />
				</div>
				<div class="form-group">
					<label for="user_name">User Mail<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="user_mail" name="user_mail" placeholder="User Name" 
					value="<?php echo $_POST['user_mail']; ?>" required />
				</div>
				
				<div class="form-group">
					<label for="user_password">User Password<span class="required-img"> *</span></label>
					<input type="password" class="form-control" id="user_password" name="user_password" placeholder="Password" 
					  required />
				</div>
				
				
				<div class="form-group">
					<label for="user_role">User Roles<span class="required-img"> *</span></label>
					<div class="form-control">
						<select id="user_role" name="user_role" onchange="mostrarCustomers()">
							<option value="cliente">Cliente</option>
							<!--option value="respondente">Respondente</option-->
						
						</select>
					</div>
				</div>
				<div class="form-group" id="subcustomers"  style="display:none">
				
					<label for="user_role">Customer<span class="required-img"> *</span></label>
					<div class="form-control">
						<select id="subcustomer" name="customer_id" value="">
						<?php foreach($this->tempVars['CustomerList'] as $customerList){?>
							<option value="<?php echo $customerList->customer_id; ?>">
							    <?php echo $customerList->customer_name; ?>
							</option>
						<?php }?>
						</select>
						
					</div>
				</div>
								
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="user_form" value="yes"/>
			</form>
		</div>	
	</div>
</div>

<?php include_once('app/view/footer.inc.php'); ?>