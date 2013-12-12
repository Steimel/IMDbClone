<html>
<head>
	<title>Awards</title>
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
			$('#awards').dataTable( {
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
<?php session_start(); $award_nav = true; include './menu.php'; ?>
<div class="container">
	<h2>Awards <?php if(isset($_SESSION['valid']) && $_SESSION['valid']){ ?> <a href="award_add">+</a> <?php } ?></h2>
	<table id="awards" border=1 width=100%>
	<thead>
		<tr background=#ff8c00>
			<th>Name</th>
			<th>Society</th>
			<th>Year</th>
		</tr>
	</thead>
	<tbody>
<?php
	
require_once("./dbconnect.php");
$db_connection = DbUtil::openDataReadOnlyConnection();

$stmt = $db_connection->stmt_init();
	
if($stmt->prepare("select award_id, award_name, award_society, year from awards")) {
	
	$stmt->execute();
		
	$stmt->bind_result($award_id, $award_name, $award_society, $year);
		
	while ($stmt->fetch()) {
		echo '<tr><td><a href="award?id=' . $award_id . '">' . $award_name . '</a></td>';
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

</div>
</body>
</html>

