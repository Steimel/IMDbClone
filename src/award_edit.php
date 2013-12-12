<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: awards");
	exit;
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$award_id = $_POST['award_id'];
	$award_name = $_POST['award_name'];
	$award_society = $_POST['award_society'];
	$year = $_POST['year'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("UPDATE awards SET award_name=?, award_society=?, year=? WHERE award_id=?")) {
		$stmt->bind_param('sssi', $award_name, $award_society, $year, $award_id);
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
	
if($stmt->prepare("SELECT award_id, award_name, award_society, year FROM awards WHERE award_id=?")) {
	$stmt->bind_param('i', $award_id);
	$stmt->execute();
		
	$stmt->bind_result($award_id, $award_name, $award_society, $year);
		
	$stmt->fetch();
		
	$stmt->close();
}

?>

<html>
<head>
	<title>Edit <?php echo $award_name; ?></title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Edit <?php echo $award_name ?></h1><br><br>
	<form action="award_edit" method="post">
		<input type="hidden" name="award_id" value="<?php echo $award_id; ?>" />
		Name: <input type="text" name="award_name" value="<?php echo $award_name; ?>" /><br><br>
		Society: <input type="text" name="award_society" value="<?php echo $award_society; ?>" /><br><br>
		Year: <input type="text" name="year" value="<?php echo $year; ?>" /><br><br><br>
		<input type="submit" value="Save" />
	</form>
</div>
</body>
</html>