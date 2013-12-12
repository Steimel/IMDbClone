<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: songs");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$song_id = $_POST['song_id'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("DELETE FROM songs WHERE song_id=?")) {
		$stmt->bind_param('i', $song_id);
		$stmt->execute();			
		$stmt->close();
	}
	
	header( 'Location: songs' ) ;
	exit;
	
}

$song_id = $_GET['id'];

if(empty($song_id))
{
	header( 'Location: songs' ) ;
}

require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("SELECT song_id, title FROM songs WHERE song_id=?")) {
	$stmt->bind_param('i', $song_id);
	$stmt->execute();
		
	$stmt->bind_result($song_id, $title);
		
	$stmt->fetch();
		
	$stmt->close();
}

?>

<html>
<head>
	<title>Delete <?php echo $title; ?></title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Delete <?php echo $title ?>?</h1><br><br>
	<form action="song_delete" method="post">
		<input type="hidden" name="song_id" value="<?php echo $song_id; ?>" />
		<input type="submit" value="Delete" />
	</form>
</div>
</body>
</html>