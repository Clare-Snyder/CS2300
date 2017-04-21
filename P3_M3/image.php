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
        $imageID = $_GET['id'];
        if(preg_match('/^\d+$/', $imageID)){
            $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
            $image = $mysqli->query("SELECT * FROM images WHERE images.imageID = $imageID"); 
            $image = $image->fetch_row();
            print "<img id='fullimage' src=$image[2] alt=$image[1]>"; 
            print "<p>food: $image[1]</p>";
            if ( isset( $_SESSION['logged_user_by_sql'] ) ) {
                print '<form method="post">
                <input type="submit" name="delete" value="Delete image"><br>
                <input type="text" name="new_caption">
                <input type="submit" name="cap_edit" value="Re-caption image"><br>';
                if(!empty($_POST['delete'])) {
                    $image = $mysqli->query("DELETE FROM `images` WHERE `images`.`imageID` =  $imageID");
                    $imageinalbum= $mysqli->query("DELETE FROM `imageinalbum` WHERE `imageinalbum`.`imageID` = $imageID");
                }
                
                if(!empty($_POST['cap_edit'])) {
                    if(preg_match("/^[a-zA-Z ]+$/", $_POST['new_caption'])){
                        $new_cap = $_POST['new_caption'];
                        $image = $mysqli->query("UPDATE `images` SET `caption` = \"$new_cap\" WHERE `images`.`imageID` =  $imageID");
                    }
                    else{
                        echo"Please enter a valid caption name";
                    }   
                }
                
                print'<p>Remove image from albums:</p>';
                $albums_remove = $mysqli->query("SELECT * FROM albums INNER JOIN imageinalbum on albums.albumID=imageinalbum.albumID WHERE imageinalbum.imageID=$imageID");
                while ($row = $albums_remove->fetch_row()) {
                    print "<label>$row[1]</label><input type='checkbox' name='$row[0]' value='Yes'><br>"; 
                    
                }
                print'<p>Add image to albums:</p>';
                $albums_add = $mysqli->query("SELECT * FROM albums INNER JOIN `imageinalbum` ON albums.albumID=imageinalbum.albumID GROUP BY imageinalbum.albumID HAVING imageinalbum.imageID !=$imageID");
                while ($row = $albums_add->fetch_row()) {
                    print "<label>$row[1]</label><input type='checkbox' name='$row[1]' value='Yes'><br>"; 
                    
                }
                
                print'<input type="submit" name="sel_img_edit" value="Edit image albums"><br>';
                
                
                if(!empty($_POST['sel_img_edit'])) {
                    $albums_remove = $mysqli->query("SELECT * FROM albums INNER JOIN imageinalbum on   albums.albumID=imageinalbum.albumID WHERE imageinalbum.imageID=$imageID");
                    $albums_add = $mysqli->query("SELECT * FROM albums INNER JOIN `imageinalbum` ON albums.albumID=imageinalbum.albumID GROUP BY imageinalbum.albumID HAVING imageinalbum.imageID !=$imageID");
                    while ($row = $albums_remove->fetch_row()) {
                        if(!empty($_POST[$row[0]])){
                            $mysqli->query("DELETE FROM `imageinalbum` WHERE imageinalbum.imageID =$imageID AND imageinalbum.albumID = $row[0]");
                        }
                    }
                    while ($row = $albums_add->fetch_row()) {
                        if(!empty($_POST[$row[1]])){
                            $mysqli->query("INSERT INTO `imageinalbum` (`imageID`, `albumID`) VALUES ('$imageID', '$row[0]')");
                        }
                    }
                } 
            }   
        } 
            print "credit: $image[3]<br>";
            print "date taken: $image[4]<br>";
            $albums = $mysqli->query("SELECT * FROM `imageinalbum` INNER JOIN albums ON imageinalbum.albumID = albums.albumID WHERE imageinalbum.imageID=$imageID");  
            print "current albums:<br>";
            while ( $row = $albums->fetch_row() ) {  
                print "$row[3]<br>";  
            }  
    ?>
</body>
    
  