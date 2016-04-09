<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Submission Report</title>
	<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
<div id="wrap">
	<h2>Submission Report</h2>
	<?php 
	require_once("connect.php");
	$conn->select_db("databasename");

	$formVals = array();
	$formVals['username'] = (isset($_POST['username']) ? $_POST['username'] : "");
	$formVals['school'] = (isset($_POST['school']) ? $_POST['school'] : "" );
	$formVals['course'] = (isset($_POST['course']) ? $_POST['course'] : "" );

		if(isset($_GET['action']))
		{
			print "<h3>-" . $_GET['action']. "-</h3>";

			switch ($_GET['action']) 
			{
				case 'delete':
					if (isset($_GET['id']) && is_numeric($_GET['id']))
					{
						$deleteQry = sprintf("UPDATE users SET active = 0 WHERE id = %d", $_GET['id']);
						$conn->query($deleteQry);
						if ($conn->affected_rows > 0)
						{
							print "USER DELETED!<br/>";
						}
					}
					break;
				
				case 'edit':
					if (isset($_GET['id']) && is_numeric($_GET['id']))
					{
						if($_SERVER['REQUEST_METHOD'] != 'POST')
						{	  
							$getQry = sprintf("SELECT * FROM users WHERE ID = %d AND active = 1", $_GET['id']);
							$getRS = $conn->query($getQry);
							if($getRS->num_rows > 0)
							{
								$formVals = $getRS->fetch_assoc();
							}
						}
						else
						{  
							$name = '';
							$getQry = sprintf("SELECT photo FROM users WHERE ID = %d AND active = 1", $_GET['id']); 
							$getRS = $conn->query($getQry);
							while($row = mysqli_fetch_assoc($getRS)) 
							{
								$name = $row['photo'];
							}
							$newFileName = $_FILES['photo']['name'];
							$isValid = true;
							if(isset($_FILES['photo']) && !empty($_FILES['photo']) && $_FILES['photo']['size'] != 0)
							{ 
								if($_FILES['photo']['error'] != 0)
								{
									$isValid = false; 
								} 
								$ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
								if($isValid) 
								{  
									$name = "profilePhoto" . $_GET['id'] . "." . $ext;
									chmod($_FILES['photo']['tmp_name'], 0777);
								
									if(move_uploaded_file($_FILES['photo']['tmp_name'], $name))
									{
										$updateQry = sprintf("UPDATE users SET username = '%s', school = '%s', course = '%s', photo = '%s' WHERE id = %d", addslashes($formVals['username']), addslashes($formVals['school']), addslashes($formVals['course']), $name , $_GET['id']);
										$conn->query($updateQry); 
										if($conn->affected_rows > 0)
										{
											echo "Update worked!<br/>";
										}
										else
										{
											echo "INSERT failed!";
										}
									}	
									else
									{
										echo "Error!: moving file failed";
									}				
								} 
							}
							else
							{
								$updateQry = sprintf("UPDATE users SET username = '%s', school = '%s', course = '%s' WHERE id = %d", addslashes($formVals['username']), addslashes($formVals['school']), addslashes($formVals['course']) , $_GET['id']);
								$conn->query($updateQry); 
								if($conn->affected_rows > 0)
								{
									echo "Update worked!<br/>";
								}
							} 
							
						}
					}
					break;

				case 'new':  
				if ($_SERVER['REQUEST_METHOD'] == "POST")
				{
					$isValid = true;
					if(!isset($_FILES['photo']) || empty($_FILES['photo']))
					{ 
						$newQry = sprintf("INSERT INTO users( username, school, course, active, photo ) VALUES ('%s','%s','%s',%d, '%s')", addslashes($formVals['username']), addslashes($formVals['school']),addslashes($formVals['course']), 1, "no photo");
						$conn->query($newQry);
							if($conn->affected_rows > 0)
							{
								echo "INSERT Successful!";
							}
					}
					else
					{ 
						if($_FILES['photo']['size'] == 0)
						{
							$isValid = false; 
						}
						if($_FILES['photo']['error'] != 0)
						{
							$isValid = false; 
						} 
						$ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
						//For counting records in the database for "profilephoto#.ext" wherer # is the user id 
						$countQry = sprintf("SELECT COUNT(id) AS count FROM users");
						$result = mysqli_query($conn, $countQry);
						$countResult = $result->fetch_object()->count; 
						if($isValid) 
						{  
							$name = "profilePhoto" . ($countResult + 1) . "." . $ext;
							chmod($_FILES['photo']['tmp_name'], 0777);
						
							if(move_uploaded_file($_FILES['photo']['tmp_name'], $name))
							{
								$newQry = sprintf("INSERT INTO users( username, school, course, active, photo ) VALUES ('%s','%s','%s',%d, '%s')", addslashes($formVals['username']), addslashes($formVals['school']),addslashes($formVals['course']), 1, $name);
								$conn->query($newQry);
								if($conn->affected_rows > 0)
								{
									echo "INSERT Successful!";
								}
								else
								{
									echo "INSERT failed!";
								}
							}	
							else
							{
								echo "Error!: moving file failed";
							}				
						}
						else
						{
							echo "Upload failed!: size invalid";
						}
					} 
				} 
					break;
				default:
					break;
			}
		} 
	?>	 
		<form method='post' enctype='multipart/form-data'>
		<label>Profile Photo:</label> <br/><input type='file' name='photo' accept='image/jpeg, image/jpg, image/png' ><br/>
		<label>Username:</label><br/><input type="text" name="username" value="<?php echo $formVals['username']; ?>" maxlength="25"></br> 
		<label>School:</label><br/><input type="text" name="school" value="<?php echo $formVals['school']; ?>" maxlength="25"></br>
		<label>Course:</label><br/><input type="text" name="course" value="<?php echo $formVals['course']; ?>" maxlength="25"></br>
		<br/><input id="submit" type="submit">
		</form>
	<?php
	$usersRS = $conn->query('SELECT * FROM users WHERE active = 1');
	if($usersRS)
	{
		echo "<table>";
	?>
			<tr>
				<th>ID</th>
				<th>User</th>
				<th>School</th>
				<th>Course</th>
				<th>Profile Photo</th>
				<th colspan="2"><a id="new" href="admin.php?action=new">New</a></th>
			</tr>
	<?php
		while ($row = $usersRS->fetch_array())
		{
			if($row['photo'] == "no photo")
			{
				echo 
			"<tr>
				<td>".$row['id']."</td>
				<td>".$row['username']."</td>
				<td>".$row['school']."</td>
				<td>".$row['course']."</td>
				<td>no photo</td>
				<td>
					<a id='delete' href=\"admin.php?action=delete&id=".$row['id']."\">Delete</a>
				</td>
				<td>
					<a id='edit' href=\"admin.php?action=edit&id=".$row['id']."\">Edit</a>
				</td>
			</tr>";
			}
			else
			{
				echo 
			"<tr>
				<td>".$row['id']."</td>
				<td>".$row['username']."</td>
				<td>".$row['school']."</td>
				<td>".$row['course']."</td>
				<td><img src=". $row['photo'] ."   width='100' height='100'></td>
				<td>
					<a id='delete' href=\"admin.php?action=delete&id=".$row['id']."\">Delete</a>
				</td>
				<td>
					<a id='edit' href=\"admin.php?action=edit&id=".$row['id']."\">Edit</a>
				</td>
			</tr>";
			}
		}
		echo "</table>";
	}
?>
</div>
</body>
</html>





