<?php session_start(); ?>

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
    <?php include('includes/head.php');  
        //get images from database
        require_once 'includes/config.php';
        $albumID = $_GET['id'];
        if(preg_match('/^\d+$/', $albumID)){
            $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
            $album = $mysqli->query("SELECT * FROM albums WHERE albums.albumID = $albumID");
            $album_name = $album->fetch_row();
            print "<h3>$album_name[1]</h3>";  
            if ( isset( $_SESSION['logged_user_by_sql'] ) ) {
                print '<form method="post">
                <input type="submit" name="delete" value="Delete album"><br>
                <input type="text" name="new_title">
                <input type="submit" name="title_edit" value="Re-title album"><br>';
                
                if(!empty($_POST['delete'])) {
                    $album = $mysqli->query("DELETE FROM `albums` WHERE `albums`.`albumID` =  $albumID");
                    $imageinalbum= $mysqli->query("DELETE FROM `imageinalbum` WHERE `imageinalbum`.`albumID` = $albumID");
                }
                
                if(!empty($_POST['title_edit'])) {
                    if(preg_match("/^[a-zA-Z ]+$/", $_POST['new_title'])){
                        $new_cap = $_POST["new_title"];
                        $image = $mysqli->query("UPDATE `albums` SET `title` = \"$new_cap\" WHERE `albums`.`albumID` =  $albumID");
                        $mysqli->query("UPDATE `albums` SET `date_modified` = CURRENT_DATE WHERE `albums`.`albumID` =  $albumID");
                    }
                    else{
                        echo"Please enter a valid title";
                    }   
                }
            }  
            $imageinalbum= $mysqli->query('SELECT * FROM imageinalbum');
            $image = $mysqli->query("SELECT * FROM `images` INNER JOIN imageinalbum ON images.imageID = imageinalbum.imageID WHERE imageinalbum.albumID = $albumID");  
            while ( $row = $image->fetch_row() ) {  
                print "<a href='image.php?id=$row[0]'><img src=$row[2] alt=$row[1]></a>";  
            }
        }
    
    ?>
</body>