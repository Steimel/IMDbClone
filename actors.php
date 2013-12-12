<html>
<head>
	<title>Actors</title>
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
			$('#actors').dataTable( {
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
<?php session_start(); $actor_nav = true; include './menu.php'; ?>
<div class="container">
	<h2>Actors <?php if(isset($_SESSION['valid']) && $_SESSION['valid']){ ?> <a href="actor_add">+</a> <?php } ?></h2>
	<table id="actors" border=1 width=100%>
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
	
require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select actor_id, first_name, last_name, stage_name, dob, gender from actors")) {
	
	$stmt->execute();
		
	$stmt->bind_result($actor_id, $first_name, $last_name, $stage_name, $dob, $gender);
		
	while ($stmt->fetch()) {
		echo '<tr><td><a href="actor?id=' . $actor_id . '">' . $last_name . ', ' . $first_name . '</a></td>';
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
$db_connection->close();
?>

</div>
</body>
</html>

