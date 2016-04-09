<?php 
define('HOST', "localhost");
define('USER', "username");
define('PASSWORD', "password");

// creating the connection 
    $conn = new mysqli(HOST, USER , PASSWORD);

//check connection 
if($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
 
?>


