<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: movies");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$title = $_POST['title'];
	$year = $_POST['year'];
	$runtime = $_POST['runtime'];
	$rating = $_POST['rating'];
	
	$actors = $_POST['actors'];
	$directors = $_POST['directors'];
	$producers = $_POST['producers'];
	$songs = $_POST['songs'];
	$awards = $_POST['awards'];
	
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openDataReadWriteConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("INSERT INTO movies (title, year, runtime, rating) VALUES (?,?,?,?)")) {
		$stmt->bind_param('ssss', $title, $year, $runtime, $rating);
		$stmt->execute();			
		$stmt->close();
		
		$movie_id = mysqli_insert_id($db_connection);
		
		for($i = 0; $i < count($actors); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO appeared_in (actor_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $actors[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		for($i = 0; $i < count($directors); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO directed (director_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $directors[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		for($i = 0; $i < count($producers); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO produced (producer_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $producers[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		for($i = 0; $i < count($songs); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO featured_in (song_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $songs[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		for($i = 0; $i < count($awards); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO awarded_to (award_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $awards[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		$message_type = "success";
		$message = "Movie Added Successfully!";
	}
	
	
}

?>

<html>
<head>
	<title>Add Movie</title>
	<style type="text/css" title="currentStyle">
		@import "media/css/jquery.dataTables.css";
		@import "media/css/TableTools.css";
	</style>
	<link href="media/css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="media/js/TableTools.min.js"></script>
	<script>
		$(document).ready(function() {
			var actor_table = $('#actors').dataTable( {
				"sDom": 'rtip',
				"iDisplayLength" : -1
			} );
			var director_table = $('#directors').dataTable( {
				"sDom": 'rtip',
				"iDisplayLength" : -1
			} );
			var producer_table = $('#producers').dataTable( {
				"sDom": 'rtip',
				"iDisplayLength" : -1
			} );
			var song_table = $('#songs').dataTable( {
				"sDom": 'rtip',
				"iDisplayLength" : -1
			} );
			var award_table = $('#awards').dataTable( {
				"sDom": 'rtip',
				"iDisplayLength" : -1
			} );
			
			actor_table.fnSort([[1,'asc']]);
			director_table.fnSort([[1,'asc']]);
			producer_table.fnSort([[1,'asc']]);
			song_table.fnSort([[1,'asc']]);
			award_table.fnSort([[1,'asc'], [2, 'asc'], [3, 'asc']]);
		} );
	</script>
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h1>Add Movie</h1><br><br>
	<form action="movie_add" method="post">
		Title: <input type="text" name="title" /><br><br>
		Year: <input type="text" name="year" /><br><br>
		Runtime (minutes): <input type="text" name="runtime" /><br><br>
		Rating: <select name="rating">
					<option value="NR">NR</option>
					<option value="G">G</option>
					<option value="PG">PG</option>
					<option value="PG-13">PG-13</option>
					<option value="R">R</option>
					<option value="NC-17">NC-17</option>
				</select>
		<br><br><br>
		
	<h2>Actors</h2>
	<table id="actors" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Add</th>
			<th>Name</th>
			<th>Stage Name</th>
			<th>DOB</th>
			<th>Gender</th>
		</tr>
	</thead>
	<tbody>
<?php
		
require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select actor_id, first_name, last_name, stage_name, dob, gender from actors")) {
	
	$stmt->execute();
		
	$stmt->bind_result($actor_id, $first_name, $last_name, $stage_name, $dob, $gender);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="actors[]" value="' . $actor_id . '" /></td>';
		echo '<td>' . $last_name . ', ' . $first_name . '</td>';
		echo "\n";
		echo "<td>" . $stage_name . "</td>";
		echo "\n";
		echo "<td>" . $dob . "</td>";
		echo "\n";
		echo "<td>" . $gender . "</td></tr>";
		echo "\n";
		echo "\n";
	}
		
	echo "</tbody></table>";
	$stmt->close();
}
?>
<br><br>
<h2>Directors</h2>
	<table id="directors" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Add</th>
			<th>Name</th>
			<th>DOB</th>
			<th>Gender</th>
		</tr>
	</thead>
	<tbody>
<?php

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select director_id, first_name, last_name, dob, gender from directors")) {
	
	$stmt->execute();
		
	$stmt->bind_result($director_id, $first_name, $last_name, $dob, $gender);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="directors[]" value="' . $director_id . '" /></td>';
		echo '<td>' . $last_name . ', ' . $first_name . '</td>';
		echo "\n";
		echo "<td>" . $dob . "</td>";
		echo "\n";
		echo "<td>" . $gender . "</td></tr>";
		echo "\n";
		echo "\n";
	}
		
	echo "</tbody></table>";
	$stmt->close();
}
?>
<br><br>
<h2>Producers</h2>
	<table id="producers" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Add</th>
			<th>Name</th>
			<th>DOB</th>
			<th>Gender</th>
		</tr>
	</thead>
	<tbody>
<?php

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select producer_id, first_name, last_name, dob, gender from producers")) {
	
	$stmt->execute();
		
	$stmt->bind_result($producer_id, $first_name, $last_name, $dob, $gender);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="producers[]" value="' . $producer_id . '" /></td>';
		echo '<td>' . $last_name . ', ' . $first_name . '</td>';
		echo "\n";
		echo "<td>" . $dob . "</td>";
		echo "\n";
		echo "<td>" . $gender . "</td></tr>";
		echo "\n";
		echo "\n";
	}
		
	echo "</tbody></table>";
	$stmt->close();
}
?>
<br><br>
<h2>Songs</h2>
	<table id="songs" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Add</th>
			<th>Title</th>
			<th>Year</th>
			<th>Artist</th>
			<th>Runtime</th>
		</tr>
	</thead>
	<tbody>
<?php

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select song_id, title, year, artist, runtime from songs")) {
	
	$stmt->execute();
		
	$stmt->bind_result($song_id, $title, $year, $artist, $runtime);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="songs[]" value="' . $song_id . '" /></td>';
		echo '<td>' . $title . '</td>';
		echo "\n";
		echo "<td>" . $year . "</td>";
		echo "\n";
		echo "<td>" . $artist . "</td>";
		echo "\n";
		echo "<td>" . $runtime . "</td></tr>";
		echo "\n";
		echo "\n";
	}
		
	echo "</tbody></table>";
	$stmt->close();
}
?>
<br><br>
<h2>Awards</h2>
	<table id="awards" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Add</th>
			<th>Name</th>
			<th>Society</th>
			<th>Year</th>
		</tr>
	</thead>
	<tbody>
<?php

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select award_id, award_name, award_society, year from awards")) {
	
	$stmt->execute();
		
	$stmt->bind_result($award_id, $award_name, $award_society, $year);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="awards[]" value="' . $award_id . '" /></td>';
		echo '<td>' . $award_name . '</td>';
		echo "\n";
		echo "<td>" . $award_society . "</td>";
		echo "\n";
		echo "<td>" . $year . "</td></tr>";
		echo "\n";
		echo "\n";
	}
		
	echo "</tbody></table>";
	$stmt->close();
}

$db_connection->close();

?>
		
		<br><br><br>
		<input type="submit" value="Add Movie" />
	</form>
</div>
</body>
</html>