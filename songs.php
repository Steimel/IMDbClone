<html>
<head>
	<title>Songs</title>
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
			$('#songs').dataTable( {
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
<?php session_start(); $song_nav = true; include './menu.php'; ?>
<div class="container">
	<h2>Songs <?php if(isset($_SESSION['valid']) && $_SESSION['valid']){ ?> <a href="song_add">+</a> <?php } ?></h2>
	<table id="songs" border=1 width=100%>
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
	
require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select song_id, title, year, artist, runtime from songs")) {
	
	$stmt->execute();
		
	$stmt->bind_result($song_id, $title, $year, $artist, $runtime);
		
	while ($stmt->fetch()) {
		echo '<tr><td><a href="song?id=' . $song_id . '">' . $title . '</a></td>';
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
$db_connection->close();
?>

</div>
</body>
</html>

