<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: actors");
	exit;
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$actor_id = $_POST['actor_id'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$stage_name = $_POST['stage_name'];
	$dob = $_POST['dob'];
	if($dob == '') $dob = null;
	$gender = $_POST['gender'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("UPDATE actors SET first_name=?, last_name=?, stage_name=?, dob=?, gender=? WHERE actor_id=?")) {
		$stmt->bind_param('sssssi', $first_name, $last_name, $stage_name, $dob, $gender, $actor_id);
		$stmt->execute();			
		$stmt->close();
	}
	
	$db_connection->close();
	
	header( 'Location: actors' ) ;
	exit;
	
}

$actor_id = $_GET['id'];

if(empty($actor_id))
{
	header( 'Location: actors' ) ;
}

require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("SELECT actor_id, first_name, last_name, stage_name, dob, gender FROM actors WHERE actor_id=?")) {
	$stmt->bind_param('i', $actor_id);
	$stmt->execute();
		
	$stmt->bind_result($actor_id, $first_name, $last_name, $stage_name, $dob, $gender);
		
	$stmt->fetch();
		
	$stmt->close();
}


	$db_connection->close();

$name = $first_name . ' ' . $last_name;

?>

<html>
<head>
	<title>Edit <?php echo $name; ?></title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Edit <?php echo $name ?></h1><br><br>
	<form action="actor_edit" method="post">
		<input type="hidden" name="actor_id" value="<?php echo $actor_id; ?>" />
		First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>" /><br><br>
		Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>" /><br><br>
		Stage Name: <input type="text" name="stage_name" value="<?php echo $stage_name; ?>" /><br><br>
		Date of Birth (YYYY/MM/DD): <input type="text" name="dob" value="<?php echo $dob; ?>" /><br><br><br>
		<input type="radio" name="gender" value="Male" <?php if($gender=="Male"){echo 'checked';} ?> /> Male<br><br>
		<input type="radio" name="gender" value="Female" <?php if($gender=="Female"){echo 'checked';} ?> /> Female<br><br><br>
		<input type="submit" value="Save" />
	</form>
</div>
</body>
</html>