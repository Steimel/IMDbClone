<?php
session_start();
//If not logged in, redirect
if(!(isset($_SESSION['valid']) && $_SESSION['valid'])){
	header("Location: movies");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$movie_id = $_POST['movie_id'];
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
		
	if($stmt->prepare("UPDATE movies SET title=?, year=?, runtime=?, rating=? WHERE movie_id=?")) {
		$stmt->bind_param('ssssi', $title, $year, $runtime, $rating, $movie_id);
		$stmt->execute();			
		$stmt->close();
		
		// remove old actors
		$stmt = $db_connection->stmt_init();
		if($stmt->prepare("DELETE FROM appeared_in WHERE movie_id=?")) {
			$stmt->bind_param('i', $movie_id);
			$stmt->execute();			
			$stmt->close();
		}
		// add the new set
		for($i = 0; $i < count($actors); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO appeared_in (actor_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $actors[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		// remove old directors
		$stmt = $db_connection->stmt_init();
		if($stmt->prepare("DELETE FROM directed WHERE movie_id=?")) {
			$stmt->bind_param('i', $movie_id);
			$stmt->execute();			
			$stmt->close();
		}
		// add the new set
		for($i = 0; $i < count($directors); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO directed (director_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $directors[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		// remove old producers
		$stmt = $db_connection->stmt_init();
		if($stmt->prepare("DELETE FROM produced WHERE movie_id=?")) {
			$stmt->bind_param('i', $movie_id);
			$stmt->execute();			
			$stmt->close();
		}
		// add the new set
		for($i = 0; $i < count($producers); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO produced (producer_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $producers[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		// remove old songs
		$stmt = $db_connection->stmt_init();
		if($stmt->prepare("DELETE FROM featured_in WHERE movie_id=?")) {
			$stmt->bind_param('i', $movie_id);
			$stmt->execute();			
			$stmt->close();
		}
		// add the new set
		for($i = 0; $i < count($songs); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO featured_in (song_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $songs[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
		
		// remove old awards
		$stmt = $db_connection->stmt_init();
		if($stmt->prepare("DELETE FROM awarded_to WHERE movie_id=?")) {
			$stmt->bind_param('i', $movie_id);
			$stmt->execute();			
			$stmt->close();
		}
		// add the new set
		for($i = 0; $i < count($awards); $i++)
		{
			$stmt = $db_connection->stmt_init();
			if($stmt->prepare("INSERT INTO awarded_to (award_id, movie_id) VALUES (?,?)")) {
				$stmt->bind_param('ii', $awards[$i], $movie_id);
				$stmt->execute();			
				$stmt->close();
			}
		}
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

if($stmt->prepare("SELECT movie_id, title, year, runtime, rating FROM movies WHERE movie_id=?")) {
	$stmt->bind_param('i', $movie_id);
	$stmt->execute();
		
	$stmt->bind_result($movie_id, $title, $year, $runtime, $rating);
		
	$stmt->fetch();
		
	$stmt->close();
}


?>

<html>
<head>
	<title>Edit <?php echo $title; ?></title>
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
	<h1>Edit <?php echo $title; ?></h1><br><br>
	<form action="movie_edit" method="post">
		<input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>" />
		Title: <input type="text" name="title" value="<?php echo $title; ?>" /><br><br>
		Year: <input type="text" name="year" value="<?php echo $year; ?>" /><br><br>
		Runtime (minutes): <input type="text" name="runtime" value="<?php echo $runtime; ?>" /><br><br>
		Rating: <select name="rating">
					<option value="NR" <?php if($rating=="NR"){echo 'selected';} ?>>NR</option>
					<option value="G" <?php if($rating=="G"){echo 'selected';} ?>>G</option>
					<option value="PG" <?php if($rating=="PG"){echo 'selected';} ?>>PG</option>
					<option value="PG-13" <?php if($rating=="PG-13"){echo 'selected';} ?>>PG-13</option>
					<option value="R" <?php if($rating=="R"){echo 'selected';} ?>>R</option>
					<option value="NC-17" <?php if($rating=="NC-17"){echo 'selected';} ?>>NC-17</option>
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


// Find current actors
$currentActors = array();
$stmt = $db_connection->stmt_init();

if($stmt->prepare("select actor_id from appeared_in WHERE movie_id=?")) {
	$stmt->bind_param('i', $movie_id);
	$stmt->execute();
		
	$stmt->bind_result($actor_id);
	
	
	while($stmt->fetch()){
		$currentActors[$actor_id] = true;
	}
		
	$stmt->close();
} 

$stmt = $db_connection->stmt_init();

if($stmt->prepare("select actor_id, first_name, last_name, stage_name, dob, gender from actors")) {
	
	$stmt->execute();
		
	$stmt->bind_result($actor_id, $first_name, $last_name, $stage_name, $dob, $gender);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="actors[]" value="' . $actor_id . '"';
		if($currentActors[$actor_id] == true)
		{
			echo ' checked';
		}
		echo ' /></td>';
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

// Find current directors
$currentDirectors = array();
$stmt = $db_connection->stmt_init();

if($stmt->prepare("select director_id from directed WHERE movie_id=?")) {
	$stmt->bind_param('i', $movie_id);
	$stmt->execute();
		
	$stmt->bind_result($director_id);
	
	
	while($stmt->fetch()){
		$currentDirectors[$director_id] = true;
	}
		
	$stmt->close();
} 

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select director_id, first_name, last_name, dob, gender from directors")) {
	
	$stmt->execute();
		
	$stmt->bind_result($director_id, $first_name, $last_name, $dob, $gender);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="directors[]" value="' . $director_id . '"';
		if($currentDirectors[$director_id] == true)
		{
			echo ' checked';
		}
		echo ' /></td>';
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

// Find current producers
$currentProducers = array();
$stmt = $db_connection->stmt_init();

if($stmt->prepare("select producer_id from produced WHERE movie_id=?")) {
	$stmt->bind_param('i', $movie_id);
	$stmt->execute();
		
	$stmt->bind_result($producer_id);
	
	
	while($stmt->fetch()){
		$currentProducers[$producer_id] = true;
	}
		
	$stmt->close();
} 

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select producer_id, first_name, last_name, dob, gender from producers")) {
	
	$stmt->execute();
		
	$stmt->bind_result($producer_id, $first_name, $last_name, $dob, $gender);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="producers[]" value="' . $producer_id . '"';
		if($currentProducers[$producer_id] == true)
		{
			echo ' checked';
		}
		echo ' /></td>';
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

// Find current songs
$currentSongs = array();
$stmt = $db_connection->stmt_init();

if($stmt->prepare("select song_id from featured_in WHERE movie_id=?")) {
	$stmt->bind_param('i', $movie_id);
	$stmt->execute();
		
	$stmt->bind_result($song_id);
	
	
	while($stmt->fetch()){
		$currentSongs[$song_id] = true;
	}
		
	$stmt->close();
} 

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select song_id, title, year, artist, runtime from songs")) {
	
	$stmt->execute();
		
	$stmt->bind_result($song_id, $title, $year, $artist, $runtime);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="songs[]" value="' . $song_id . '"';
		if($currentSongs[$song_id] == true)
		{
			echo ' checked';
		}
		echo ' /></td>';
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

// Find current songs
$currentAwards = array();
$stmt = $db_connection->stmt_init();

if($stmt->prepare("select award_id from awarded_to WHERE movie_id=?")) {
	$stmt->bind_param('i', $movie_id);
	$stmt->execute();
		
	$stmt->bind_result($award_id);
	
	
	while($stmt->fetch()){
		$currentAwards[$award_id] = true;
	}
		
	$stmt->close();
} 

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select award_id, award_name, award_society, year from awards")) {
	
	$stmt->execute();
		
	$stmt->bind_result($award_id, $award_name, $award_society, $year);
		
	while ($stmt->fetch()) {
		echo '<tr><td><input type="checkbox" name="awards[]" value="' . $award_id . '"';
		if($currentAwards[$award_id] == true)
		{
			echo ' checked';
		}
		echo ' /></td>';
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
		<input type="submit" value="Save" />
	</form>
</div>
</body>
</html>