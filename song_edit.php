<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: songs");
	exit;
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$song_id = $_POST['song_id'];
	$title = $_POST['title'];
	$year = $_POST['year'];
	$artist = $_POST['artist'];
	$runtime = $_POST['runtime'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("UPDATE songs SET title=?, year=?, artist=?, runtime=? WHERE song_id=?")) {
		$stmt->bind_param('ssssi', $title, $year, $artist, $runtime, $song_id);
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
	
if($stmt->prepare("SELECT song_id, title, year, artist, runtime FROM songs WHERE song_id=?")) {
	$stmt->bind_param('i', $song_id);
	$stmt->execute();
		
	$stmt->bind_result($song_id, $title, $year, $artist, $runtime);
		
	$stmt->fetch();
		
	$stmt->close();
}

?>

<html>
<head>
	<title>Edit <?php echo $title; ?></title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Edit <?php echo $title ?></h1><br><br>
	<form action="song_edit" method="post">
		<input type="hidden" name="song_id" value="<?php echo $song_id; ?>" />
		Title: <input type="text" name="title" value="<?php echo $title; ?>" /><br><br>
		Year: <input type="text" name="year" value="<?php echo $year; ?>" /><br><br>
		Artist: <input type="text" name="artist" value="<?php echo $artist; ?>" /><br><br>
		Runtime (HH:MM:SS): <input type="text" name="runtime" value="<?php echo $runtime; ?>" /><br><br><br>
		<input type="submit" value="Save" />
	</form>
</div>
</body>
</html>