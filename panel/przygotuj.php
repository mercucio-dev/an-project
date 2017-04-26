<?php
	session_start();
	if( !(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']==true ) )
	{
		header('Location: index.php');
		exit();
	}
	require_once "funkcje.php";
	$sciezka = 'temp2';
	if(isset($_POST['crop'])) PrzeslijPlik($sciezka,1) ? $_SESSION['wyslanoDoCrop'] = true : "";

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Panel zarządzania postami</title>
	<link href="../css/animate.css" rel="stylesheet">
	<link href="panel_styl.css" rel="stylesheet">
</head>
<body>
<b><a href="wyloguj.php">wyloguj</a></b>
<div>
	<form method="post" enctype="multipart/form-data">
				<label>Wybierz plik:</label>
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" value="Prześlij plik" name="crop">
	</form>
</div>
<div>
	<?php
    if(isset($_SESSION['wyslanoDoCrop'])) echo '<br/><h1>Wysłano plik do cropa</h1>';
    else die();
    echo "<pre>";
	    print_r($_SESSION);
    echo "</pre>";
    $ostatniPlik=$_SESSION['zdjecia'][count($_SESSION['zdjecia'])-1];
    ?>

</div>
<div>
    <?php

    list($width, $height, $type, $attr) = getimagesize($ostatniPlik);

    echo "Width: " .$width. "<br />";
    echo "Height: " .$height. "<br />";
    echo "Type: " .$type. "<br />";
    echo "Attribute: " .$attr. "<br />";
    echo "<br/>typ : ".gettype($type);

    $image = new Imagick( $ostatniPlik );
    $imageprops = $image->getImageGeometry();
    if ($imageprops['width'] <= 655 || $imageprops['height'] <= 665) {
        echo "<br/>Plik jest za mały";
        // don't upscale
    }
    else{
        $image->resizeImage(655,665, imagick::FILTER_LANCZOS, 0.9, true);
        $image->writeImage($ostatniPlik."resize");
    };


    //
//    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    //
/*    switch($type) {
        case 2:
            echo "<br/>Plik to : jpg";
            $im = imagecreatefromjpeg($ostatniPlik);
            break;
        case 3:
            echo "<br/>Plik to : png";
            $im = imagecreatefrompng($ostatniPlik);
            break;
        default:
            echo "<br/>Muszi wskazać png lub jpg !!! a nie : ".$attr;
            die();
    }
    $size = min(imagesx($im), imagesy($im));
    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
    if ($im2 !== FALSE) {
        imagepng($im2, $ostatniPlik.'-croped');
    }
    echo '<br/>Ratio to : '.$ratio=$width/$height;
    //unset($_SESSION['zdjecia']);
*/
    ?>
</div>
</body>