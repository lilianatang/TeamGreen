<?php 
session_start();
$_SESSION['message'] = "";

class Check_User {
	private $connection;


	function __construct() {
		require_once 'C:\wamp64\www\TeamGreen\include_php\db_connect.php';

		$db = new DB_Connect();
		$this->connection = $db->connect();

	}


	/*check if the credentials are correct */
	function check_credentials() {
		if ((isset($_POST["username"])) and (isset($_POST["password"]))) {
	
	//assign posted values to variables
			$username = $_POST['username'];
			$password = sha1($_POST['password']);

	//check if values exist in the database
			$query = "SELECT * FROM users WHERE username = '$username' AND encrypted_password = '$password'";

			$result = mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
			$count = mysqli_num_rows($result);

			if ($count == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['message'] = "Logged in sucessfully";
				$userinfo = mysqli_fetch_assoc($result);
				//$_SESSION['role_id'] = $row['role_id'];
				//$count = mysqli_num_rows($result);
				$role = $userinfo['role_id'];
				echo "LOOK HJEREEWENFOIWENFWE " . $role;
				//admin page
				
				if ($role['role_id'] == 1) {
					header("location: admin/adminFamilyStatistics.html");
				}

				//family page
				else if ($role == 2) {
					$_SESSION['role_id'] = $row['role_id'];
					$query_family = "INSERT INTO family(user_id)
									SELECT users.user_id from users WHERE username = '$username'";

					$result_family = mysqli_query($this->connection, $query) or die (mysqli_error($this->connection));

					header("location: family/family.php");
				}

				else if ($role == 3) {
					$_SESSION['role_id'] = $role;
					header("location: board_member/board_member.php");
				}

				else {
					$_SESSION['role_id'] = $role;
					header("location: teacher/teacher.php");
				}

			} else {
				$_SESSION['message']= "Invalid Login Credentials";
			}


		}
		
		$this->connection->close();
	}
}
$credential = new Check_User();
$credential->check_credentials();
?>

	<html>

	<head>
	
		<meta charset="UTF-8">
		
		<title>Caraway Facilitation Portal Login </title>
		
		<!-- Link to External Style Sheet Located in the css folder -->
		<link rel="stylesheet"  href="style/login-page.css" type="text/css">
	
	</head>
	
	<body>
	
		<!-- Caraway Logo Image -->
		<img id = "logo" src="schedule-template/media/portal-logo.png" alt="Caraway Facilitation Portal">
	
		<!-- Login Form -->

		<form id = "login-form" action="index.php" method="post" autocomplete="off">

		<form id = "login-form" action="C:\wamp64\www\TeamGreen\index.php" method="post" autocomplete="off">

		<?= $_SESSION['message']; ?>
			
			<label id = "id-label" > User ID: </label>
			<br>

			<input id = "id-input" type = "text" name="username" required> </input>
			<br>
			<label id = "password-label"> Password: </label>
			<br>
			<input id = "password-input" type = "password" name="password" required> </input>
			<br>
			<input type="submit" value="Submit" name="Submit"> </input>

			<br>
		</form>
		
	</body>
</html>
