<?php 
	session_start();

	unset($_SESSION['isCompleted']);
	$error = false;

	if(!empty($_POST['username'])){
		$_SESSION['username'] = $_POST['username'];
		header('Location: page2.php');
	}else if($_SERVER['REQUEST_METHOD'] == "POST"){
		$error = "Fill this in!";
	}
?> 

<form method="post" action="page1.php"> 

	Username:<input type="text" name="username" maxlength="25">
	<?php if($error){echo $error; }?>
	<input type="submit">
</form>