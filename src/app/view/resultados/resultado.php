<!--CUSTOMERS VIEW-->
<?php include_once('app/view/header.inc.php'); ?>

<div class="container">
		<div class="row">
			<div class="pull-left"> 
				<h2>Result </h2>
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
			<?php echo $this->tempVars['RESULTADO']; ?>
		  </div><!--tableResponsiveList-->
				  
	</div><!--row-->
</div><!--container-->
<span  style="display:none" id="mensaje"></span>
<?php include_once('app/view/footer.inc.php'); ?>
