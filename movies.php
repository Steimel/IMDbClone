<html>
<head>
	<title>Movies</title>
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
			$('#movies').dataTable( {
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
<?php session_start(); $movie_nav = true; include './menu.php'; ?>
<div class="container">
	<h2>Movies <?php if(isset($_SESSION['valid']) && $_SESSION['valid']){ ?> <a href="movie_add">+</a> <?php } ?></h2>
	<table id="movies" border=1 width=100%>
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
	
require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select movie_id, title, year, runtime, rating from movies")) {
	
	$stmt->execute();
		
	$stmt->bind_result($movie_id, $title, $year, $runtime, $rating);
		
	while ($stmt->fetch()) {
		echo '<tr><td><a href="movie?id=' . $movie_id . '">' . $title . '</a></td>';
		echo "\n";
		echo "<td>" . $year . "</td>";
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

