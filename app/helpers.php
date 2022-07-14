<?php



function makeImagePreview($src, $dst, $dest_width = 295, $dest_height = 200)
{
	if(!list($width, $height) = getimagesize($src)) return "Unsupported picture type!";

	if ($width / $height < $dest_width / $dest_height) { // будет выше $dest_height
		$new_width = $dest_width;
		$new_height = $height / ($width / $dest_width);
	}else{ // будет шире $dest_width
		$new_width = $width / ($height / $dest_height);
		$new_height = $dest_height;
	}

	if (!resizeImage($src, $dst, $new_width)) {
		copy($src, $dst);
	};
}


function resizeImage($src, $dst, $width, $height = 0, $crop=0){

	if(!$height) $height = $width;

	if(!list($w, $h) = getimagesize($src)) return abort(404); // "Unsupported picture type!";

	$type = strtolower(substr(strrchr($src,"."),1));
	if($type == 'jpeg') $type = 'jpg';
	switch($type){
		case 'bmp': $img = imagecreatefromwbmp($src); break;
		case 'gif': $img = imagecreatefromgif($src); break;
		case 'jpg': $img = imagecreatefromjpeg($src); break;
		case 'png': $img = imagecreatefrompng($src); break;
		default : return "Unsupported picture type!";
	}

	// resize
	if($crop){
		if($w < $width or $h < $height) return false; // "Picture is too small!";
		$ratio = max($width/$w, $height/$h);
		$h = $height / $ratio;
		$x = ($w - $width / $ratio) / 2;
		$w = $width / $ratio;
	}
	else{
		if($w < $width and $h < $height) return false; // "Picture is too small!";
		$ratio = min($width/$w, $height/$h);
		$width = $w * $ratio;
		$height = $h * $ratio;
		$x = 0;
	}

	$new = imagecreatetruecolor($width, $height);

	// preserve transparency
	if($type == "gif" or $type == "png"){
		imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
		imagealphablending($new, false);
		imagesavealpha($new, true);
	}

	imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

	switch($type){
		case 'bmp': imagewbmp($new, $dst); break;
		case 'gif': imagegif($new, $dst); break;
		case 'jpg': imagejpeg($new, $dst); break;
		case 'png': imagepng($new, $dst); break;
	}
	return true;
}