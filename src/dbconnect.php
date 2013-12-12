<?php
	class DbUtil{
		// Login information for data host and schema (non-login database)
		private static $DATA_HOST = "";
		private static $DATA_SCHEMA = "";
		private static $DATA_READ_ONLY_USER = "";
		private static $DATA_READ_ONLY_PASSWORD = "";
		private static $DATA_READ_WRITE_USER = "";
		private static $DATA_READ_WRITE_PASSWORD = "";
		
		// Login information for database with login information
		// (the same as above if users table is in same db/schema as actors, directors, etc.)
		private static $LOGIN_HOST = "";
		private static $LOGIN_SCHEMA = "";
		private static $LOGIN_READ_ONLY_USER = "";
		private static $LOGIN_READ_ONLY_PASSWORD = "";
		private static $LOGIN_READ_WRITE_USER = "";
		private static $LOGIN_READ_WRITE_PASSWORD = "";
		
		private static function loginConnectionHelper($host, $user, $pass, $schema) {
			$db = new mysqli(DbUtil::$host, DbUtil::$user, DbUtil::$pass, DbUtil::$schema);
			if($db->connect_errno) {
				echo("Can't connect to MySQL Server. Error code: " . mysqli_connect_error());
				$db->close();
				exit();
			}
			return $db;
		}
		
		public static function openDataReadOnlyConnection() {
			return loginConnectionHelper($DATA_HOST, $DATA_READ_ONLY_USER, $DATA_READ_ONLY_PASSWORD, $DATA_SCHEMA);
		}
		
		public static function openDataReadWriteConnection() {
			return loginConnectionHelper($DATA_HOST, $DATA_READ_WRITE_USER, $DATA_READ_WRITE_PASSWORD, $DATA_SCHEMA);
		}
		
		public static function openLoginReadOnlyConnection() {
			return loginConnectionHelper($LOGIN_HOST, $LOGIN_READ_ONLY_USER, $LOGIN_READ_ONLY_PASSWORD, $LOGIN_SCHEMA);
		}
		
		public static function openLoginReadWriteConnection() {
			return loginConnectionHelper($LOGIN_HOST, $LOGIN_READ_WRITE_USER, $LOGIN_READ_WRITE_PASSWORD, $LOGIN_SCHEMA);
		}
	}
	
	
?>

