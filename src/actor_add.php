<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: actors");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$stage_name = $_POST['stage_name'];
	$dob = $_POST['dob'];
	if($dob == '') $dob = null;
	$gender = $_POST['gender'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("INSERT INTO actors (first_name, last_name, stage_name, dob, gender) VALUES (?,?,?,?,?)")) {
		$stmt->bind_param('sssss', $first_name, $last_name, $stage_name, $dob, $gender);
		$stmt->execute();			
		$stmt->close();
		
		$message_type = "success";
		$message = "Actor Added Successfully!";
	}
}

?>

<html>
<head>
	<title>Add Actor</title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Add Actor</h1><br><br>
	<form action="actor_add" method="post">
		First Name: <input type="text" name="first_name" /><br><br>
		Last Name: <input type="text" name="last_name" /><br><br>
		Stage Name: <input type="text" name="stage_name" /><br><br>
		Date of Birth (YYYY/MM/DD): <input type="text" name="dob" /><br><br><br>
		<input type="radio" name="gender" value="Male" /> Male<br><br>
		<input type="radio" name="gender" value="Female" /> Female<br><br><br>
		<input type="submit" value="Add Actor" />
	</form>
</div>
</body>
</html>