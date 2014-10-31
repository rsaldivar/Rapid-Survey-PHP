<!--CUSTOMERS VIEW-->
<?php include_once('app/view/header.inc.php'); ?>


<style>
@media 
only screen and (max-width: 360px),
(min-device-width: 368px) and (max-device-width: 1024px)  {
td:nth-of-type(1):before { content: "Respondente"; }
td:nth-of-type(2):before { content: "Fecha"; }
td:nth-of-type(3):before { content: "Hora"; }
td:nth-of-type(4):before { content: ""; }
td:nth-of-type(5):before { content: ""; }
td:nth-of-type(6):before { content: ""; }
td:nth-of-type(7):before { content: ""; }
td:nth-of-type(8):before { content: ""; }
td:nth-of-type(9):before { content: ""; }
td:nth-of-type(10):before { content: ""; }
}
</style>

<div class="container">
		<div class="row">
			<div class="pull-left"> 
				<h1><span>Result List</span> 
			</div>
			<div class="pull-right lead"> 
				<input type="button" name="listsubmissions" value="List Survey" class="btn btn-success" 
			onclick="location.href='<?php echo $this->buildUrl("customer/surveys"); ?>'" />
			</div>
		</div>
	
	<h1 style="text-align:center">
		<?php echo $this->tempVars['RESULTADOS'][0]->titulo; ?>  
	</h1>
	<div class="row"><div class="clear">&nbsp;</div><hr></div>
	<div class="row">		
		<?php 
		echo $_SESSION['MSG'];
		echo $this->tempVars['MSG'];
		$_SESSION['MSG'] = '';
		$this->tempVars['MSG'] ='';
		?>

		<?php 
		if(count($this->tempVars['RESULTADOS']))
		{ 
		?>
		<a class="btn btn-success" style="width:100%"  href="<?php echo $this->buildUrl('results/general/'.$this->tempVars['ID_SURVEY']); ?>" >View Results General</a>
		<?php }?>
			<div class="table-responsive">
			<form name="customer_list" role="form" action="" method="post">
			<table class="table table-hover table-striped">
				<thead>
					<?php 
					if(count($this->tempVars['RESULTADOS']))
					{ 
						?>
						<tr>
							<th>Username  
							</th>
							<th>Date
							</th>
							<th>Time
							</th>
							<th>Acction
							</th>
							<th id="column4"><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
						</tr>
						</thead>
						<tbody>
						<?php
						
						//$this->pr($this->tempVars['RESULTADOS']);
						
						foreach($this->tempVars['RESULTADOS'] as $resultado)
						{
						?>
							<tr>
								<td><?php if($resultado->email) echo $resultado->email; else echo $resultado->correo; ?></td>
								<td><?php echo $resultado->fechaRespuesta; ?></td>
								<td><?php echo $resultado->hora; ?></td>
								<td>
									<a class="btn btn-default"  href="<?php echo $this->buildUrl('results/single/'.$this->tempVars['ID_SURVEY'].'/'.$resultado->idRespuesta); ?>" >View</a>
								</td>
								
								<td><input type="checkbox" name="ids[]" value="<?php echo $resultado->idRespuesta; ?>"></td>
							</tr>
						<?php
						} 
					}
					else
					{
						echo "<tr><td colspan='100%'><b>You have results</b></td></tr>";
					}
					?>
					
				</tbody>
				<?php
				if(count($this->tempVars['RESULTADOS']))
				{ 
				?>
				<tfoot>
					<tr>
						<td colspan="100%">
							<div class="pull-right">
								<input name="footerAction" type="hidden" id="what" />
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
			  </div><!--tableResponsiveList-->
				  
	</div><!--row-->
</div><!--container-->



<?php include_once('app/view/footer.inc.php'); ?>
