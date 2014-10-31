<?php

class rating {
	function rating($stars,$id,$path,$readOnly=1) {

		$this->tempVars["STARS"] = $stars;

		//$this->tempVars["SITE_URL"] = $this->getSiteUrl();
		//print_r($_SERVER);
		$substract = intval($stars)*22;
		?>
		<style>
		.star<?php echo $id?> {
		background:url(<?php echo $path;?>framework/helpers/rating/stars.png);
		width:100px;
		height:22px;
		background-position:0px <?php echo 132-$substract;?>px;
		}
		.starparent<?php echo $id?> {
		width:100px;
		height:22px;
		}
		</style>
		<?php

		if(!$readOnly) {
		
		?>
		<script>
		$(document).ready(function() {
		var tmpStarClicked<?php echo $id?> = <?php echo intval($stars)?>;
		var tmpStar<?php echo $id?> = <?php echo intval($stars)?>;
		$("#<?php echo $id?>").mousemove(function(e) {
		//alert($(this).offset().left);
			var parentOffset = $(this).parent().offset(); 
			//or $(this).offset(); if you really just want the current element's offset
			//alert(e.pageX);
			var relX = parseFloat(e.pageX) - parseFloat(parentOffset.left);
			var relY = parseFloat(e.pageY) - parseFloat(parentOffset.top);

			if(relX > 83.5) {
				tmpStar<?php echo $id?> = 5;
			} else 			if(relX < 83.5 && relX > 64.5) {
				tmpStar<?php echo $id?> = 4;
			} else 			if(relX < 64.5 && relX > 45.5) {
				tmpStar<?php echo $id?> = 3;
			} else 			if(relX < 45.5 && relX > 25.5) {
				tmpStar<?php echo $id?> = 2;
			} else 			if(relX < 25.5) {
				tmpStar<?php echo $id?> = 1;
			}
//alert(tmpStar<?php echo $id?>);
			$('#<?php echo $id?>').css('background-position', "0px "+ (132-(tmpStar<?php echo $id?>*22)) + "px");
		});
		$("#<?php echo $id?>").click(function(e) {
		tmpStarClicked<?php echo $id?> = tmpStar<?php echo $id?>;
		if(tmpStarClicked<?php echo $id?>) {
		$('#<?php echo $id?>').css('background-position', "0px "+ (132-(tmpStarClicked<?php echo $id?>*22)) + "px");
		}
		$('#hidden_<?php echo $id?>').val(tmpStarClicked<?php echo $id?>);
		});
		
		$("#<?php echo $id?>").mouseout(function(e) {
		if(tmpStarClicked<?php echo $id?>) {
		$('#<?php echo $id?>').css('background-position', "0px "+ (132-(tmpStarClicked<?php echo $id?>*22)) + "px");
		}
		});
		});
		</script>
		<?php
		}
		?>
		<div class="starparent<?php echo $id?>"><div class="star<?php echo $id?>" id="<?php echo $id?>"></div></div>
		<input type="hidden" name="hidden_<?php echo $id?>" id="hidden_<?php echo $id?>" value="<?php echo intval($stars)?>">
		<?php
	}
}