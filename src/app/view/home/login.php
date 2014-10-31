<?php include_once('app/view/header.inc.php'); ?>
<div class="container">
	<div class="row">
		<div class="pull-left lead col-md-12"> 
			<h3>Login</h3>
		</div>
		<div class="col-md-8">
			<?php 
			echo $_SESSION['MSG'];
			echo $this->tempVars['MSG'];
			$_SESSION['MSG'] = '';
			$this->tempVars['MSG'] ='';
			?>
			<form role="form" action="" method="post">
				<div class="form-group">   
					<div class="input-group input-group-lg">
  						<span class="input-group-addon login-title-size">Username</span>
  						<input type="text" class="form-control input-lg" name="username" id="username" placeholder="example@example.com" required />
					</div>
				</div>	
				<div class="form-group">
					<div class="input-group input-group-lg">
  						<span class="input-group-addon login-title-size">Password</span>
  						<input type="password" class="form-control input-lg" name="password" id="password" placeholder="password" required />
					</div>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>	
	</div>		
</div>	
<?php include_once('app/view/footer.inc.php'); ?>