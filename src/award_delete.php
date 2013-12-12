<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: awards");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$award_id = $_POST['award_id'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("DELETE FROM awards WHERE award_id=?")) {
		$stmt->bind_param('i', $award_id);
		$stmt->execute();			
		$stmt->close();
	}
	
	header( 'Location: awards' ) ;
	exit;
	
}

$award_id = $_GET['id'];

if(empty($award_id))
{
	header( 'Location: awards' ) ;
}

require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("SELECT award_id, award_name FROM awards WHERE award_id=?")) {
	$stmt->bind_param('i', $award_id);
	$stmt->execute();
		
	$stmt->bind_result($award_id, $award_name);
		
	$stmt->fetch();
		
	$stmt->close();
}

?>

<html>
<head>
	<title>Delete <?php echo $award_name; ?></title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Delete <?php echo $award_name ?>?</h1><br><br>
	<form action="award_delete" method="post">
		<input type="hidden" name="award_id" value="<?php echo $award_id; ?>" />
		<input type="submit" value="Delete" />
	</form>
</div>
</body>
</html>