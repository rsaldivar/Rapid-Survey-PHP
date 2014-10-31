<?php include_once('app/view/header.inc.php'); ?>
<div class="container">
	<div class="row">
		<div class="pull-left"> 
			<h2>Add Customer</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listcustomer" value="Customer List" class="btn btn-default" 
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
			<form role="form" action="" method="post">
				<div class="form-group">
					<label for="customer_name">Customer Name<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="customer_name" name="name" placeholder="Customer Name" 
					value="<?php echo $_POST['customer_name']; ?>" required />
				</div>
				<div class="form-group">
					<label for="customer_username">Customer Email</label>&nbsp;(Username)<span class="required-img"> *</span> 
					<input type="email" class="form-control" id="customer_email" name="email" placeholder="Customer Email" 
					value="<?php echo $_POST['customer_email']; ?>"  required />
				</div>
				<div class="form-group">
					<label for="customer_password">Customer Password<span class="required-img"> *</span></label>
					<input type="password" class="form-control" id="customer_password" name="password" placeholder="Password" 
					value="<?php echo $_POST['customer_password']; ?>"  required />
				</div>
				
				
				<div class="form-group">
					<label for="customer_address">Customer Address</label>
					<textarea class="form-control" id="customer_address" name="address" placeholder="Customer Address" cols="50" rows="10"><?php echo $_POST['customer_address']; ?></textarea>
				</div>
				
				<p><h2>Customer Contact</h2></p>
				<div class="form-group">
					
					<label for="customer_contact_name">Name<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="detail_name" name="detail_name" placeholder="Customer Name" 
					value="<?php echo $_POST['customer_contact_name']; ?>" required />
				</div>
				<div class="form-group">
					<label for="customer_contact_email">Email<span class="required-img"> *</span></label>
					<input type="email" class="form-control" id="detail_email" name="detail_email" placeholder="Customer Email" 
					value="<?php echo $_POST['customer_contact_email']; ?>" required />
				</div>
				<div class="form-group">	
					<label for="customer_contact_phone">Phone<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="detail_phone" name="detail_phone" placeholder="Customer Phone" 
					value="<?php echo $_POST['customer_contact_phone']; ?>" required />
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="customer_form" value="yes"/>
			</form>
		</div>	
	</div>
</div>

<?php include_once('app/view/footer.inc.php'); ?>