<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: actors");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$actor_id = $_POST['actor_id'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("DELETE FROM actors WHERE actor_id=?")) {
		$stmt->bind_param('i', $actor_id);
		$stmt->execute();			
		$stmt->close();
	}
	
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

$name = $first_name . ' ' . $last_name;

?>

<html>
<head>
	<title>Delete <?php echo $name; ?></title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Delete <?php echo $name ?>?</h1><br><br>
	<form action="actor_delete" method="post">
		<input type="hidden" name="actor_id" value="<?php echo $actor_id; ?>" />
		<input type="submit" value="Delete" />
	</form>
</div>
</body>
</html>