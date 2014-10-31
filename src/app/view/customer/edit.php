<?php include_once('app/view/header.inc.php'); ?>
<?php // if($_POST['customer_name'] !=''){$this->tempVars['CustomerList'] = $_POST;}?>
<div class="container">
	<div class="row">
		<div class="pull-left"> 
			<h2>Edit Customer</h2>
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
					<label for="customer_name">Customer Name&nbsp;(Username)<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="customer_name" name="name" placeholder="Customer Name" 
					value="<?php echo $this->tempVars['CustomerList'][0]->nombre; ?>" required />
				</div>
				<div class="form-group">   
					<label for="customer_username">Customer Email<span class="required-img"> *</span></label> 
					<input type="email" class="form-control" id="customer_email" name="email" placeholder="Customer Email" 
					value="<?php echo $this->tempVars['CustomerList'][0]->correo; ?>" required />
				</div>	
				<div class="form-group">
					<label for="customer_password">Customer Password<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="customer_password" name="password" placeholder="Password" 
					value="<?php echo $this->tempVars['CustomerList'][0]->password; ?>" required /></div>
				
				
				<div class="form-group">
					<label for="customer_address">Customer Address</label>
					<textarea class="form-control" id="customer_address" name="address" placeholder="Customer Address" cols="50" rows="10"  
					><?php echo $this->tempVars['CustomerList'][0]->direccion; ?></textarea>
				</div>
				
				<p><h3>Customer Contact Detail</h3></p>
				<div class="form-group">
					<label for="customer_contact_name">Name<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="customer_contact_name" name="detail_names" placeholder="Customer Names" 
					value="<?php echo $this->tempVars['CustomerList'][0]->nombres; ?>" required />
				</div>
				<div class="form-group">
					<label for="customer_contact_email">Email Alternate<span class="required-img"> *</span></label>
					<input type="email" class="form-control" id="customer_contact_email" name="detail_email" placeholder="Customer Email" 
					value="<?php echo $this->tempVars['CustomerList'][0]->correo_alternativo; ?>" required />
				</div>
				<div class="form-group">	
					<label for="customer_contact_phone">Phone<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="customer_contact_phone" name="detail_phone" placeholder="Customer Phone" 
					value="<?php echo $this->tempVars['CustomerList'][0]->telefono; ?>" required />
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="customer_form_edit" value="yes"/>
				<input type="hidden" name="customer_id" value="<?php echo $this->tempVars['CustomerList'][0]->customer_id; ?>"/>
			</form>
		</div>	
	</div>
</div>

<?php include_once('app/view/footer.inc.php'); ?>