<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: directors");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$director_id = $_POST['director_id'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("DELETE FROM directors WHERE director_id=?")) {
		$stmt->bind_param('i', $director_id);
		$stmt->execute();			
		$stmt->close();
	}
	
	header( 'Location: directors' ) ;
	exit;
	
}

$director_id = $_GET['id'];

if(empty($director_id))
{
	header( 'Location: directors' ) ;
}

require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("SELECT director_id, first_name, last_name, dob, gender FROM directors WHERE director_id=?")) {
	$stmt->bind_param('i', $director_id);
	$stmt->execute();
		
	$stmt->bind_result($director_id, $first_name, $last_name, $dob, $gender);
		
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
	<form action="director_delete" method="post">
		<input type="hidden" name="director_id" value="<?php echo $director_id; ?>" />
		<input type="submit" value="Delete" />
	</form>
</div>
</body>
</html>