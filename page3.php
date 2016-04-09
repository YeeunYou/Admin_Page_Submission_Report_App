<?php 
	session_start();
	
	$error = false;

	if(!empty($_POST['course'])){
		$_SESSION['course'] = $_POST['course'];
		header('Location: thankYou.php');
	}else if($_SERVER['REQUEST_METHOD'] == "POST"){
		$error = "Fill this in!";
	}
?> 

<form method="post" action="page3.php"> 

	Course:<input type="text" name="course" maxlength="25">
	<?php if($error){echo $error; }?>
	<input type="submit">
</form>