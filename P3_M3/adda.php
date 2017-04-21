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
    <h3>add album</h3>
    
    <?php 
    if ( isset( $_SESSION['logged_user_by_sql'] ) ) {
        print ' <form action ="" method="post">
        <label>album title:</label>
        <input id = "albumAdd" type="text" name="addAlbum">
        <input type="submit" name="albumSubmit" value="Add Album">
        </form>';
        error_reporting( error_reporting() & ~E_NOTICE ); /* hides error messages about inputs*/
        require_once 'includes/config.php';
        $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
        $title = $_POST['addAlbum'];
        if(!empty($_POST['albumSubmit'])) {
            $sql = "INSERT INTO `albums`(`albumID`, `title`, `date_created`, `date_modified`) VALUES (NULL, '$title', CURRENT_DATE(), CURRENT_DATE())";
            if ($mysqli->query($sql) === TRUE) {
                echo "<p>New record created successfully</p>";
                } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
                }
        }
    }
    else{echo "Please log in!";}
   ?>
</body>