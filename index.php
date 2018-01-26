<?php 
session_start();
require("include_php/connection.php");

//check if the user pressed 'submit'

if (isset($_POST)['username']) and isset($_POST)['password'])) {
	//assign posted values to variables
	$username = $_POST['username'];
	$password = $_POST['password'];

	//check if values exist in the database
	$query = "SELECT * FROM `users` WHERE username = '$username' and password = `password`";

	$result = mysqli_query($connection, $query) or die(mysql_error($connection));
	$count = mysqli_num_rows($result);


	if ($count == 1){
		$_SESSION['username'] = $username;
	} else {
		$msg = "Invalid Login Credentials";
	}
}

if (isset($_SESSION['username'])){
	$username = $_SESSION['username'];
	echo "Hi . $username . ";
	echo "This is Members Area";
	echo "<a href='logout.php'>logout</a>";

}
else {

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
		<img id = "logo" src="media/portal-logo.png" alt="Caraway Facilitation Portal">
	
		<!-- Login Form -->
		<form id = "login-form" method="post" action="">
		
			
			<label id = "id-label" > User ID: </label>
			<br>

			<input id = "id-input" type = "text" name="username"> </input>
			<br>
			<label id = "password-label"> Password: </label>
			<br>
			<input id = "password-input" type = "password" name="password"> </input>
			<br>
			<input type="submit" value="Submit"> </input>

			<br>
		</form>
		
	</body>
</html>
<?php } ?>