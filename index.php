<?php 
session_start();
$_SESSION['message'] = "";

class Check_User {
	private $connection;


	function __construct() {
		require_once 'include_php\db_connect.php';

		$db = new DB_Connect();
		$this->connection = $db->connect();

	}


	/*check if the credentials are correct */
	function check_credentials() {
		if ((isset($_POST["username"])) and (isset($_POST["password"]))) {
	
	//assign posted values to variables
			$username = $_POST['username'];
			$password = $_POST['password'];

	//check if values exist in the database
			$query = "SELECT * FROM users WHERE username = '$username' AND encrypted_password = '$password'";

			$result = mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
			$count = mysqli_num_rows($result);

			if ($count == 1){
				$_SESSION['username'] = $username;
				$_SESSION['message'] = "Logged in sucessfully";
			} else {
				$_SESSION['message']= "Invalid Login Credentials";
			}
		}

		if (isset($_SESSION['username'])) {
			$username = $_SESSION['username'];
			echo "Hi . $username . ";
			echo "This is Members Area";
			echo "<a href='logout.php'>logout</a>";
			#header('location: include_php/create-user-form.php');
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
		<img id = "logo" src="images\portal-logo.png" alt="Caraway Facilitation Portal">
	
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
