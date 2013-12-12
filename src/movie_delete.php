<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: movies");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$movie_id = $_POST['movie_id'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("DELETE FROM movies WHERE movie_id=?")) {
		$stmt->bind_param('i', $movie_id);
		$stmt->execute();			
		$stmt->close();
	}
	
	header( 'Location: movies' ) ;
	exit;
	
}

$movie_id = $_GET['id'];

if(empty($movie_id))
{
	header( 'Location: movies' ) ;
}

require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("SELECT movie_id, title FROM movies WHERE movie_id=?")) {
	$stmt->bind_param('i', $movie_id);
	$stmt->execute();
		
	$stmt->bind_result($movie_id, $title);
		
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
	<form action="movie_delete" method="post">
		<input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>" />
		<input type="submit" value="Delete" />
	</form>
</div>
</body>
</html>