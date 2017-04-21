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
    <?php include('includes/head.php'); ?>
    <h3>add image</h3>
    
    <?php 
    if ( isset( $_SESSION['logged_user_by_sql'] ) ) {
        print '<form action ="" method="post" enctype="multipart/form-data">
        <label>Upload image:</label>
        <input type="file" name="uploadImg"><br>
        <p>Add to zero or more albums:</p>';
         require_once 'includes/config.php';
            $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
            $album = $mysqli->query('SELECT * FROM albums');
            while ($row = $album->fetch_row() ) {
                $album_id = $row[0];
                $album_name = $row[1];
                print "<label>$album_name</label><input type='checkbox' name='$album_id' value='Yes'><br>"; 
            }
        print '<label>image caption:</label>
        <input id = "imageAdd" type="text" name="addCaption">
        <label>image citation (URL):</label>
        <input id = "citationAdd" type="text" name="addCitation">
        <input type="submit" name="imageSubmit" value="Add Image">
        </form>';
        error_reporting( error_reporting() & ~E_NOTICE ); /* hides error messages about inputs*/
        require_once 'includes/config.php';
        $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
        $caption = $_POST['addCaption'];
        $citation = $_POST['addCitation'];
        $image = $_FILES['uploadImg'];
        $originalName = $image['name'];
        $tempName = $image['tmp_name'];
        if(!empty($_POST['imageSubmit'])) {
            move_uploaded_file($tempName, "images/$originalName");
            $_SESSION['photos'][] = $originalName;
            $sql = "INSERT INTO `images`(`imageID`, `caption`, `file_name`, `credit`, `Date_Taken`) VALUES (NULL, '\"$caption\"', '\"images/$originalName\"','$citation', CURRENT_DATE())";
            if ($mysqli->query($sql) === TRUE) {
                echo "New record created successfully";
                } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
                }
                $album = $mysqli->query('SELECT * FROM albums');
                while ($row = $album->fetch_row() ) {
                    $album_id = $row[0];
                    $albumChecked = $_POST[$album_id];
                    $imgID = $mysqli->query("SELECT MAX(imageID) FROM `images`");
                    $imgID = $imgID->fetch_row()[0];
                    if($albumChecked=='Yes'){
                        $mysqli->query("INSERT INTO `imageinalbum`(`imageID`, `albumID`) VALUES ('$imgID', '$album_id')");
                    }
                }
        }
    }
    else{
        echo "<p>Please log in!</p>";
    }
    ?>
</body>