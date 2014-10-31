// JavaScript Document
function checkall(objForm){
 len = objForm.elements.length;
	for( i=0 ; i<len ; i++){
		if (objForm.elements[i].type=='checkbox') 
		objForm.elements[i].checked=objForm.check_all.checked;
	 }
}
function button_prompt(frmobj,comb) {

	 len = frmobj.elements.length;
	 flag = true;
	for( i=0 ; i<len ; i++){
		if (frmobj.elements[i].type=='checkbox' && frmobj.elements[i].checked) {
				 flag = false;
			}
	 }
	 if(flag) {
		 alert("Please select a checkbox to continue");
		 return false;
		 }
	   if(comb=="Delete"){
			if(confirm ("Are you sure you want to delete the record(s)."))
			{
				frmobj.footerAction.value="Delete";
				frmobj.action = "";
				frmobj.submit();
			}
			else{ 
			return false;
			}
	}
	else {
		frmobj.footerAction.value=comb;
		//frmobj.action="";
		frmobj.submit();
	}
}



var clickCntr = 0;
var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

var regSpace = /\s/;
function validate() {
	returnCntr=true;
	$(".error").remove();
	$('.required').each(function(index) {
		if($(this).val()=="") {
			$(this).after("<div class='error "+$(this).attr("id")+"error'>"+$(this).attr("errormsg")+"</div>");
			returnCntr=false;
		}
	});

	$('.checked').each(function(index) {
		$chkFlag = false;
		if(!$(this).is(':checked')) {
			$chkFlag = true;
			if($(this).attr("customError")!="undefined") {
				$errorCls = $(this).attr("customError");
      //alert('in if');
   $(this).after("<div class='error "+$(this).attr("id")+"error'>"+$(this).attr("errormsg")+"</div>");				
				} else {
					//alert('in else');
			$(this).after("<div class='error "+$(this).attr("id")+"error'>"+$(this).attr("errormsg")+"</div>");
				}
			returnCntr=false;
		}
		if($errorCls) {
		returnCntr=false;
		$("."+$errorCls).html($(this).attr("errormsg"));
		}
	});
	$('.compare').each(function(index) {
		if($(this).val()) {
			fieldArr = $(this).attr("comparefields").split("|");
			if($("#"+fieldArr[0]).val()!=$("#"+fieldArr[2]).val()) {
				$(this).after("<div class='error "+$(this).attr("id")+"error'>"+$(this).attr("comparemsg")+"</div>");
				returnCntr=false;
			}
		}
		
	});
	
	$('.emailvalidate').each(function(index) {

		if(!regex.test($(this).val())) {
			$(this).after("<div class='error "+$(this).attr("id")+"error'>Please enter a valid email address</div>");
			returnCntr=false;
		}
	});
	
	$('.nospace').each(function(index) {

		if(regSpace.test($(this).val())) {
			$(this).after("<div class='error "+$(this).attr("id")+"error'>Please enter a valid value(without space)</div>");
			returnCntr=false;
		}
	});
	$('.integer').each(function(index) {
		  // returnCntr=true;
           number=$(this).val();
		   var checklength=$(this).attr('maxlength');
		if(isNaN(number) ||  number.length!=checklength) {
			$(this).after("<div class='error "+$(this).attr("id")+"error'>Please enter a valid "+ checklength +" digit value</div>");
			returnCntr=false;
		}
	});

	$('.filevalidate').each(function(index) {
		if($(this).val()!="") {
			extentionArr = $(this).attr("extentions").split(",");
			extentionVarArr = $(this).val().split(".");
			extentionVar = extentionVarArr[extentionVarArr.length-1];
			extentionFlag = false;
			for(i=0;i<extentionArr.length;i++) {
				if(extentionArr[i].toUpperCase()==extentionVar.toUpperCase()) {
					extentionFlag = true;
				}
			}

			if(!extentionFlag) {
				$(this).after("<div class='error"+$(this).attr("id")+"error error'>"+$(this).attr("extentions")+" are the only valid extentions</div>");
			returnCntr=false;
			}
		}
	});
clickCntr++;
return 	returnCntr;
}

