<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Good Food Photos</title>
    <link href='https://fonts.googleapis.com/css?family=Gravitas+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <link href='css/style.css' rel='stylesheet' type='text/css'>
</head>
    
<body>
    <?php include('includes/head.php'); ?>
    <h3>all albums</h3>
    <?php
        //get album info from database
        require_once 'includes/config.php';
        $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
        $album = $mysqli->query('SELECT * FROM albums');
        $imageinalbum = $mysqli->query('SELECT * FROM imageinalbum');
        while ( $row = $album->fetch_row() ) {
            error_reporting( error_reporting() & ~E_NOTICE ); /* hides error messages about inputs*/
            $thumbnail = $mysqli->query("SELECT MAX(imageinalbum.imageID) FROM imageinalbum WHERE imageinalbum.albumID=$row[0]")->fetch_row()[0];
            if($thumbnail!=0){
            	$img_thumb = $mysqli->query("SELECT * FROM images WHERE images.imageID=$thumbnail")->fetch_row();
            	print "<a href='album.php?id=$row[0]'><div class='container'><img src=$img_thumb[2] alt='image_thumbnail'>
            	<div class='overlay'><div class='text'>$row[1]</div></div></div></a>"; 
	    }
	    else{echo "<p><a href='album.php?id=$row[0]'>$row[1]</a></p>";}
        }
    ?>
</body>
    