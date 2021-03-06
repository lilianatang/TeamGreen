<?php
// Used to display message that user has been created.
session_start();
$_SESSION['message']="";

/* This class creates a new user, encrypts the password and inserts it into
 * the database.
 * Usage: $var = new Create_User()
 * Return: new connection to DB
 * NOTE: UNSURE IF THIS NEEDS TO BE ITS OWN CLASS RIGHT NOW, will talk to group members
 * accordingly.
 * NOTE: THE CONNECTION TO db_connect.php is hardcoded based on Joe's filepath, need to make it
 * generalized for all users...
*/
class Create_User 
{
	private $connection;

	// the construction is to initiate the connection in Create_User class
	// Author: Liliana Quyen Tang
	function __construct()
	{
		require_once '../include_php/db_connect.php';

		$db = new DB_Connect();
		$this->connection = $db->connect();
	}
	/* creates a user from the data entered into the form
	* Usage: $user->create_user();
	* Return: None
	*/
	function create_user()
	{
		if ($_SERVER['REQUEST_METHOD'] ==  "POST")
		{
			
			/* This section inserts the new user into the user table */
			$username = $this->connection->real_escape_string($_POST['username']);
			$password = sha1($_POST['password']); 
			$roleID = 2;
			
			$sql = "INSERT INTO users (username, encrypted_password, role_id) VALUES"
			. "('$username','$password', '$roleID')";

			$result = mysqli_query($this->connection, $sql);
			if($result)
			{
				$_SESSION['message'] = "New user successfully created";
			}
			else
			{
				$_SESSION['message'] = 'Error: ' . mysqli_error($this->connection);
				return;
			}
			
			/* This section retrieves the generated user_id from the previous query */
			$sql = "SELECT user_id FROM users where username = '$username' and encrypted_password = '$password' and role_id = 2";

			$result = mysqli_query($this->connection, $sql);
			
			$row = mysqli_fetch_assoc($result);
			$user_id = $row['user_id'];
				
			if($result)
			{
				$_SESSION['message'] = "New user successfully created";
			}
			else
			{
				$_SESSION['message'] = 'Error: ' . mysqli_error($this->connection);
				return;
			}
			
			/* This section inserts the generated user into the family table */
			$sql = "INSERT INTO family (user_id) VALUES"
			. " ($user_id)";

			$result = mysqli_query($this->connection, $sql);
			
			if($result)
			{
				$_SESSION['message'] = "New user successfully created";
			}
			else
			{
				$_SESSION['message'] = 'Error: ' . mysqli_error($this->connection);
				return;
			}
			
			$this->connection->close();
		}		
	}
}
$use = new Create_User();
$use->create_user();
?>
<html>
<head>
	
		<meta charset="UTF-8">
		
		<title>Family Account Creation</title>
		
		<!-- Link to External CSS for the html head Located in the css folder -->
		<link rel="stylesheet"  href="../style/headerStyle.css" type="text/css">

		<!-- Link to External CSS for the html body Located in the css folder -->

		<!-- Link to Google font Aclonica. -->
		<link href='https://fonts.googleapis.com/css?family=Aclonica' rel='stylesheet'>

	
	</head>
	
	<!--
	main_div_pages - containers I used to move around the layout.
	- using google as a place holder for the hyperlink to our own pages for the <q> tages
	-->
	<body>

		<div class="main-container"> <!-- Header is inserted here! --> 	</div>
		
		<h1>Family Account Creation</h1>
		
		<form action="../admin/create_family.php" method="post" autocomplete="off" style="text-align: center;" />

			<?= $_SESSION['message']  ?>
			<p>Family User ID: <input type="text" name="username" required /></p>
			<p>Family Password: <input type="text" name="password" required /></p>
			<input type="submit" value="Submit" name="Submit" />
	
		</form>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
		
		<!-- This section inserts the header -->
		<script type="text/javascript"> 
			jQuery(document).ready(function($){
				$("body .main-container").load("adminHeader.html");
			});
		</script>
</body>
</html>

