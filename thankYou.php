<?php 
session_start();

require_once("connect.php");
$conn->select_db("databasename");
//print_r( $conn);
if(!isset($_SESSION['isCompleted']))
{  
	//insert into db 
	$qry = sprintf("INSERT INTO `users`( `username`, `school`, `course`, `active`)	
	VALUES 	('%s','%s','%s',%d)",$_SESSION['username'],$_SESSION['school'],	$_SESSION['course'],1);
	$conn->query($qry);

	if ($conn->affected_rows > 0){
	echo "Thank You! You submitted:<br/>";
	echo "<br/>Username: ".$_SESSION['username'];
	echo "<br/>School: ".$_SESSION['school'];
	echo "<br/>Course: ".$_SESSION['course'];

	//sending an email
	$_SESSION['isCompleted'] = true; 
	}
}
else
{
	echo "You've already submitted!";
}
 
?> 
