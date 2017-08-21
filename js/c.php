<?php
if(isset($_POST['pos']))
{
$from_top = abs($_POST['pos']);
$default_cover_width = 918;
$default_cover_height = 276;
	// includo la classe
	require_once("thumbncrop.inc.php"); //php class for image resizing & cropping
	
	// valorizzo la variabile
	$tb = new ThumbAndCrop();
	
	// apro l'immagine
	$tb->openImg("original.jpg"); //original cover image
	
	$newHeight = $tb->getRightHeight($default_cover_width);
	
	$tb->creaThumb($default_cover_width, $newHeight);

	$tb->setThumbAsOriginal();
	
	$tb->cropThumb($default_cover_width, 276, 0, $from_top);
	
	
	$tb->saveThumb("cover.jpg"); //save cropped cover image
	
	$tb->resetOriginal();
	
	$tb->closeImg();

$data['status'] = 200;
$data['url'] = 'cover.jpg';
echo json_encode($data);
}
?>