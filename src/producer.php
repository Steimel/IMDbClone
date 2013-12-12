<?php
session_start();
$producer_id = $_GET['id'];
if(empty($producer_id))
{
	header( 'Location: producers.php' ) ;
}
require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("SELECT producer_id, first_name, last_name, dob, gender FROM producers WHERE producer_id=?")) {
	$stmt->bind_param('i', $producer_id);
	$stmt->execute();
		
	$stmt->bind_result($producer_id, $first_name, $last_name, $dob, $gender);
		
	$stmt->fetch();
		
	$stmt->close();
}

$name = $first_name . ' ' . $last_name;

?>

<head>
	<title><?php echo $name; ?></title>
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
		} );
	</script>
</head>
<body>
<?php include './menu.php'; ?>
<div class="container">
	<h2><?php echo $name; ?></h2>
	<?php if(isset($_SESSION['valid']) && $_SESSION['valid']){ ?> <h3><a href="producer_edit?id=<?php echo $producer_id; ?>">Edit</a> <a href="producer_delete?id=<?php echo $producer_id ?>">Delete</a></h3> <?php } ?>
	Born: <?php echo $dob; ?><br>
	Gender: <?php echo $gender; ?><br>

	<h2>Produced:</h2>
	<table id="produced" border=1 width=100%>
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

if($stmt->prepare("SELECT movie_id, title, year, runtime, rating FROM movies WHERE movie_id IN (SELECT movie_id FROM produced WHERE producer_id=?)")) {

	$stmt->bind_param('i', $producer_id);

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
