<?php
session_start();
//If already logged in, redirect
if(isset($_SESSION['valid']) && $_SESSION['valid']){
	header("Location: actors");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$username = $_POST['txtUsername'];
	$password = $_POST['pswPassword'];
		
	require_once("./dbconnect.php");
		
	$db_connection = DbUtil::openLoginReadOnlyConnection();
	
	$stmt = $db_connection->stmt_init();	
		
	if($stmt->prepare("SELECT user_id FROM users WHERE username=? AND password=SHA1(?)")) {
		$stmt->bind_param('ss', $username, $password);
		$stmt->execute();
			
		$stmt->bind_result($user_id);			
		if($stmt->fetch()){
			//Successful login!
			$stmt->close();
			$db_connection->close();
			
			$db_connection = DbUtil::openLoginReadWriteConnection();
			
			$stmt = $db_connection->stmt_init();
			
			if($stmt->prepare("CALL Update_Last_Login(?)")) {
				$stmt->bind_param('i', $user_id);
				$stmt->execute();		
			}
			
			session_regenerate_id();
			$_SESSION['valid'] = 1;
			$_SESSION['user_id'] = $user_id;
			$_SESSION['username'] = $username;
			
			//redirect to logged in page
			header("Location: actors");
			exit;
			
		}				
		
		$message_type = "fail";
		$message = "Unsuccessful login. Please try again.";
		
		$stmt->close();
	}

	
}

?>


<html>
<head>
<title>Login</title>
<link href="media/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $login_nav = true; include './menu.php'; ?>
<div class="container">
<h1>Admin Login</h1>

<form action="login" method="post">
  <b>Please enter your account information</b><br><br>
  
  Username: <input type="text" name="txtUsername" /><br><br>
  Password: <input type="password" name="pswPassword" /><br><br>  
  
    <input type="submit" value="Login">
	</form>  
	</div>
  </body>
</html>