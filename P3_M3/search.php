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
    <h3>search images * search albums</h3>
    <form method="post" > <!--Form for searching:-->
        Search: <input type="text" name="search" /> 
        <label>Image</label><input type='checkbox' name='img_search' value='Yes'>
        <label>Album</label><input type='checkbox' name='album_search' value='Yes'>
        <br>
        <input type="submit" name="submit" value="Search" />
    </form>
    
     <?php
        error_reporting( error_reporting() & ~E_NOTICE ); /* hides error messages about inputs*/
        if(isset($_POST['submit'])){
            if(preg_match("/^[a-zA-Z ]+$/", $_POST['search'])) {
                $search = $_POST['search'];
                require_once 'includes/config.php';
                $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $img_checked = $_POST['img_search'];
                $album_checked = $_POST['album_search'];
                //Searching both images and albums:
                if($img_checked=='Yes'&&$album_checked=='Yes'){
                    $search_result = $mysqli->query("SELECT DISTINCT(images.imageID), caption, file_name, credit, Date_Taken FROM `images` INNER JOIN imageinalbum ON images.imageID = imageinalbum.imageID INNER JOIN albums ON imageinalbum.albumID=albums.albumID WHERE caption LIKE '%$search%' OR title LIKE '%$search%'");
                    while ( $row = $search_result->fetch_row() ) {  
                        print "<a href='image.php?id=$row[0]'><img src=$row[2] alt=$row[1]></a>";  
                    }
                }
                //only searching images:
                else if($img_checked=='Yes'){
                    $search_result = $mysqli->query("SELECT DISTINCT(images.imageID), caption, file_name, credit, Date_Taken FROM `images` INNER JOIN imageinalbum ON images.imageID = imageinalbum.imageID INNER JOIN albums ON imageinalbum.albumID=albums.albumID WHERE caption LIKE '%$search%'");
                    while ( $row = $search_result->fetch_row() ) {  
                        print "<a href='image.php?id=$row[0]'><img src=$row[2] alt=$row[1]></a>";  
                    }
                }
                //only searching albums:
                else if($album_checked=='Yes'){
                    $search_result = $mysqli->query("SELECT DISTINCT(images.imageID), caption, file_name, credit, Date_Taken FROM `images` INNER JOIN imageinalbum ON images.imageID = imageinalbum.imageID INNER JOIN albums ON imageinalbum.albumID=albums.albumID WHERE title LIKE '%$search%'");  
                    while ( $row = $search_result->fetch_row() ) {  
                        print "<a href='image.php?id=$row[0]'><img src=$row[2] alt=$row[1]></a>s";  
                    }
                }
                else{
                    echo "Please select at least one of the above checkboxes!";
                }

            }
            else{
                print"Invalid search: Must contain letters and spaces only!";
            }    
        }
    ?>
    
</body>