<!--CUSTOMERS VIEW-->
<?php include_once('app/view/header.inc.php'); ?>

<style>
@media 
only screen and (max-width: 360px),
(min-device-width: 368px) and (max-device-width: 1024px)  {
td:nth-of-type(1):before { content: "Nombre"; }
td:nth-of-type(2):before { content: "Fecha"; }
td:nth-of-type(3):before { content: "Permisos"; }
td:nth-of-type(4):before { content: ""; }
td:nth-of-type(5):before { content: ""; }
td:nth-of-type(6):before { content: ""; }
td:nth-of-type(7):before { content: ""; }
td:nth-of-type(8):before { content: ""; }
td:nth-of-type(9):before { content: ""; }
td:nth-of-type(10):before { content: ""; }
}
</style>
<script>
$(document).ready(function() {
	
jQuery.validator.addMethod("formatocorreos", function(value,  element) {
return this.optional(element) ||  /^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([,.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$/i.test(value);
}, "Ingresa emails separados por coma");
	
	$("#addsurvey").validate({
		rules: {
			titulo : {
				required: true,
				minlength: 3
			},
			descripcion: {
				required: true,
    			rangelength: [10, 250]
			},
			mensaje_bienvenida: {
				required: true,
				minlength: 5
			},
			mensaje_despedida: {
				required: true,
				minlength: 5
			},
			correos :{
				required:true,
				formatocorreos : true
			},
			fecha_final:{
				required:true,
				date:true
			}
			
		},
		messages: {
			titulo: {
				required: "Please enter you title",
				minlength: "Your title must consist of at least 3 characters"
			},
			descripcion: {
				required: "Please provide a descripcion",
				minlength: "Your descripcion must be at least 10 characters long"
			}
		}
	});
});
</script>
<script>
$(document).ready(function() {
	// validate signup form on keyup and submit
	$("#editsurvey").validate({
		rules: {
			edit_titulo : {
				required: true,
				minlength: 3
			},
			edit_descripcion: {
				required: true,
    			rangelength: [10, 250]
			},
			edit_mensaje_bienvenida: {
				required: true,
				minlength: 5
			},
			edit_mensaje_despedida: {
				required: true,
				minlength: 5
			},
			edit_correos :{
				required:true,
				formatocorreos: true
			}
		},
		messages: {
			edit_titulo: {
				required: "Please enter you title",
				minlength: "Your title must consist of at least 3 characters"
			},
			edit_descripcion: {
				required: "Please provide a descripcion",
				minlength: "Your descripcion must be at least 10 characters long"
			}
		}
	});
});
</script>

<div class="container">
		<div class="row">
			<div class="pull-left"> 
				<h2>Survey List</h2>
			</div>
			<div class="pull-right lead"> 
				<button class="btn btn-success" style="width:100%;" data-toggle="modal" data-target="#myModal">Add Survey</button>
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
			<table class="table table-hover table-striped">
				<thead>
					<?php 
					if(count($this->tempVars['ENCUESTAS']))
					{ 
						?>
						<tr>
							<!--th>Survey  #</th-->
							<th>Survey Name</th>
							<th>Date</th>
							<th>Diffuse</th>
							<th>Status</th>
							<th colspan="4">Options</th>
							<th><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
						</tr>
						</thead>
						<tbody>
						<?php
						foreach($this->tempVars['ENCUESTAS'] as $encuesta)
						{
						?>
							<tr>
								<td>
								<?php 
								if($encuesta->difusion == "activa")
								{	//CON EL ID DEL USUARIO SE VALIDA, QUE MUESTRE LAS ENCUESTAS PROPIAS	
									echo '<a href="survey/'.$_SESSION["SESS_USER_ID"].$encuesta->id.'" >'.$encuesta->titulo.'</a>';
								}
								else echo $encuesta->titulo; 
								
								?>
								
								</td>
								<td><?php echo $encuesta->fecha_final; ?></td>
								<td><?php echo $encuesta->permisos;?>
								<td><?php echo $encuesta->estado; ?></td>
								</td>
								<td><a class="btn btn-warning" style="width:100%" href="
									<?php
										if(REWRITEURL)echo $this->buildUrl('customer/surveys&edit='.$encuesta->id); 
										else echo $this->buildUrl('customer/surveys/?edit='.$encuesta->id); 
									?>">Modify</a></td>
								<td><a class="btn btn-info"    style="width:100%" href="<?php echo $this->buildUrl('results/listing/'.$encuesta->id); ?>">Result</a></td>
								<td><a class="btn btn-success" style="width:100%" href="<?php echo $this->buildUrl('question/listing/'.$encuesta->id); ?>" >Admin </a></td>
								<td><a class="btn btn-primary" style="width:100%" href="<?php echo $this->buildUrl('generador/publish/'.$encuesta->id); ?>" >Publish </a></td>
								<td><input type="checkbox" name="ids[]" value="<?php echo $encuesta->id; ?>"></td>
							</tr>
						<?php
						} 
					}
					else
					{
						echo "<tr><td colspan='100%'><b>You have no surveys</b></td></tr>";
					}
					?>
					
				</tbody>
				<?php
				if(count($this->tempVars['ENCUESTAS']))
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
			  </div><!--tableResponsiveList-->
				  
	</div><!--row-->
</div><!--container-->


<!-- Modal Agregar Encuesta-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add Survey</h4>
      </div>
      <div class="modal-body">
	<form id="addsurvey" class="form-horizontal" action="" role="form" method="POST" >
		<div class="form-group">
			<label class="col-sm-3 control-label">Title</label>
			<div class="col-sm-9">
				<input class="form-control" type="text" value="" name="titulo" id="add_title" placeholder="Title" required>
			</div>
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Description</label>
			<div class="col-sm-9">
				<textarea class="textAreaTiny form-control" name="descripcion" id="add_description" placeholder="Description" required></textarea>
			</div>	
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Welcome message</label>
			<div class="col-sm-9">
				<textarea class="textAreaTiny form-control" name="mensaje_bienvenida" id="add_welcome_message" placeholder="Welcome message" required></textarea>
			</div>
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Farewell message</label>
			<div class="col-sm-9">
			      <textarea class="textAreaTiny form-control" name="mensaje_despedida" id="add_farewell_message" placeholder="Farewell message" required></textarea>
			</div>
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Public</label>
			<div class="col-sm-9">
			      <input type="checkbox" class=" form-control" style="width: initial;" name="permisos" id="publica" checked>
			</div>
		</div><!--form-group-->
		
		<script>
		$("#publica").on("click" , function(){ if($("#publica").is(':checked'))$("#correos").hide(); else   $("#correos").show(); });
		</script>
		<div class="form-group" id="correos" style="display:none">
			<label class="col-sm-3 control-label">Emails</label>
			<div class="col-sm-9">
				<input class="form-control" type="text"  name="correos" id="add_mails" placeholder="email1@domain.com,email2@domain.com" />
			</div>
		</div><!--form-group-->
		
		<div class="form-group">
			<label class="col-sm-3 control-label">End Date</label>
			<div class="col-sm-9">
				<input class="form-control datepicker" type="text" name="fecha_final" id="add_end_date" placeholder="End Date" required/>
			</div>
		</div><!--form-group-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        <input type="hidden" name="frmSubmitInsert" value="yes" />
        <button type="submit" class="btn btn-primary">Save</button></form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php 	
	if($this->tempVars['MODAL-EDIT'] == "TRUE")
	{ ?>
		<script>
		$( document ).ready(function() { $('#myModalEdit').modal('show');
		$("#edit_id").val("<?php echo $this->tempVars['ENCUESTA_EDIT'][0]->id?>");
		$("#edit_titulo").val("<?php echo $this->tempVars['ENCUESTA_EDIT'][0]->titulo?>");
		$("#edit_descripcion").val("<?php echo $this->tempVars['ENCUESTA_EDIT'][0]->descripcion?>");
		$("#edit_mensaje_bienvenida").val("<?php echo $this->tempVars['ENCUESTA_EDIT'][0]->mensaje_bienvenida?>");
		$("#edit_mensaje_despedida").val("<?php echo $this->tempVars['ENCUESTA_EDIT'][0]->mensaje_despedida?>");
		
		<?php
		if( $this->tempVars['ENCUESTA_EDIT'][0]->permisos == "publica" )
		{
			echo  '$("#edit_publica").attr("checked", true);'; 
		}
		else{
			echo  '$("#edit_publica").attr("checked", false);';
			echo  '$("#edit_correos").show();';
			echo  '$("#edit_line_correos").val("'.$this->tempVars['CORREOS-TOKENS'].'");';
		}
		?>
			$("#edit_publica").on("click" , function(){ 
				if($("#edit_publica").is(':checked'))
						$("#edit_correos").hide(); 
				else	$("#edit_correos").show(); 
			});
		});
		</script>
<?php	}
?>

<!-- Modal Editar Encuesta-->
<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Edit Survey</h4>
      </div>
      <div class="modal-body">
	<form id="editsurvey" class="form-horizontal" action="" role="form" method="POST" >
		<div class="form-group" style="display:none">
			<label class="col-sm-3 control-label">Id Survey</label>
			<div class="col-sm-9">
				<input class="form-control" type="text" value="" name="edit_id" id="edit_id" placeholder="" >
			</div>
		</div><!--form-group--><div class="form-group">
			<label class="col-sm-3 control-label">Title</label>
			<div class="col-sm-9">
				<input class="form-control" type="text" value="" name="edit_titulo" id="edit_titulo" placeholder="Title" required>
			</div>
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Description</label>
			<div class="col-sm-9">
				<textarea class="textAreaTiny form-control" name="edit_descripcion" id="edit_descripcion" placeholder="descripcion" required></textarea>
			</div>	
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Welcome message</label>
			<div class="col-sm-9">
				<textarea class="textAreaTiny form-control" name="edit_mensaje_bienvenida" id="edit_mensaje_bienvenida" required></textarea>
			</div>
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Farewell message</label>
			<div class="col-sm-9">
			      <textarea class="textAreaTiny form-control" name="edit_mensaje_despedida" id="edit_mensaje_despedida" required></textarea>
			</div>
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Public</label>
			<div class="col-sm-9">
			      <input type="checkbox" class=" form-control" style="width: initial;" name="edit_publica" id="edit_publica" checked>
			</div>
		</div><!--form-group-->
		
		<script>
		$("#edit_publica").on("click" , function(){
		if($("#edit_publica").is(':checked'))$("#edit_correos").hide(); else   $("#edit_correos").show();  
		    });
		</script>
		<div class="form-group" id="edit_correos" style="display:none">
			<label class="col-sm-3 control-label">Emails</label>
			<div class="col-sm-9">
				<input class="form-control" type="text"  name="edit_correos" id="edit_line_correos" placeholder="email1@domain.com,email2@domain.com"/>
			</div>
		</div><!--form-group-->
		
		<div class="form-group">
			<label class="col-sm-3 control-label">End Date</label>
			<div class="col-sm-9">
				<input class="form-control datepicker" type="text" name="edit_fecha_final" id="edit_fecha_final" required />
			</div>
		</div><!--form-group-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        <input type="hidden" name="frmSubmitEdit" value="yes" />
        <button type="submit" class="btn btn-primary">Update</button></form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php include_once('app/view/footer.inc.php'); ?>
