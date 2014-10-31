<?php include_once('app/view/header.inc.php'); ?>
<div class="container">
	<div class="row">
		<div class="pull-left"> 
			<h2>Change Password</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listcustomer" value="Customer List" class="btn btn-success" 
			onclick="location.href='<?php echo $this->buildUrl("customer/listing"); ?>'" />
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
			<form role="form" action="" method="post" onSubmit="return valid();">
				
				<div class="form-group">
					<label for="old_password">Old Password<span class="required-img"> *</span></label>
					<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" required />
				</div>
				<div class="form-group">
					<label for="new_password">New Password<span class="required-img"> *</span></label>
					<input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password"  required />
				</div>
				<div class="form-group">
					<label for="confirm_password">Confirm Password<span class="required-img"> *</span></label>
					<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="change_password" value="yes"/>
			</form>
		</div>	
	</div>
</div>
<script>
	function valid()
	{
		if($('#new_password').val() != $('#confirm_password').val())
		{
			alert('New Password and Confirm Password not matched');
			return false;
		}
		else
		{
			return true;
		}
	}
</script>

<?php include_once('app/view/footer.inc.php'); ?>