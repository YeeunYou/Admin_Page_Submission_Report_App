<?php 
	session_start();

	$error = false;

	if(!empty($_POST['school'])){
		$_SESSION['school'] = $_POST['school'];
		header('Location: page3.php');
	}else if($_SERVER['REQUEST_METHOD'] == "POST"){
		$error = "Fill this in!";
	}
?> 

<form method="post" action="page2.php"> 

	School:<input type="text" name="school" maxlength="25">
	<?php if($error){echo $error; }?>
	<input type="submit">
</form>