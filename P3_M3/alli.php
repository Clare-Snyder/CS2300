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
    <h3>all images</h3>
    <?php 
        //get images from database
        require_once 'includes/config.php';
        $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
        $album = $mysqli->query('SELECT * FROM albums');
        $imageinalbum= $mysqli->query('SELECT * FROM imageinalbum');
        $image = $mysqli->query('SELECT * FROM images');     
        while ( $row = $image->fetch_row() ) {  
            print "<a href='image.php?id=$row[0]'><img src=$row[2] alt=$row[1]></a>";  
        }
    
    ?>
</body>