<?php
class CommonFunctions
{
	/* FUNCTION FOR CHECK THE VALID FILE EXTENTION */
	static function findexts($filename) 
	{
        $filename = strtolower($filename);
        $exts = split("[/\\.]", $filename);
        $n = count($exts) - 1;
        $exts = $exts[$n];
        return $exts;
    }
	
	static function DisplayMsg($msg,$type='alert-info')
	{
		return '<div class="alert alert-dismissable '.$type.'"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$msg.'</div>';
	}
	
	static function isUserLogedIn()
	{
		if($_SESSION["SESS_USER_USERNAME"] !='' && $_SESSION["SESS_USER_ROLE"] != '')
		{
	 		return true;
		}
		else
		{
			$path = SITE_URL.'home';
			header("Location: ".$path);
			exit;
		}
	}
	
	
	function round_up ($value, $places=0) 
	{
  		if ($places < 0) { $places = 0; }
  		$mult = pow(10, $places);
  		return ceil($value * $mult) / $mult;
 	}
	
	
	// function for access the permission
	function isUsersAccessable($path)
	{
		$AllArray =array('user/index',
						'user/listing',
						'user/add',
						'user/edit',
						'project/index',
						'project/listing',
						'project/add',
						'project/edit',
						'customer/index',
						'customer/listing',
						'customer/add',
						'customer/edit',
						'question/index',
						'question/listing',
						'question/add',
						'question/edit',
						'customer/index',
						'customer/listing',
						'customer/edit',
						'customer/surveys',
						'customer/survey',
						'question/index',
						'question/listing',
						'question/edit',
						'results/index',
						'results/listing',
						'results/general',
						'results/single',
						'generador/index',
						'generador/publish'
						);
		$customerArray =array(
						'customer/index',
						'customer/listing',
						'customer/edit',
						'customer/surveys',
						'customer/survey',
						'question/index',
						'question/listing',
						'question/edit',
						'results/index',
						'results/listing',
						'results/general',
						'results/single',
						'generador/index',
						'generador/publish'
						);
		
		if($_SESSION["SESS_USER_ROLE"] == "administrador")
		{
// 			if(!in_array($path,$AllArray))
// 			{
// 				$this->wtRedirect($_SESSION["ROLE_PATH"]); 
// 			}
		}
		
		elseif($_SESSION["SESS_USER_ROLE"] == "cliente" )
		{
			//SI no esta en el arreglo, se redirecciona a su path original Controller home
			if(!in_array($path,$customerArray))
			{
				$this->wtRedirect($_SESSION["ROLE_PATH"]); 
			}
		}
	}
	function getStars($rating, $id = "", $readOnly = 1) 
	{
		$path = $this->getSiteUrl();
        if (!$id) 
		{
            $id = "star_1";
        }
        $starsObj = new rating($rating, $id, $path, $readOnly);
    }
}

$com_fun = new CommonFunctions;
