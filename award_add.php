<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: awards");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$award_name = $_POST['award_name'];
	$award_society = $_POST['award_society'];
	$year = $_POST['year'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("INSERT INTO awards (award_name, award_society, year) VALUES (?,?,?)")) {
		$stmt->bind_param('sss', $award_name, $award_society, $year);
		$stmt->execute();			
		$stmt->close();
		
		$message_type = "success";
		$message = "Award Added Successfully!";
	}
}

?>

<html>
<head>
	<title>Add Award</title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Add Award</h1><br><br>
	<form action="award_add" method="post">
		Name: <input type="text" name="award_name" /><br><br>
		Society: <input type="text" name="award_society" /><br><br>
		Year: <input type="text" name="year" /><br><br><br>
		<input type="submit" value="Add Award" />
	</form>
</div>
</body>
</html>