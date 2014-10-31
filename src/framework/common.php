<?php
class common {
	var $templateVars = array();
	function attachFile($filePath) {
		
		
	}
	function view($filename) {
		$tempVars = $this->templateVars;
		$filePath = "app/view/".$filename.".php";
		require_once($filePath);
	}
	function getSiteUrl() {
		return SITE_URL;
		
	}
	function date_format_mysql($date,$seperator,$format) {
		$dateArr = explode($seperator,$date);
		$formatArr = explode($seperator,$format);
		for($i=0;$i<count($formatArr);$i++) {
		$val = $formatArr[$i];
			if($val=='mm') {
				$MM = $dateArr[$i];
			}
			if($val=='dd') {
				$DD = $dateArr[$i];
			}
			if($val=='YYYY') {
				$YYYY = $dateArr[$i];
			}
			
		}
		return "$YYYY-$MM-$DD";
	}
	function buildUrl($url,$param=array()) {
		if(REWRITEURL==false){
			/*
		$urlArr = explode('/',$url);
		$indHolder = "";
		for($i=0;$i<count($urlArr);$i++) {
			if($i==0) {
				$urlstr[] = "controller=".$urlArr[$i];				
			} else 	if($i==1) {
				$urlstr[] = "method=".$urlArr[$i];				
			} else if($i%2!=0) {
				$indHolder = $urlArr[$i];
			} else {
				$urlstr[] = $indHolder."=".$urlArr[$i];
			}
		}
		*/
		//$url = "index.php?".implode("&",$urlstr);
			$url = $this->getSiteUrl().'index.php/'.$url;
		} else {
			$url = $this->getSiteUrl().$url;
		}
		return $url;		
	}
	function pData() {
		global $_POST,$_GET;
		
		print_r($_POST);
		return $_POST;
	}
	function pr($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	function model($modelClass) {
		return $model = new $modelClass();
	}
	function parsedata($value){
		$ret=mysql_real_escape_string(stripslashes(trim($value)));
		return $ret;
	}
	function stripdata($value){
		$ret=stripslashes(trim($value));
		return $ret;
	}
	function wtRedirect($url) {
		$tempVars = $this->templateVars;
		$urlVar = $this->buildUrl($url);
		header("location:".$urlVar);
		exit();
	}
	function wtReferer() {
		header("location:".$_SERVER['HTTP_REFERER']);
		exit();
	}
	function sort_column($column,$path) {
		$getString = $this->buildUrl($path);
		return "<a href='".$getString."/sort/desc/column/".$column."'><img src='".$this->getSiteUrl()."app/view/admin/images/arrow_up.gif' border='0'></a> <a href='".$getString."/sort/asc/column/".$column."''><img src='".$this->getSiteUrl()."app/view/admin/images/arrow_down.gif' border='0'></a>";
	}
	function sendPhpMail($email_to,$emailto_name,$email_subject,$email_body,$email_from,$reply_to,$html=true){
		require_once (FRAMEWORK .DS .'class.phpmailer.php');
		global $SITE_NAME;
		$mail = new PHPMailer();
		$mail->From     = $email_from;
		$mail->FromName = $SITE_NAME;
		$mail->AddAddress($email_to,$emailto_name); 
		$mail->AddReplyTo($reply_to,$SITE_NAME);
		$mail->WordWrap = 50;                              // set word wrap
		$mail->IsHTML($html);                               // send as HTML
		$mail->Subject  =  $email_subject;
		$mail->Body     =  $email_body;
		$mail->Send();	
		return true;
	}
	function uploadFile($name,$tmpFile,$path) {
		$fileName = time().rand(1,1000)."__".$name;
		move_uploaded_file($tmpFile,$path.DS.$fileName);
		return $fileName;
	}
	



// Function that checks whether the data are the on-screen text.
// It works in the following way:
// an array arrfailAt stores the control words for the current state of the stack, which show that
// input data are something else than plain text.
// For example, there may be a description of font or color palette etc. 
function rtf_isPlainText($s) {
    $arrfailAt = array("*", "fonttbl", "colortbl", "datastore", "themedata");
    for ($i = 0; $i < count($arrfailAt); $i++)
        if (!empty($s[$arrfailAt[$i]])) return false;
    return true;
} 

	function rtf2text($filename) {
		// Read the data from the input file.
		$text = file_get_contents($filename);
		if (!strlen($text))
			return "";
	
		// Create empty stack array.
		$document = "";
		$stack = array();
		$j = -1;
		// Read the data character-by- character…
		for ($i = 0, $len = strlen($text); $i < $len; $i++) {
			$c = $text[$i];
	
			// Depending on current character select the further actions.
			switch ($c) {
				// the most important key word backslash
				case "\\":
					// read next character
					$nc = $text[$i + 1];
	
					// If it is another backslash or nonbreaking space or hyphen,
					// then the character is plain text and add it to the output stream.
					if ($nc == '\\' && $this->rtf_isPlainText($stack[$j])) $document .= '\\';
					elseif ($nc == '~' && $this->rtf_isPlainText($stack[$j])) $document .= ' ';
					elseif ($nc == '_' && $this->rtf_isPlainText($stack[$j])) $document .= '-';
					// If it is an asterisk mark, add it to the stack.
					elseif ($nc == '*') $stack[$j]["*"] = true;
					// If it is a single quote, read next two characters that are the hexadecimal notation
					// of a character we should add to the output stream.
					elseif ($nc == "'") {
						$hex = substr($text, $i + 2, 2);
						if ($this->rtf_isPlainText($stack[$j]))
							$document .= html_entity_decode("&#".hexdec($hex).";");
						//Shift the pointer.
						$i += 2;
					// Since, we’ve found the alphabetic character, the next characters are control word
					// and, possibly, some digit parameter.
					} elseif ($nc >= 'a' && $nc <= 'z' || $nc >= 'A' && $nc <= 'Z') {
						$word = "";
						$param = null;
	
						// Start reading characters after the backslash.
						for ($k = $i + 1, $m = 0; $k < strlen($text); $k++, $m++) {
							$nc = $text[$k];
							// If the current character is a letter and there were no digits before it,
							// then we’re still reading the control word. If there were digits, we should stop
							// since we reach the end of the control word.
							if ($nc >= 'a' && $nc <= 'z' || $nc >= 'A' && $nc <= 'Z') {
								if (empty($param))
									$word .= $nc;
								else
									break;
							// If it is a digit, store the parameter.
							} elseif ($nc >= '0' && $nc <= '9')
								$param .= $nc;
							// Since minus sign may occur only before a digit parameter, check whether
							// $param is empty. Otherwise, we reach the end of the control word.
							elseif ($nc == '-') {
								if (empty($param))
									$param .= $nc;
								else
									break;
							} else
								break;
						}
						// Shift the pointer on the number of read characters.
						$i += $m - 1;
	
						// Start analyzing what we’ve read. We are interested mostly in control words.
						$toText = "";
						switch (strtolower($word)) {
							// If the control word is "u", then its parameter is the decimal notation of the
							// Unicode character that should be added to the output stream.
							// We need to check whether the stack contains \ucN control word. If it does,
							// we should remove the N characters from the output stream.
							case "u":
								$toText .= html_entity_decode("&#x".dechex($param).";");
								$ucDelta = @$stack[$j]["uc"];
								if ($ucDelta > 0)
									$i += $ucDelta;
							break;
							// Select line feeds, spaces and tabs.
							case "par": case "page": case "column": case "line": case "lbr":
								$toText .= "\n"; 
							break;
							case "emspace": case "enspace": case "qmspace":
								$toText .= " "; 
							break;
							case "tab": $toText .= "\t"; break;
							// Add current date and time instead of corresponding labels.
							case "chdate": $toText .= date("m.d.Y"); break;
							case "chdpl": $toText .= date("l, j F Y"); break;
							case "chdpa": $toText .= date("D, j M Y"); break;
							case "chtime": $toText .= date("H:i:s"); break;
							// Replace some reserved characters to their html analogs.
							case "emdash": $toText .= html_entity_decode("&mdash;"); break;
							case "endash": $toText .= html_entity_decode("&ndash;"); break;
							case "bullet": $toText .= html_entity_decode("&#149;"); break;
							case "lquote": $toText .= html_entity_decode("&lsquo;"); break;
							case "rquote": $toText .= html_entity_decode("&rsquo;"); break;
							case "ldblquote": $toText .= html_entity_decode("&laquo;"); break;
							case "rdblquote": $toText .= html_entity_decode("&raquo;"); break;
							// Add all other to the control words stack. If a control word
							// does not include parameters, set &param to true.
							default:
								$stack[$j][strtolower($word)] = empty($param) ? true : $param;
							break;
						}
						// Add data to the output stream if required.
						if ($this->rtf_isPlainText($stack[$j]))
							$document .= $toText;
					}
	
					$i++;
				break;
				// If we read the opening brace {, then new subgroup starts and we add
				// new array stack element and write the data from previous stack element to it.
				case "{":
					array_push($stack, $stack[$j++]);
				break;
				// If we read the closing brace }, then we reach the end of subgroup and should remove 
				// the last stack element.
				case "}":
					array_pop($stack);
					$j--;
				break;
				// Skip “trash”.
				case '\0': case '\r': case '\f': case '\n': break;
				// Add other data to the output stream if required.
				default:
					if ($this->rtf_isPlainText($stack[$j]))
						$document .= $c;
				break;
			}
		}
		// Return result.
		return $document;
	}
	
	function TimeTo($future) // $original should be the future date and time in unix format
	{
		// Common time periods as an array of arrays
		$periods = array(
			array(60 * 60 * 24 * 365 , 'year'),
			array(60 * 60 * 24 * 30 , 'month'),
			array(60 * 60 * 24 * 7, 'week'),
			array(60 * 60 * 24 , 'day'),
			array(60 * 60 , 'hour'),
			array(60 , 'minute'),
		);
	   
		$today = time();
		$since = $future - $today; // Find the difference of time between now and the future
	   
		// Loop around the periods, starting with the biggest
		for ($i = 0, $j = count($periods); $i < $j; $i++)
			{
			$seconds = $periods[$i][0];
			$name = $periods[$i][1];
		   
			// Find the biggest whole period
			if (($count = floor($since / $seconds)) != 0)
					{
				break;
			}
		}
	   
		$print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	   
		if ($i + 1 < $j)
			{
			// Retrieving the second relevant period
			$seconds2 = $periods[$i + 1][0];
			$name2 = $periods[$i + 1][1];
		   
			// Only show it if it's greater than 0
			if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0)
					{
				$print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
			}
		}
		return $print;
}
	function getTimeStamp($date) {
		return strtotime($date);
	}
	function getSecretKey() {
		$salt = "AnyXYZSTring";
		$rnd = mt_rand( 0, 0x7fffffff ) ^ crc32( $salt ) ^ crc32( microtime() );
		return $secret = md5( $rnd );
	}
	
	function ago($i){
		$m = time()-$i; $o='just now';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,
	'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
		}
		return $o;
	}

}

