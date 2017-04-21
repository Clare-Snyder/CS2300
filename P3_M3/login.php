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
    <h3>login</h3>
    <?php 
        $post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
        $post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
        if ( empty( $post_username ) || empty( $post_password ) ) {
    ?>
            <form method="post">
                Username: <input type="text" name="username"> <br>
                Password: <input type="password" name="password"> <br>
                <input type="submit" value="Submit">
            </form> 
    
    <?php

		} else {
            error_reporting( error_reporting() & ~E_NOTICE ); /* hides error messages about inputs*/
            require_once 'includes/config.php';
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "SELECT * FROM login_info WHERE username = '$post_username'";
            $result = $mysqli->query($query);
            $row = $result->fetch_row();
            $db_hash_password = $row[1];
			if( password_verify($post_password, $db_hash_password ) ) {
					$db_username = $row[0];
					$_SESSION['logged_user_by_sql'] = $db_username;
				}
        } 
            //successful login:
            if ( isset($_SESSION['logged_user_by_sql'] ) ) {
				echo '<p>Login successful</p>';
                print '<p><a href="logout.php">log out</a></p>';
            //incorrect login info:    
			} else {
				echo '<p>You did not login successfully.</p>';
				echo '<p>Please try again.</p>';
			}
    ?>
    
</body>