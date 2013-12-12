<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: songs");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$title = $_POST['title'];
	$year = $_POST['year'];
	$artist = $_POST['artist'];
	$runtime = $_POST['runtime'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("INSERT INTO songs (title, year, artist, runtime) VALUES (?,?,?,?)")) {
		$stmt->bind_param('ssss', $title, $year, $artist, $runtime);
		$stmt->execute();			
		$stmt->close();
		
		$message_type = "success";
		$message = "Song Added Successfully!";
	}
}

?>

<html>
<head>
	<title>Add Song</title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Add Song</h1><br><br>
	<form action="song_add" method="post">
		Title: <input type="text" name="title" /><br><br>
		Year: <input type="text" name="year" /><br><br>
		Artist: <input type="text" name="artist" /><br><br>
		Runtime (HH:MM:SS): <input type="text" name="runtime" /><br><br><br>
		<input type="submit" value="Add Song" />
	</form>
</div>
</body>
</html>