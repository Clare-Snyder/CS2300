<?php session_start();
if (isset($_SESSION['logged_user_by_sql'])) {
$olduser = $_SESSION['logged_user_by_sql'];
unset( $_SESSION['logged_user_by_sql'] );
} else {
$olduser = false;
}
?>

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
    <h3>logout</h3>
    <?php 
        if ( $olduser ) {
            print( "<p>Return to our <a href='login.php'>login page</a></p>");
        } else {
            print( "<p>You haven’t logged in.</p>");
            print( "<p>Go to our <a href='login.php'>login page</a></p>" );
        }
    ?>
    
</body>