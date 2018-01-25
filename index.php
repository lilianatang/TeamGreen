<?php 
ob_start();
session_start();
include("include_php/connection.php");
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
		<?php
		if ($_SERVER["REQUEST_METHOD"] = "POST") {
			$myusername = mysqli_real_escape_string($connection, $_POST['username']);
			$mypassword1 = mysqli_real_escape_string($connection, $_POST['password']);
			$mypassword = SHA1($mypassword1);

			$sql_queries = "SELECT * FROM users WHERE username = '$myusername' and encrypted_password = '$mypassword'";
			$result = mysqli_query($connection, $sql_queries);
			$row = mysqli_fetch_array($result);
			$_SESSION['user_id'] = $row['user_id'];
			$_SESSION['role_id'] = $row['role_id'];

			$count = msqli_num_rows($result);
			if ($count == 1) {
				if ($row['role_id'] == 1) {
					header("location: admin_page.php");
				}

				else if ($row['role_id'] == 2) {
					header("location: family_page.php");
				}

				else if ($row['role_id'] == 3) {
					header("location: teacher_page.php");
				}
		
				else if ($row['role_id'] == 4) {
					header("location: punchin_page.php");
				}

				else {
					header("location: board_page.php");
				}
			}

			else {
				$error = "Either your username or password is incorrect. Please try again!";

			}
		?>				
 
			
			
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
