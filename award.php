<?php
session_start();
$award_id = $_GET['id'];
if(empty($award_id))
{
	header( 'Location: awards.php' ) ;
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

<head>
	<title><?php echo $award_name; ?></title>
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
	<h2><?php echo $award_name; ?></h2>
	<?php if(isset($_SESSION['valid']) && $_SESSION['valid']){ ?> <h3><a href="award_edit?id=<?php echo $award_id; ?>">Edit</a> <a href="award_delete?id=<?php echo $award_id ?>">Delete</a></h3> <?php } ?>
	Society: <?php echo $award_society; ?><br>
	Year: <?php echo $year; ?><br>

	<h2>Awarded To:</h2>
	<table id="awarded_to" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Title</th>
			<th>Year</th>
			<th>Runtime</th>
			<th>Rating</th>
		</tr>
	</thead>
	<tbody>
<?php

$stmt = $db_connection->stmt_init();

if($stmt->prepare("SELECT movie_id, title, year, runtime, rating FROM movies WHERE movie_id IN (SELECT movie_id FROM awarded_to WHERE award_id=?)")) {

	$stmt->bind_param('i', $award_id);

	$stmt->execute();
	
	$stmt->bind_result($movie_id, $title, $year, $runtime, $rating);
	
	while ($stmt->fetch()) {
		echo '<tr><td><a href="movie?id=' . $movie_id . '">' . $title . '</a></td>';
		echo "\n";
		echo '<td>' . $year . '</td>';
		echo "\n";
		echo "<td>" . $runtime . " min</td>";
		echo "\n";
		echo "<td>" . $rating . "</td></tr>";
		echo "\n";
		echo "\n";
	}
		
	echo "</tbody></table>";
	$stmt->close();
}

$db_connection->close();

?>


	
</div>
</body>
</html>
