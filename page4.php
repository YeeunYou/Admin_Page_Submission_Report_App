<?php
session_start(); 
require_once("connect.php");
$conn->select_db("week7");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	if(!isset($_SESSION['isCompleted']))
	{  
		$isvalid = true;

 		if(isset($_POST['file']) && !empty($_FILES))
 		{
 			if($_FILES['file']['size'] == 0)
 			{
 				$isvalid = false;
 			}
 			if($_FILES['file']['error'] != 0)
 			{
 				$isvalid = false;
 			}
 	
 			if($isvalid)
 			{
 				$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
 				$name = "profilephoto" . $ext;
 			}
 		}
 		else
 		{
 			$_SESSION['noFile'] = $_FILES['file'];
 			$_SESSION['noFile'] = "no photo";
 		}
 	}
 	else
 	{
 		echo "You've already submitted!";
 	}
}
?>

<form method="post" enctype="multipart/form-data">
	Add your profile picture: 
	<input type="file" name="file" accept="image/jpeg, image/jpg, image/png">
	<br/>
	<br/>
	<input type="submit"  value="Save">
</form>