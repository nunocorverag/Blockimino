<?php
include("includes/header.php");

$id_perfil = $id_usuario_loggeado;
$nombre_usuario = $usuario_loggeado;
$imgSrc = "";
$result_path = "";
$msg = "";

/***********************************************************
    0 - Remove The Temp image if it exists
***********************************************************/
if (!isset($_POST['x']) && !isset($_FILES['image']['name'])) {
    // Delete users temp image
    $temppath = 'assets/images/profile_pics/'.$nombre_usuario.'_temp.jpeg';
    if (file_exists($temppath)) {
        @unlink($temppath);
    }
}

if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
    /***********************************************************
     * 1 - Upload Original Image To Server
     ***********************************************************/
    // Get Name | Size | Temp Location
    $ImageName = $_FILES['image']['name'];
    $ImageSize = $_FILES['image']['size'];
    $ImageTempName = $_FILES['image']['tmp_name'];
    // Get File Ext
    $ImageType = @explode('/', $_FILES['image']['type']);
    $type = $ImageType[1]; // file type
    // Set Upload directory
    $uploaddir = 'assets/images/profile_pics';
    // Set File name
    $file_temp_name = $nombre_usuario.'_original.'.md5(time()).'n'.$type; // the temp file name
    $fullpath = $uploaddir."/".$file_temp_name; // the temp file path
    $file_name = $nombre_usuario.'_temp.jpeg'; //$nombre_usuario.'_temp.'.$type; // for the final resized image
    $fullpath_2 = $uploaddir."/".$file_name; // for the final resized image

    // Check if the uploaded file type is valid
    $allowedTypes = array('jpg', 'jpeg', 'png');
    if (!in_array($type, $allowedTypes)) {
        echo 'Tipo de imagen no válido. Por favor, sube un archivo .png, .jpg o .jpeg.';
        echo '<br/><br/><button class="boton_recargar_upload"><a href="upload.php" style="color: black">Recargar pagina</a></button>';
        exit;
	}

    // Move the file to the correct location
    $move = move_uploaded_file($ImageTempName, $fullpath);
    chmod($fullpath, 0777);
    // Check for valid upload
    if (!$move) {
        die('No se pudo subir el archivo');
    } else {
        $imgSrc = "assets/images/profile_pics/".$file_name; // the image to display in crop area
        $msg = "¡Subida completa!";   // message to page
        $src = $file_name;           // the file name to post from cropping form to the resize
    }

/***********************************************************
	2  - Resize The Image To Fit In Cropping Area
***********************************************************/		
		//get the uploaded image size	
			clearstatcache();				
			$original_size = getimagesize($fullpath);
			$original_width = $original_size[0];
			$original_height = $original_size[1];	
		// Specify The new size
			$main_width = 500; // set the width of the image
			$main_height = $original_height / ($original_width / $main_width);	// this sets the height in ratio									
		//create new image using correct php func			
			if($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg"){
				$src2 = imagecreatefromjpeg($fullpath);
			}elseif($_FILES["image"]["type"] == "image/png"){ 
				$src2 = imagecreatefrompng($fullpath);
			}else{ 
				$msg .= "Hubo un error al subir el archivo. Porfavor sube archivos de tipo .jpg, .png o .jpeg.<br />";
			}
		//create the new resized image
			$main = imagecreatetruecolor($main_width,$main_height);
			imagecopyresampled($main,$src2,0, 0, 0, 0,$main_width,$main_height,$original_width,$original_height);
		//upload new version
			$main_temp = $fullpath_2;
			imagejpeg($main, $main_temp, 90);
			chmod($main_temp,0777);
		//free up memory
			imagedestroy($src2);
			imagedestroy($main);
			//imagedestroy($fullpath);
			@ unlink($fullpath); // delete the original upload					
									
}//ADD Image 	

/***********************************************************
	3- Cropping & Converting The Image To Jpg
***********************************************************/
if (isset($_POST['x'])){
	
	//the file type posted
		$type = $_POST['type'];	
	//the image src
		$src = 'assets/images/profile_pics/'.$_POST['src'];	
		$finalname = $nombre_usuario.md5(time());	
	
	if($type == 'jpg' || $type == 'jpeg' || $type == 'JPG' || $type == 'JPEG'){	
	
		//the target dimensions 150x150
			$targ_w = $targ_h = 150;
		//quality of the output
			$jpeg_quality = 90;
		//create a cropped copy of the image
			$img_r = imagecreatefromjpeg($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		//save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 	
			 		
	}else if($type == 'png' || $type == 'PNG'){
		
		//the target dimensions 150x150
			$targ_w = $targ_h = 150;
		//quality of the output
			$jpeg_quality = 90;
		//create a cropped copy of the image
			$img_r = imagecreatefrompng($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		//save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 	
						
	}else if($type == 'gif' || $type == 'GIF'){
		
		//the target dimensions 150x150
			$targ_w = $targ_h = 150;
		//quality of the output
			$jpeg_quality = 90;
		//create a cropped copy of the image
			$img_r = imagecreatefromgif($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		//save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 	
		
	}
		//free up memory
			imagedestroy($img_r); // free up memory
			imagedestroy($dst_r); //free up memory
			@ unlink($src); // delete the original upload					
		
		//return cropped image to page	
		$result_path ="assets/images/profile_pics/".$finalname."n.jpeg";

		//Insert image into database
		$insert_pic_query = mysqli_query($con, "UPDATE usuarios SET foto_perfil='$result_path' WHERE id_usuario='$id_usuario_loggeado'");
		header("Location: ".$usuario_loggeado);
														
}// post x
?>
<div id="Overlay" style=" width:100%; height:100%; border:0px #990000 solid; position:absolute; top:0px; left:0px; z-index:2000; display:none;"></div>
<div class="main_column column">


	<div class="columna_principal" id="formExample">
		
	    <p><b> <?=$msg?> </b></p>
	    
	    <form action="upload.php" method="post"  enctype="multipart/form-data">
	        Sube una nueva foto de perfil!<br /><br />
	        <input type="file" id="image" name="image" style="width:50%; height:30px; " /><br /><br />
	        <input type="submit" value="Confirmar" style="width:85px; height:25px;" />
	    </form><br /><br />
	    
	</div> <!-- Form-->  


    <?php
    if($imgSrc){ //if an image has been uploaded display cropping area?>
	    <script>
	    	$('#Overlay').show();
			$('#formExample').hide();
	    </script>
	    <div id="CroppingContainer" style="width:800px; max-height:600px; background-color:#FFF; margin: 0 auto; position:relative; overflow:hidden; border:2px #666 solid; z-index:2001; padding-bottom:0px;">  
	    
	        <div id="CroppingArea" style="width:500px; max-height:500px; position:relative; overflow:hidden; margin:40px 0px 40px 40px; border:2px #666 solid; float:left;">	
	            <img src="<?=$imgSrc?>" border="0" id="jcrop_target" style="border:0px #990000 solid; position:relative; margin:0px 0px 0px 0px; padding:0px; " />
	        </div>  

	        <div id="InfoArea" style="width:180px; height:210px; position:relative; overflow:hidden; margin:40px 0px 0px 40px; border:0px #666 solid; float:left;">	
	           <p style="margin:0px; padding:0px; color:#444; font-size:18px;">          
	                <b>Recorta la imagen</b><br /><br />
	                <span style="font-size:14px;">
						Recorta/cambia el tamaño de tu imagen de perfil cargada. <br />
						Una vez que esté satisfecho con su imagen de perfil, haga clic en Guardar.
	                </span>
	           </p>
	        </div>  

	        <br />

	        <div id="CropImageForm" style="width:100px; height:30px; float:left; margin:10px 0px 0px 40px;" >  
	            <form action="upload.php" method="post" onsubmit="return checkCoords();">
	                <input type="hidden" id="x" name="x" />
	                <input type="hidden" id="y" name="y" />
	                <input type="hidden" id="w" name="w" />
	                <input type="hidden" id="h" name="h" />
	                <input type="hidden" value="jpeg" name="type" /> <?php // $type ?> 
	                <input type="hidden" value="<?=$src?>" name="src" />
	                <input type="submit" value="Guardar" style="width:100px; height:30px;"   />
	            </form>
	        </div>

	        <div id="CropImageForm2" style="width:100px; height:30px; float:left; margin:10px 0px 0px 40px;" >  
	            <form action="upload.php" method="post" onsubmit="return cancelCrop();">
	                <input type="submit" value="Cancelar" style="width:100px; height:30px;"   />
	            </form>
	        </div>            
	            
	    </div><!-- CroppingContainer -->
	<?php 
	} ?>
</div>
 
 <?php if($result_path) {
	 ?>
     
     <img src="<?=$result_path?>" style="position:relative; margin:10px auto; width:150px; height:150px;" />
	 
 <?php } ?>
    <br /><br />