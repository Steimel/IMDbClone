<?php
session_start();
$movie_id = $_GET['id'];
if(empty($movie_id))
{
	header( 'Location: movies.php' ) ;
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

<head>
	<title><?php echo $title; ?></title>
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
			$('#appeared_in').dataTable( {
				"sDom": 'T<"clear">frtip',
				"oTableTools": {
					"aButtons": [
						"copy",
						"csv",
						"pdf",
						"print"
					]
				}
			} );
			$('#directed').dataTable( {
				"sDom": 'T<"clear">frtip',
				"oTableTools": {
					"aButtons": [
						"copy",
						"csv",
						"pdf",
						"print"
					]
				}
			} );
			$('#produced').dataTable( {
				"sDom": 'T<"clear">frtip',
				"oTableTools": {
					"aButtons": [
						"copy",
						"csv",
						"pdf",
						"print"
					]
				}
			} );
			$('#featured_in').dataTable( {
				"sDom": 'T<"clear">frtip',
				"oTableTools": {
					"aButtons": [
						"copy",
						"csv",
						"pdf",
						"print"
					]
				}
			} );
			$('#awarded_to').dataTable( {
				"sDom": 'T<"clear">frtip',
				"oTableTools": {
					"aButtons": [
						"copy",
						"csv",
						"pdf",
						"print"
					]
				}
			} );
		} );
	</script>
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h2><?php echo $title; ?></h2>
	<?php if(isset($_SESSION['valid']) && $_SESSION['valid']){ ?> <h3><a href="movie_edit?id=<?php echo $movie_id; ?>">Edit</a> <a href="movie_delete?id=<?php echo $movie_id ?>">Delete</a></h3> <?php } ?>
	Year: <?php echo $year; ?><br>
	Runtime: <?php echo $runtime; ?> min<br>
	Rating: <?php echo $rating; ?><br>

	<h2>Actors:</h2>
	<table id="appeared_in" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Name</th>
			<th>Stage Name</th>
			<th>DOB</th>
			<th>Gender</th>
		</tr>
	</thead>
	<tbody>
<?php

$stmt = $db_connection->stmt_init();

if($stmt->prepare("SELECT actor_id, first_name, last_name, stage_name, dob, gender FROM actors WHERE actor_id IN (SELECT actor_id FROM appeared_in WHERE movie_id=?)")) {

	$stmt->bind_param('i', $movie_id);

	$stmt->execute();
	
	$stmt->bind_result($actor_id, $first_name, $last_name, $stage_name, $dob, $gender);
	
	while ($stmt->fetch()) {
		echo "<tr><td><a href='actor?id=" . $actor_id . "'>" . $last_name . ', ' . $first_name . "</a></td>";
		echo "\n";
		echo '<td>' . $stage_name . '</td>';
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
<h2>Directors:</h2>
	<table id="directed" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Name</th>
			<th>DOB</th>
			<th>Gender</th>
		</tr>
	</thead>
	<tbody>

<?php

$stmt = $db_connection->stmt_init();

if($stmt->prepare("SELECT director_id, first_name, last_name, dob, gender FROM directors WHERE director_id IN (SELECT director_id FROM directed WHERE movie_id=?)")) {

	$stmt->bind_param('i', $movie_id);

	$stmt->execute();
	
	$stmt->bind_result($director_id, $first_name, $last_name, $dob, $gender);
	
	while ($stmt->fetch()) {
		echo "<tr><td><a href='director?id=" . $director_id . "'>" . $last_name . ', ' . $first_name . "</a></td>";
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
<h2>Producers:</h2>
	<table id="produced" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Name</th>
			<th>DOB</th>
			<th>Gender</th>
		</tr>
	</thead>
	<tbody>

<?php

$stmt = $db_connection->stmt_init();

if($stmt->prepare("SELECT producer_id, first_name, last_name, dob, gender FROM producers WHERE producer_id IN (SELECT producer_id FROM produced WHERE movie_id=?)")) {

	$stmt->bind_param('i', $movie_id);

	$stmt->execute();
	
	$stmt->bind_result($producer_id, $first_name, $last_name, $dob, $gender);
	
	while ($stmt->fetch()) {
		echo "<tr><td><a href='producer?id=" . $producer_id . "'>" . $last_name . ', ' . $first_name . "</a></td>";
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
<h2>Songs:</h2>
	<table id="featured_in" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Title</th>
			<th>Year</th>
			<th>Artist</th>
			<th>Runtime</th>
		</tr>
	</thead>
	<tbody>

<?php

$stmt = $db_connection->stmt_init();

if($stmt->prepare("SELECT song_id, title, year, artist, runtime FROM songs WHERE song_id IN (SELECT song_id FROM featured_in WHERE movie_id=?)")) {

	$stmt->bind_param('i', $movie_id);

	$stmt->execute();
	
	$stmt->bind_result($song_id, $title, $year, $artist, $runtime);
	
	while ($stmt->fetch()) {
		echo "<tr><td><a href='song?id=" . $song_id . "'>" . $title . "</a></td>";
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
<h2>Awards:</h2>
	<table id="awarded_to" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Name</th>
			<th>Society</th>
			<th>Year</th>
		</tr>
	</thead>
	<tbody>

<?php

$stmt = $db_connection->stmt_init();

if($stmt->prepare("SELECT award_id, award_name, award_society, year FROM awards WHERE award_id IN (SELECT award_id FROM awarded_to WHERE movie_id=?)")) {

	$stmt->bind_param('i', $movie_id);

	$stmt->execute();
	
	$stmt->bind_result($award_id, $award_name, $award_society, $year);
	
	while ($stmt->fetch()) {
		echo "<tr><td><a href='song?id=" . $award_id . "'>" . $award_name . "</a></td>";
		echo "\n";
		echo "<td>" . $award_society . "</td>";
		echo "\n";
		echo "<td>" . $year . "</td>";
		echo "\n";
		echo "\n";
	}
		
	echo "</tbody></table>";
	$stmt->close();
}


$db_connection->close();

?>


<br><br>
</div>
</body>
</html>
