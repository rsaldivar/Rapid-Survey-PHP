<?php include_once('app/view/header.inc.php'); 
?>

<script>
$(document).ready(function() {
	// validate signup form on keyup and submit
	$("#editquestion").validate({
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

<script>
$( document ).ready(function() {
$('#tipo option[value=<?php echo $this->tempVars['question_list'][0]->tipo;?>]').attr('selected','selected');
	parametrosTipo();
	
	//LABEL
	<?php 
	
	if( $this->tempVars['question_list'][0]->dimension == "array"){
	for ( $i = 0; $i < $this->tempVars['sub_labels_count']; $i++){ 
		echo "addSubOpcion();";
		echo '$($("#divSubOpcion .form-control")['.$i.']).val("'.$this->tempVars['sub_labels'][$i].'");';
	}}
	
	if( $this->tempVars['question_list'][0]->dimension == "matriz"){
	$i = 0; 
	for ( $i = 0; $i < $this->tempVars['sub_labels_count']; $i++){ 
		echo "addSubLabel();";
		echo '$($("#divSubLabel .form-control")['.$i.']).val("'.$this->tempVars['sub_labels'][$i].'");';
	}
		
	$i = 0; 
	for ( $i = 0; $i < $this->tempVars['sub_preguntas_count']; $i++){ 
		echo "addSubPregunta();";
		echo '$($("#divSubPregunta .form-control")['.$i.']).val("'.$this->tempVars['sub_preguntas'][$i].'");';
	} 
	}
	
	?>
	
});

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
			$('#extra').append('<div id="divSubPregunta" class="subitem" ><p class="btn btn-success " style="margin:0px 0px 10px 0px " onclick="addSubPregunta()" >Add Pregunta</p></div>');
			$('#extra').append('<div id="divSubLabel"    class="subitem" ><p class="btn btn-success " style="margin:0px 0px 10px 0px " onclick="addSubLabel()"    >Add Columna</p></div>');
		}
		$('#containerValidaciones').append('<label>Texto de error personalizado</label><input class="form-control" type="text" name="mensaje_validacion" >');
			
} 
</script>

<div class="container">
	<div class="row">
		<div class="pull-left"> 
			<h2>Edit Question</h2>
		</div>
		<div class="pull-right lead"> 
			<input type="button" name="listsubmissions" value="List Survey" class="btn btn-info" 
			onclick="location.href='<?php echo $this->buildUrl("customer/surveys"); ?>'" />
			<input type="button" name="listsubmissions" value="List Questions" class="btn btn-success" 
			onclick="location.href='<?php echo $this->buildUrl("question/listing/".$this->tempVars['question_list'][0]->grupo_id); ?>'" />
		
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
			<form id="editquestion" role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validateFrm(this)" >

				
				<div class="form-group">
					<label for="titulo">Title<span class="required-img"> *</span></label>
					<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Question" 
					value="<?php echo $this->tempVars['question_list'][0]->titulo; ?>" required />
				</div>
				<div class="form-group">
					<label for="mensaje_ayuda">help<span class="required-img"> *</span></label>
					<textarea class="form-control" id="mensaje_ayuda" name="mensaje_ayuda" placeholder="Message" 
					required /><?php echo $this->tempVars['question_list'][0]->mensaje_ayuda; ?></textarea>
				</div>
				
				<div class="form-group">
					<label for="user_role">Type<span class="required-img"> *</span></label>
					<div class="form-control">
					
					<script>
					<?php
					//echo '$("#tipo").val("'.$this->tempVars['question_list'][0]->tipo_descripcion.'");';
					
					?>
					</script>
					
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
				</div>
				
		<div class="form-group" id="containerExtra" >
			<div class="col-sm-12" id="extra" ><!--add subquestions o sublabels-->
			</div>
		</div><!--form-group-->
		
		
		
		<div class="form-group">
			<div class="col-sm-12">
			
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
			
		</div><!--form-group-->
				
				<!--div class="form-group">
					<label for="prioridad">Prioridad<span class="required-img"> *</span></label>
					<input type="number" class="form-control"  name="prioridad" placeholder="Prioridad" 
					value="< ? php echo $this->tempVars['question_list'][0]->prioridad; ?>" required />
				</div-->
				
				
				<div class="form-group">
					<label for="class">Field required ?<span class="required-img"> *</span></label>
					<input type="checkbox" class="form-control" id="class" style="width:initial;" name="class" 
					value="<?php if($this->tempVars['question_list'][0]->class != null){echo 'required" '.' checked="true"';}else echo "required"; ?>"  />
				</div>
				
				
				<button type="submit" class="btn btn-primary">Submit</button>
				<input type="hidden" name="submission_form_edit" value="yes"/>
				<input type="hidden" name="project_id"  value="<?php echo $this->tempVars['question_list'][0]->grupo_id; ?>" />
				<input type="hidden" name="submission_id"  value="<?php echo $this->tempVars['question_list'][0]->id; ?>" />
			</form>
		</div>	
	</div>
</div>

<?php include_once('app/view/footer.inc.php'); ?>

<script>
function validateFrm(obj) {
	if(!validate()) {
		return false;
	}
}
</script>


