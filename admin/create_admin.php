<?php
// Used to display message that user has been created.
session_start();
$_SESSION['message']="";

/* This class creates a new user, encrypts the password and inserts it into
 * the database.
 * Usage: $var = new Create_User()
 * Return: new connection to DB
 * NOTE: UNSURE IF THIS NEEDS TO BE ITS OWN CLASS RIGHT NOW, will talk to group memebers
 * accordingly.
 * NOTE: THE CONNECTION TO db_connect.php is hardcoded based on Joe;s filepath, need to make it
 * generalized for all users...
*/
class Create_User 
{
	private $connection;

	// the construction is to initiate the connection in Create_User class
	// Author: Liliana Quyen Tang
	function __construct()
	{
		require_once 'C:\wamp64\www\TeamGreen\include_php\db_connect.php';

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
			$username = $this->connection->real_escape_string($_POST['username']);
			$password = sha1($_POST['password']); 
			$roleID = 1;
			
			$sql = "INSERT INTO users (username, encrypted_password, role_id) VALUES"
			. "('$username','$password', '$roleID')";

			$result = mysqli_query($this->connection, $sql);
			if($result)
			{
				$_SESSION['message'] = "New admin successfully created";
			}
			else
			{
				die('Error: ' . mysqli_error($mysqli));
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
		
		<title>Admin Account Creation</title>
		
		<!-- Link to External CSS for the html head Located in the css folder -->
		<link rel="stylesheet"  href="../style/headerStyle.css" type="text/css">

		<!-- Link to External CSS for the html body Located in the css folder -->

		<!-- Link to Google font Aclonica. -->
		<link href='https://fonts.googleapis.com/css?family=Aclonica' rel='stylesheet'>

	
	</head>
	
	<!--
	main_div_pages - containers i used to move around the layout.
	- using google as a place holder for the hyperlink to our own pages for the <q> tages
	-->
	<body>

			<div class="main-container">
			</div>
<h1>Family Account Creation</h1>
<form action="../admin/create_admin.php" method="post" autocomplete="off" />
<?= $_SESSION['message']  ?>
<p>Family User ID: <input type="text" name="username" required /></p>
<p>Family Password: <input type="text" name="password" required /></p>
<input type="submit" value="Submit" name="Submit" />
</form>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
		<script type="text/javascript"> 
		jQuery(document).ready(function($){
			$("body .main-container").load("adminHeader.html");
			console.log("000000");
		});
		</script>
</body>
</html>

