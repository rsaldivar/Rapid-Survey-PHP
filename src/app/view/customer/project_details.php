
<?php include_once('app/view/header.inc.php'); ?>
<script type="text/javascript">
        $(document).ready(function () {
            $('.fancybox').fancybox({
                    padding : 5,
                    width : 300,
                    height : 250,
                    openEffect  : 'elastic',
                    closeEffect : 'elastic',
                    autoSize    : true,
            });
        });
</script>

<!--ARCHIVO QUE MUESTRA DE MANERA PREVIA UNA SUBMISSION-->

<script>//Parametros a enviar por POST
function cambiarTab(){
$('#listaSubmissions').addClass("in active");
$('#tabList').addClass("active");
$('#detalleProyecto').removeClass("in active");
$('#tabDetail').removeClass("active");
}


function updateComments(valorNuevo, idSubmission , valorViejo){
var parametros = {"EDITARSUBMISSION" : "yes",id: idSubmission, focus: "0" , comments : valorViejo+" ; "+valorNuevo, creativity : "0", design : "0",fonts : "0",colors: "0"};
$.post("", parametros , function(server_response){   $("#ant").html(server_response); cambiarTab(); });
}
function updateFocus(valorNuevo, idSubmission){
var parametros = {"EDITARSUBMISSION" : "yes",id: idSubmission, focus: valorNuevo , comments : "", creativity : "0", design : "0",fonts : "0",colors: "0"};
//$.ajax({ data:  parametros,url:   '',type:  'POST',success:  function (response) { $("#mensaje").html(response);}});
$.post("", parametros , function(server_response){   $("#ant").html(server_response); cambiarTab(); }); 
}
function updateCreativity(valorNuevo,idSubmission)
{var parametros = {"EDITARSUBMISSION" : "yes",id: idSubmission, focus: "0" , comments : "", creativity : valorNuevo, design : "0",fonts : "0",colors: "0"};
$.post("", parametros , function(server_response){   $("#ant").html(server_response); cambiarTab(); });
}
function updateDesign(valorNuevo,idSubmission)
{var parametros = {"EDITARSUBMISSION" : "yes",id: idSubmission, focus: "0" , comments : "", creativity : "0", design : valorNuevo, fonts : "0",colors: "0"};
$.post("", parametros , function(server_response){   $("#ant").html(server_response); cambiarTab(); });
} 
function updateFonts(valorNuevo,idSubmission)
{var parametros = {"EDITARSUBMISSION" : "yes",id: idSubmission, focus: "0" , comments : "", creativity : "0", design : "0",fonts : valorNuevo ,colors: "0"};
$.post("", parametros , function(server_response){   $("#ant").html(server_response); cambiarTab(); });
}
function updateColor(valorNuevo,idSubmission)
{var parametros = {"EDITARSUBMISSION" : "yes",id: idSubmission, focus: "0" , comments : "", creativity : "0", design : "0",fonts : "0",colors: valorNuevo};
$.post("", parametros , function(server_response){   $("#ant").html(server_response); cambiarTab(); });
}
</script>



<script type="text/javascript">
var antCampo="<?php echo $_POST["campo"];?>";
$("#<?php echo $_POST["columnSelect"];?>").addClass("order_active");
var ordenSelect="<?php echo $_POST["order"];?>";
if(ordenSelect == "ASC"){$("#<?php echo $_POST["columnSelect"];?>").append('<span style="position: relative;top: 2px;left: 10px;" class="glyphicon glyphicon-chevron-down"></span>');}
else {$("#<?php echo $_POST["columnSelect"];?>").append('<span style="position: relative;top: 2px; left: 10px;" class="glyphicon glyphicon-chevron-up"></span>');}

function ordenar(campo,columnSelect)
{ var parametros;
  if(campo == antCampo){orden="DESC";}else{ orden = "ASC";}
  if(ordenSelect == "DESC" ){orden="ASC";}
  parametros = {campo: campo , order:orden, campoant:campo, columnSelect:columnSelect };
  $.post("", parametros , function(server_response){   $("#ant").html(server_response);  cambiarTab(); });
}

</script>
<div id="ant">
<div class="container">
        <div class="row">
                <div class="pull-left"> 
                        <h2>Project Details</h2>
                </div>
        </div>
        <div class="row"><div class="clear">&nbsp;</div><hr></div>      
        

        <ul id="myTab" class="nav nav-tabs">
          <li class="active" id="tabDetail"><a href="#detalleProyecto" data-toggle="tab">Detail</a></li>
          <li id="tabList" ><a href="#listaSubmissions" data-toggle="tab">Submissions List</a></li>
        </ul><!--myTab-->
        <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="detalleProyecto"><!--projectlist-->
        
        <form role="form" action="" method="post" enctype="multipart/form-data"  onsubmit="return validateFrm(this)">   
        <div class="row">
                <div class="col-md-10">
                        <div class="form-group">
                              
                                <label for="customer_name">Name</label>
                                <p><?php echo $this->tempVars['Project_Details'][0]->project_name; ?></p>
                        </div>
                        <div class="form-group">
                                <label for="customer_name">Description</label>
                                <p><?php echo $this->tempVars['Project_Details'][0]->project_description; ?></p>
                        </div>
                        
                        <div class="form-group">   
                                <label>Image</label>
                                <p>
                                <?php 
                                    if(is_file(ROOT.'/media/projectfile/'.$this->tempVars['Project_Details'][0]->project_attachment))
                                    { 
                                ?>
                                        <a class="fancybox" href=" <?php echo SITE_URL."/media/projectfile/".$this->tempVars['Project_Details'][0]->project_attachment;?>" data-fancybox-group="gallery"> 
                                            <img src="<?php echo SITE_URL."/media/projectfile/".$this->tempVars['Project_Details'][0]->project_attachment;?>" width="100px" /> </a>
                                   <?php } ?>
                                </p>
                        </div>
                                
                        <div class="form-group">
                                <label for="customer_name">Customer</label>
                                <p><?php echo $this->tempVars['Customer_Name'][0]->customer_name; ?></p>
                        </div>
                                
                        <div class="form-group">   
                                <label>Deadline</label>
                                <p><?php echo date('d M Y', strtotime($this->tempVars['Project_Details'][0]->project_deadline)); ?></p>
                        </div>  
                        <div class="form-group">  
                                <hr/>
                                <label for="customer_name">Customer Comments</label>
                                <textarea class="form-control" id="project_comments" name="commentsCustomer" placeholder="Project  Description" cols="1000" rows="2"  required><?php echo $this->tempVars['Project_Details'][0]->project_comments;  ?></textarea>
                        </div>  
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <input type="hidden" name="comment_form_project" value="yes"/>
                        <input type="hidden" name="comment_form_project_id" value="<?php echo $this->tempVars['Project_Details'][0]->project_id;?> "/>
                        
                </div>  
        </div>
        </form>
        </div><!--tab-pane DetalleProjecto-->
         
        <div class="tab-pane fade" id="listaSubmissions">
    <div class="row">       
        <?php 
        echo $_SESSION['MSG'];
        echo $this->tempVars['MSG'];
        $_SESSION['MSG'] = '';
        $this->tempVars['MSG'] ='';
        ?>
        <div class="table-responsive">
            <form name="submission_list" role="form" action="" method="post">
            <table class="table table-bordered table-striped">
                <thead>
                    <?php 
                    if(count($this->tempVars['SubmissionList']))
                    { 
                        ?>
                        <tr  style="cursor: pointer;">
                            <th id="column1" onclick="ordenar('submission_id','column1')">Submission</th>
                            <th id="column2" onclick="ordenar('submission_comments','column2')">Add Comments</th>
                            <th id="column3" onclick="ordenar('submission_focus','column3')">Focus</th>
                            <th id="column4" onclick="ordenar('submission_creativity','column4')">Creativity</th>
                            <th id="column5" onclick="ordenar('submission_design','column5')">Design</th>
                            <th id="column6" onclick="ordenar('submission_fonts','column6')">Fonts </th>
                            <th id="column7" onclick="ordenar('submission_colors','column7')">Colors </th>
                            <th id="column8" onclick="ordenar('submission_prom','column8')">Prom</th>
                            <th id="column9" onclick="ordenar('submission_date','column9')" style="min-width: 100px;">Date</th>
                            <th id="column10" >Status </th>
                            <th id="column11"  >Action</th>
                            <th><input name="check_all" type="checkbox" id="check_all" value="check_all" onclick="checkall(this.form)" /></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($this->tempVars['SubmissionList'] as $submissionlist)
                        {
                        ?>
                            <tr>
                                <td><?php 
                                  
                                    if(is_file(ROOT.'/media/submissionfile/'.$submissionlist->submission_image)){
                                          ?>
    <a class="fancybox" href="<?php echo SITE_URL."/media/submissionfile/".$submissionlist->submission_image;?>" data-fancybox-group="gallery"> <img src='<?php echo SITE_URL."/media/submissionfile/".$submissionlist->submission_image;?>' width="100px" /> </a>                                    

                                               <?php } ?>
                                </td>
                                <!--td><?php echo $submissionlist->submission_comments; ?></td-->
                                <td><textarea style="width:100%; height:100%; resize: none;" name="comments"  cols="80" rows="3" onchange="updateComments(this.value,<?php echo $SubmissionList->submission_id; ?>,'<?php  echo $submissionlist->submission_comments; ?>')"/> </textarea></td>
                                                                
                                <!--td><?php if($submissionlist->submission_focus !='0'){echo $submissionlist->submission_focus;} ?></td-->
                                <td>
                                    <select name="focus" onchange="updateFocus(this.value,<?php echo $submissionlist->submission_id;?>)">
                                    <option value="1" id="focus1" <?php if($submissionlist->submission_focus == 1){echo 'selected="selected"';}?> >1/5</option>
                                    <option value="2" id="focus2" <?php if($submissionlist->submission_focus == 2){echo 'selected="selected"';}?> >2/5</option>
                                    <option value="3" id="focus3" <?php if($submissionlist->submission_focus == 3){echo 'selected="selected"';}?> >3/5</option>
                                    <option value="4" id="focus4" <?php if($submissionlist->submission_focus == 4){echo 'selected="selected"';}?> >4/5</option>
                                    <option value="5" id="focus5" <?php if($submissionlist->submission_focus == 5){echo 'selected="selected"';}?> >5/5</option>
                                    </select>       
                                </td>

                                <!--td><?php if($submissionlist->submission_creativity !='0'){echo $submissionlist->submission_creativity;} ?></td-->
                                <td>
                                    <select name="creativity" onchange="updateCreativity(this.value,<?php echo $submissionlist->submission_id;?>)">
                                    <option value="1" id="creativity1" <?php if($submissionlist->submission_creativity == 1){echo 'selected="selected"';}?> >1/5</option>
                                    <option value="2" id="creativity2" <?php if($submissionlist->submission_creativity == 2){echo 'selected="selected"';}?> >2/5</option>
                                    <option value="3" id="creativity3" <?php if($submissionlist->submission_creativity == 3){echo 'selected="selected"';}?> >3/5</option>
                                    <option value="4" id="creativity4" <?php if($submissionlist->submission_creativity == 4){echo 'selected="selected"';}?> >4/5</option>
                                      <option value="5" id="creativity5" <?php if($submissionlist->submission_creativity == 5){echo 'selected="selected"';}?> >5/5</option>
                                    </select> 
                                </td>

                                <!--td><?php if($submissionlist->submission_design !='0'){echo $submissionlist->submission_design;} ?></td-->
                                <td>
                                    <select name="Design" onchange="updateDesign(this.value,<?php echo $submissionlist->submission_id;?>)">
                                    <option value="1" id="design1" <?php if($submissionlist->submission_design == 1){echo 'selected="selected"';}?> >1/5</option>
                                    <option value="2" id="design2" <?php if($submissionlist->submission_design == 2){echo 'selected="selected"';}?> >2/5</option>
                                    <option value="3" id="design3" <?php if($submissionlist->submission_design == 3){echo 'selected="selected"';}?> >3/5</option>
                                    <option value="4" id="design4" <?php if($submissionlist->submission_design == 4){echo 'selected="selected"';}?> >4/5</option>
                                    <option value="5" id="design5" <?php if($submissionlist->submission_design == 5){echo 'selected="selected"';}?> >5/5</option>
                                    </select> 
                                </td>

                                <!--td><?php if($submissionlist->submission_fonts !='0'){echo $submissionlist->submission_fonts;} ?></td-->
                                <td>
                                    <select name="Fonts" onchange="updateFonts(this.value,<?php echo $submissionlist->submission_id;?>)">
                                    <option value="1" id="fonts1" <?php if($submissionlist->submission_fonts == 1){echo 'selected="selected"';}?> >1/5</option>
                                    <option value="2" id="fonts2" <?php if($submissionlist->submission_fonts == 2){echo 'selected="selected"';}?> >2/5</option>
                                    <option value="3" id="fonts3" <?php if($submissionlist->submission_fonts == 3){echo 'selected="selected"';}?> >3/5</option>
                                    <option value="4" id="fonts4" <?php if($submissionlist->submission_fonts == 4){echo 'selected="selected"';}?> >4/5</option>
                                    <option value="5" id="fonts5" <?php if($submissionlist->submission_fonts == 5){echo 'selected="selected"';}?> >5/5</option>
                                    </select> 
                                </td>
                                <!--td><?php if($submissionlist->submission_colors !='0'){echo $submissionlist->submission_colors;} ?></td-->
                                <td>
                                    <select name="Color" onchange="updateColor(this.value,<?php echo $submissionlist->submission_id;?>)">
                                    <option value="1" id="colors1" <?php if($submissionlist->submission_colors == 1){echo 'selected="selected"';}?> >1/5</option>
                                    <option value="2" id="colors2" <?php if($submissionlist->submission_colors == 2){echo 'selected="selected"';}?> >2/5</option>
                                    <option value="3" id="colors3" <?php if($submissionlist->submission_colors == 3){echo 'selected="selected"';}?> >3/5</option>
                                    <option value="4" id="colors4" <?php if($submissionlist->submission_colors == 4){echo 'selected="selected"';}?> >4/5</option>
                                    <option value="5" id="colors5" <?php if($submissionlist->submission_colors == 5){echo 'selected="selected"';}?> >5/5</option>
                                    </select> 
                                </td>

                                <td><?php if($submissionlist->submission_prom !='0'){echo $submissionlist->submission_prom;} ?></td>
                                <td><?php if($submissionlist->submission_date !=""){echo $submissionlist->submission_date;} ?></td>
                                <td>
                                    <?php 
                                        echo ucfirst($submissionlist->submission_status);
                                        if($submissionlist->customer_status == 'winner'){echo '<br/>(Winner)';}
                                        if($submissionlist->customer_status == 'discard'){echo '<br/>(Discarded)';} 
                                    ?>
                                
                                </td> 
                                <td>
                                    <a href="<?php echo $this->buildUrl('customer/submission-details/'.$submissionlist->submission_id); ?>" >View/Edit</a>
                                </td>
                                <td><input type="checkbox" name="ids[]" value="<?php echo $submissionlist->submission_id; ?>"></td>
                            </tr>
                        <?php
                        } 
                    }
                    else
                    {
                    ?>
                        <tr><td colspan="100%">You have no submissions in this project.</td></tr>
                    <?php
                    }
                    ?>
                    
                </tbody>
                <?php 
                if(count($this->tempVars['SubmissionList']))
                { 
                ?>
                <tfoot>
                    <tr>
                        <td colspan="100%">
                            <div class="pull-right">
                                <input name="footerAction" type="hidden" id="what" />
                                
                                <?php if($this->tempVars['ProjectNAME'][0]->project_status == 'decision')
                                {
                                ?>
                                <input type="button" name="btnMark"     value="Winner" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
                                <?php
                                }
                                ?>
                                <input type="button" name="btnActivate" value="Discard" class="btn btn-default" onclick="button_prompt(this.form,this.value)" />
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
    </div><!--row-->
                              
        </div><!--tab-pane listaSubmission-->
    </div><!--tabs-->   

</div><!--container-->
<?php include_once('app/view/footer.inc.php'); ?>       
</div>