<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: producers");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$producer_id = $_POST['producer_id'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("DELETE FROM producers WHERE producer_id=?")) {
		$stmt->bind_param('i', $producer_id);
		$stmt->execute();			
		$stmt->close();
	}
	
	header( 'Location: producers' ) ;
	exit;
	
}

$producer_id = $_GET['id'];

if(empty($producer_id))
{
	header( 'Location: producers' ) ;
}

require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("SELECT producer_id, first_name, last_name, dob, gender FROM producers WHERE producer_id=?")) {
	$stmt->bind_param('i', $producer_id);
	$stmt->execute();
		
	$stmt->bind_result($producer_id, $first_name, $last_name, $dob, $gender);
		
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
	<form action="producer_delete" method="post">
		<input type="hidden" name="producer_id" value="<?php echo $producer_id; ?>" />
		<input type="submit" value="Delete" />
	</form>
</div>
</body>
</html>