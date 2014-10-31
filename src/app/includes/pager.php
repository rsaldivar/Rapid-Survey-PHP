<?php 
 class easypager {
   protected $tpage;
   protected $trecords;
   protected $currentPage;
   protected $recordPerPage;
   
   function __construct($perpage){
     setRecordPerPage($perpage);
   }
   
  function totalPages(){
   return $this->$tpage=ceil($this->$trecords/$this->currentPage);
   }
 
   function totalRecords(){
      $pquery= "SELECT * FROM listing where classification='".$tabtype."'";
	  $pres=mysql_query($pquery);
	  $prows=mysql_num_rows($pres);
      return $this->$trecords=$prows;
   }
   
   function getCurrentpage(){
    return $this->$currentPage=isset($_GET['cpage'])?intval($_GET['cpage']):0;
   }
   
   function setRecordPerPage($val){
   return $this->currentPage=$val;
   }
    function getPagingHtml(){
	   /*get paging html*/
	    if($cpage<=0){$paginghtml='<ul><li class="disabled"><span id="#">&laquo;</span></li>';}
		else{$paginghtml='<ul><li><span id="'.($cpage-1).'">&laquo;</span></li>';}
	    for($p=1;$p<=$tpage;$p++){
		  $actv_cls=($cpage+1)==$p?'active':'';
		  $paginghtml.='<li class="'.$actv_cls.'"><span id="'.($p-1).'">'.$p.'</span></li>';
		}
		if(($cpage+1)>=$tpage){$paginghtml.='<li class="disabled"><span id="#">&raquo;</span></li></ul>';}
		else{$paginghtml.='<li><span id="'.($cpage+1).'">&raquo;</span></li></ul>';}
	  /*End*/
	  return $paginghtml;
	}
   
   
   
   }
?>