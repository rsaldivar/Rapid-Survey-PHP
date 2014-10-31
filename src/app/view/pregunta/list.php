<?php include_once('app/view/header.inc.php'); ?>


<style>
@media 
only screen and (max-width: 360px),
(min-device-width: 368px) and (max-device-width: 1024px)  {
td:nth-of-type(1):before { content: "Name"; }
td:nth-of-type(2):before { content: "Status"; }
td:nth-of-type(3):before { content: "Type"; }
td:nth-of-type(4):before { content: "Action"; }
td:nth-of-type(5):before { content: ""; }
td:nth-of-type(6):before { content: ""; }
td:nth-of-type(7):before { content: ""; }
td:nth-of-type(8):before { content: ""; }
td:nth-of-type(9):before { content: ""; }
td:nth-of-type(10):before { content: ""; }
}
</style>

<style>
.gray{
	box-shadow:0px 0px 2px green;
}
</style>
<script type="text/javascript">
$(function() {
	$(".tablaOrden tbody").tableDnD({
	 	onDragClass: "gray",
		onDrop: function(table, row) {
			var orders = $.tableDnD.serialize();
			$.post('', { orders : orders });
		}
	});
});
</script><!--habilitarDnD-->

<script>
$(document).ready(function() {
	// validate signup form on keyup and submit
	$("#addquestion").validate({
		rules: {
			titulo : {
				required: true,
				minlength: 3
			},
			mensaje_ayuda: {
				required: true,
    			rangelength: [10, 250]
			},
			tipo:{
				required:true
			}
			
		},
		messages: {
			titulo: {
				required: "Please enter you title",
				minlength: "Your title must consist of at least 3 characters"
			},
			mensaje_ayuda: {
				required: "Please provide a descripcion",
				minlength: "Your descripcion must be at least 10 characters long"
			},
			prioridad:{
				required: "Please set number",
				number: "Please set number"
			}
		}
	});
});
</script>

<div class="container">
		<div class="row">
		<div class="pull-left"> 
			<h2>Question List</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listsubmissions" value="List Survey" class="btn btn-info" 
			onclick="location.href='<?php echo $this->buildUrl("customer/surveys"); ?>'" />
			<button class="btn btn-success"  data-toggle="modal" data-target="#myModal">Add question</button>
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
			<form name="question_list" role="form" action="" method="post">
			<table class="table table-hover table-striped tablaOrden">
				<thead>
					<?php 
					if(count($this->tempVars['QuestionList']))
					{ 
						?>
						<tr >
							<th id="column1" onclick="ordenar('','column1')">Question</th>
							<th id="column2" onclick="ordenar('','column2')">Status</th>
							<th id="column3" onclick="ordenar('','column3')">Type</th>
							<th>Action</th>
							<th id="column4"><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
						</tr>
						</thead>
						<tbody>
						<?php
						foreach($this->tempVars['QuestionList'] as $question)
						{
						?>
							<tr id="order_<?php echo $question->id;?>">
								<td><?php echo $question->titulo; ?></td>
								<td><?php echo ucfirst($question->estado); ?></td>
								<td><?php echo $question->tipo_descripcion; ?></td>
								<td><a href="<?php echo $this->buildUrl('question/edit/'.$question->id); ?>" >Edit</a>					</td>
								<td><input type="checkbox" name="ids[]" value="<?php echo $question->id; ?>"></td>
							</tr>
						<?php
						} 
					}
					else
					{
					?>
						<tr><td colspan="100%">You have no question in this survey.</td></tr>
					<?php
					}
					?>
					
				</tbody>
				<?php
				if(count($this->tempVars['QuestionList']))
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
		</div>			  
	</div>
</div>



<?php include_once('app/view/footer.inc.php'); ?>

<script>

function eliminarParent(x){ //LIMPIAR LOS CAMPOS CREADOS SEGUN EL TIPO DE PREGUNTA: AL CAMBIAR LA DIMENSION 
	$(x).parent().remove();
}
//CREADOR DE OPCIONES PARA PREGUNTAS TIPO ARRAY
function addSubOpcion(){
	$("#divSubOpcion").append('<p><input type="text" class="form-control" name="label[]" placeholder="Opción nueva" ><span class="glyphicon glyphicon-remove-circle btn" onclick="eliminarParent(this)"></span></p>');
}
//CREADOR DE OPCIONES PARA PREGUNTAS TIPO MATRIZ
function addSubPregunta(){
	$("#divSubPregunta").append('<p ><input type="text" class="form-control" name="pregunta[]" placeholder="Pregunta Nueva" ><span class="glyphicon glyphicon-remove-circle btn" onclick="eliminarParent(this)"></span></p>');
}
function addSubLabel(){
	$("#divSubLabel").append('<p id="sl" ><input type="text" class="form-control" name="label[]" placeholder="Columna nueva" ><span class="glyphicon glyphicon-remove-circle btn" onclick="eliminarParent(this)"></span></p>');
}
function parametrosTipo()
{
		$( "#extra" ).empty();
		$( "#containerValidaciones" ).empty();
		
		//TIPO SIMPLE
		if( $("#tipo").val() == 's_text' )
		{	$("#tipo_descripcion").val("Linea de texto");
			$('#containerValidaciones').append('<input name="dimension" style="display:none" value="simple" >');
			$('#containerValidaciones').append('<label>Ingresa la cantidad de texto minima </label><input class="form-control" type="number" name="min" >');
			$('#containerValidaciones').append('<label>Ingresa la cantidad de texto maxima </label><input class="form-control" type="number" name="max" >');
		}
		if( $("#tipo").val() == 's_number' )
		{	
			$("#tipo_descripcion").val("Numero");
			$('#containerValidaciones').append('<input name="dimension" style="display:none" value="simple" >');
			$('#containerValidaciones').append('<label>Ingresa el valor minimo </label><input class="form-control" type="number" name="min" >');
			$('#containerValidaciones').append('<label>Ingresa el valor maximo </label><input class="form-control" type="number" name="max" >');
		}
		if( $("#tipo").val() == 's_textarea' )
		{	
			$("#tipo_descripcion").val("Area de texto");
			$('#containerValidaciones').append('<input name="dimension" style="display:none" value="simple" >');
			$('#containerValidaciones').append('<label>Ingresa la cantidad de texto minima </label><input class="form-control" type="number" name="min" >');
			$('#containerValidaciones').append('<label>Ingresa la cantidad de texto maxima </label><input class="form-control" type="number" name="max" >');
		}
		if( $("#tipo").val() == 's_date' )
		{	
			$("#tipo_descripcion").val("Fecha");
			$('#containerValidaciones').append('<input name="dimension" style="display:none" value="simple" >');
		}
		if( $("#tipo").val() == 's_time' )
		{	
			$("#tipo_descripcion").val("Fecha");
			$('#containerValidaciones').append('<input name="dimension" style="display:none" value="simple" >');
		}	
		if( $("#tipo").val() == 's_tel' )
		{	
			$("#tipo_descripcion").val("Telefono");
			$('#containerValidaciones').append('<input name="dimension" style="display:none" value="simple" >');
		}	
		//TIPO ARRAY
		if( $("#tipo").val() == 'a_select' )
		{	
			$("#tipo_descripcion").val("Lista de opciones");
		}
		if( $("#tipo").val() ==  'a_radio' )
		{	
			$("#tipo_descripcion").val("Opciones radiobutton");
		}
		if( $("#tipo").val() ==  'a_checkbox' )
		{	
			$("#tipo_descripcion").val("Opciones de Checkbox");
		}
		if( $("#tipo").val() ==  'a_select' ||  $("#tipo").val() ==  'a_radio' ||  $("#tipo").val() ==  'a_checkbox')
		{
			$('#extra').append('<input name="dimension" style="display:none" value="array" >');
			$('#extra').append('<input name="pregunta[]" placeholder="Titulo" class="form-control" type="hidden" value="'+ $("#titulo").val() +'" >');
			$('#extra').append('<div id="divSubOpcion" class="subitem" ><p class="btn btn-success " onclick="addSubOpcion()" >Add Option</p><hr/></div>');
		}
		
		//TIPO MATRIZ
		if( $("#tipo").val() == "m_radio" )
		{	
			$("#tipo_descripcion").val("Matrix de Radiobutton");
		}
		if( $("#tipo").val() == "m_checkbox" )
		{	
			$("#tipo_descripcion").val("Matrix de Checkbox");
		}
		if( $("#tipo").val() == "m_select" )
		{	
			$("#tipo_descripcion").val("Matrix de Opciones");
		}
		if( $("#tipo").val() == "m_text" )
		{	
			$("#tipo_descripcion").val("Matrix de Texto");
		}
		if( $("#tipo").val() == "m_radio" || $("#tipo").val() == "m_checkbox" || $("#tipo").val() == "m_select" || $("#tipo").val() == "m_text")
		{
			$('#extra').append('<input name="dimension" style="display:none" value="matriz" >');
			$('#extra').append('<div id="divSubPregunta" class="subitem" ><p class="btn btn-success " style="margin:0px 0px 10px 0px " onclick="addSubPregunta()" >Add Question</p></div>');
			$('#extra').append('<div id="divSubLabel"    class="subitem" ><p class="btn btn-success " style="margin:0px 0px 10px 0px " onclick="addSubLabel()"    >Add Column</p></div>');
		}
		$('#containerValidaciones').append('<label>Texto de error personalizado</label><input class="form-control" type="text" name="mensaje_validacion" >');
			
} 
</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add question</h4>
      </div>
      <div class="modal-body">
	<form id="addquestion" class="form-horizontal" action="" role="form" method="post" >
		<div class="form-group">
			<label class="col-sm-3 control-label">Title</label>
			<div class="col-sm-9">
				<input class="form-control" type="text"  name="titulo"  id="titulo" placeholder="Question" required>
			</div>
		</div><!--form-group-->
		<div class="form-group">
			<label class="col-sm-3 control-label">Help</label>
			<div class="col-sm-9">
				<textarea class="textAreaTiny form-control" name="mensaje_ayuda" id="mensaje_ayuda" placeholder="Message" required></textarea>
			</div>	
		</div><!--form-group-->
		<hr/>
		<div class="form-group">
			<label class="col-sm-3 control-label">Type</label>
			<div class="col-sm-9">
				<select name="tipo" id="tipo" onchange="parametrosTipo()"  title="Please select something!" required>
							<option value=""></option>
							<option value="s_text" title="Text line">Text line</option>
							<option value="s_number" title="Number">Number</option>
							<option value="s_textarea" title="Textarea" >Textarea</option>
							<option value="s_date" title="Date" >Date</option>
							<option value="s_tel" title="Phone">Phone</option>
							<option value="a_radio" title="Radiobuttons">Radio Buttons</option>
							<option value="a_checkbox" title="Checkboxs" >Checkboxs</option>
							<option value="a_select" title="Drop down list">Drop Down List</option>
							<option value="m_radio" id="array-radio" >Table Radio Buttons</option>
							<option value="m_checkbox" id="array-checkbox" >Table Checkboxs</option>
							<option value="m_select" id="array-select" >Table Drop Down List</option>
							<option value="m_text" id="array-select" >Table Text Line</option>
				</select>
				<input type="hidden" id="tipo_descripcion" name="tipo_descripcion"> 
			</div>
		</div><!--form-group-->
		
		<div class="form-group" id="containerExtra" >
			<label class="col-sm-3 control-label"></label>
				<div class="col-sm-9" id="extra" ><!--add subquestions o sublabels-->
				</div>
		</div><!--form-group-->
		
		
		
		<div class="form-group">
			<label class="col-sm-3 control-label">Extra</label>
			<div class="col-sm-9">
			
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Validations</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse ">
							<div class="panel-body" id="containerValidaciones">
								
							</div><!--panel-body-->
						</div><!--CollapseOne-->
					</div><!--panel-->
				</div><!--panel-group-->
			</div><!--col-sm-9-->
		</div><!--form-group-->
		
		<div class="form-group">
			<div class="col-sm-12" id="imgMuestra" >
			</div>
		</div><!--form-group-->
		
		<!--div class="form-group">
			<label class="col-sm-3 control-label">Prioridad</label>
			<div class="col-sm-9">
				<input class="form-control" type="number"  name="prioridad" id="prioridad" placeholder="Prioridad de la pregunta" >
			</div>
		</div><!--form-group-->
		<div class="form-group">	
			<label class="col-sm-3 control-label">Field required ?</label>
			<div class="col-sm-9">
				<input class="" type="checkbox" value="required" name="class" id="class" placeholder="" >
			</div>
		</div><!--form-group-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="hidden" name="frmSubmitUp" value="yes" />
        <button type="submit" class="btn btn-primary">Save</button></form>
								
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

