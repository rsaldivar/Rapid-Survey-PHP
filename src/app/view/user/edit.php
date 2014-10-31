<?php include_once('app/view/header.inc.php'); ?>
<div class="container">
		<div class="row">
		<div class="pull-left"> 
			<h2>Edit User</h2>
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
					value="<?php echo $this->tempVars['userList'][0]->nombre; ?>" required />
				</div>
				<div class="form-group">
					<label for="user_mail">User Mail<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="user_mail" name="user_mail" placeholder="User Mail" 
					value="<?php echo $this->tempVars['userList'][0]->correo; ?>" required />
				</div>
				
				<div class="form-group">
					<label for="user_password">User Password<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="user_password" value="<?php echo $this->tempVars['userList'][0]->password; ?>" name="user_password" placeholder="Password" 
					 required />
				</div>
				
				
				<div class="form-group">
					<label for="user_role">User Roles<span class="required-img"> *</span></label>
					<div class="form-control">
						<select id="user_role" name="user_role" onchange="mostrarCustomers()">
							<option value="cliente" <?php if($this->tempVars['userList'][0]->rol=='cliente'){ echo 'selected';} ?>  >cliente</option>
							<!--option value="respondente" < ?php if($this->tempVars['userList'][0]->rol=='respondente'){ echo 'selected';} ?> >respondente</option-->
						</select>
					</div>
				</div>
								
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="user_form_edit" value="yes"/>
			</form>
		</div>	
	</div>
</div>

<?php include_once('app/view/footer.inc.php'); ?>