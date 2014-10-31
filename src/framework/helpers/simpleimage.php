<?php

class SimpleImage {

   

   var $image;

   var $image_type;

 

   function load($filename) {

       $image_info = getimagesize($filename);



if($image_info[2])

      $this->image_type = $image_info[2];

      if( $this->image_type == IMAGETYPE_JPEG ) {

         $this->image = imagecreatefromjpeg($filename);

      } elseif( $this->image_type == IMAGETYPE_GIF ) {

         $this->image = imagecreatefromgif($filename);

      } elseif( $this->image_type == IMAGETYPE_PNG ) {

        $this->image = imagecreatefrompng($filename);
//				$background = imagecolorallocate($this->image, 255, 255, 255);
        // removing the black from the placeholder
  //      imagecolortransparent($this->image, $background);




      }

	 

   }

   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

   

      if( $image_type == IMAGETYPE_JPEG ) {

         imagejpeg($this->image,$filename,$compression);

      } elseif( $image_type == IMAGETYPE_GIF ) {

         imagegif($this->image,$filename);         

      } elseif( $image_type == IMAGETYPE_PNG ) {



		imagealphablending($this->image, true );
		imagesavealpha($this->image, true );

         imagepng($this->image,$filename);

      }   

      if( $permissions != null) {

         chmod($filename,$permissions);

      }

   }

   function output($image_type=IMAGETYPE_JPEG) {

      if( $image_type == IMAGETYPE_JPEG ) {

         imagejpeg($this->image);

      } elseif( $image_type == IMAGETYPE_GIF ) {

         imagegif($this->image);         

      } elseif( $image_type == IMAGETYPE_PNG ) {

         imagepng($this->image);

      }   

   }

   function getWidth() {

      return imagesx($this->image);

   }

   function getHeight() {

      return imagesy($this->image);

   }

   function resizeToHeight($height) {

      $ratio = $height / $this->getHeight();

      $width = $this->getWidth() * $ratio;

      $this->resize($width,$height);

   }

   function resizeToWidth($width) {

	     $ratio = $width / $this->getWidth();

      $height = $this->getheight() * $ratio;

      $this->resize($width,$height);

   }

   function scale($scale) {

      $width = $this->getWidth() * $scale/100;

      $height = $this->getheight() * $scale/100; 

      $this->resize($width,$height);

   }

   function resize($width,$height) {

      $new_image = imagecreatetruecolor($width, $height);

      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());

      $this->image = $new_image;   

   }      

   function get_file_extension($file_name) {

	return substr(strrchr($file_name,'.'),1);

}



function addWatermark($imgpath,$imgSrc) {







	$filename = explode(".",basename($imgSrc));

	$ext = $filename[count($filename)-1];

   	if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg"){

	$wimage = imagecreatefromjpeg($imgpath.$imgSrc);

	} else 	if(strtolower($ext)=="gif"){

	$wimage = imagecreatefromgif($imgpath.$imgSrc);

	} else 	if(strtolower($ext)=="png"){

	$wimage = imagecreatefrompng($imgpath.$imgSrc);

	}

	$size = getimagesize($imgpath.$imgSrc);  

    // print_r($size);

	if($size[0] >100 && $size[0] < 250){ 

	$watermark = imagecreatefrompng('images/logo100.png');

	} else {

	$watermark = imagecreatefrompng('images/logo_small.png');

	}

	$watermark_width = imagesx($watermark);  

	$watermark_height = imagesy($watermark);



	$dest_x = $size[0] - $watermark_width;  

	$dest_y = $size[1] - $watermark_height;



	imagealphablending($watermark, false);

	imagesavealpha($watermark,true);



	imagealphablending($wimage, true);

	imagesavealpha($wimage,true);



	$newname = $filename[0].".".$ext;





	imagecopy($wimage,$watermark, $dest_x-5, $dest_y-5, 0, 0, $watermark_width, $watermark_height);

	imagepng($wimage,$imgpath.$newname);  

	imagedestroy($wimage);  

	imagedestroy($watermark);

	return $newname;



}





}

?>