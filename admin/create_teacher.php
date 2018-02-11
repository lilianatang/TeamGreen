
<?php
// Used to display message that user has been created.
session_start();
$_SESSION['message']="";

/* This class creates a new user, encrypts the password and inserts it into
 * the database.
 * Usage: $var = new Create_User()
 * Return: new connection to DB
*/
class Create_User 
{
	private $connection;

	// the construction is to initiate the connection in Create_User class
	// Author: Liliana Quyen Tang
	function __construct()
	{
		require_once '..\include_php\db_connect.php';

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
			$first_name = $this->connection->real_escape_string($_POST['first_name']);
			$last_name = $_POST['last_name']; 
			
			$username = $_POST['username'];
			$password = sha1($_POST['password']);
			$role_id = 4;
			$classroom_id = $_POST['classroom_id'];
			
			$sql = "INSERT INTO users (username, encrypted_password, role_id) VALUES"
			. "('$username','$password', '$role_id')";

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
			$sql = "SELECT user_id FROM users where username = '$username'";

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
			
			/* This section inserts the generated user into the  teacher table */
			$sql = "INSERT INTO teachers (first_name, last_name, user_id, classroom_id) VALUES"
			. "('$first_name', '$last_name', '$user_id', '$classroom_id')";

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
		
		<title>Teacher Account Creation</title>
		
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

			
		<div class="main-container"> <!-- Header will be inserted here! -->	</div>

		<h1>Teacher Account Creation</h1>
		
		<form action="../admin/create_teacher.php" method="post" autocomplete="off" style="text-align: center;" />

			<?= $_SESSION['message']  ?>
			<p>First Name: <input type="text" name="first_name" required /></p>
			<p>Last Name: <input type="text" name="last_name" required /></p>
			<p>User ID: <input type="text" name="username" required /></p>
			<p>Password: <input type="text" name="password" required /></p>

			<p>Classroom Color: <select name="classroom_id" id = "class-select">
				<!-- This will be populated dynamically -->
			</select>

			<p><input type="submit" value="Submit" name="Submit" /></p>
		
		</form>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.js"></script>
		
		<!-- Inserts the header -->
		<script type="text/javascript"> 
			jQuery(document).ready(function($){
				$("body .main-container").load("adminHeader.html");			
			});
		</script>
		
		<!-- Inserts the family usernames in the selection -->
		<script type="text/javascript" src = "../script/load-families.js"> </script>
		
		<!-- Inserts the classes in the selection -->
		<script type="text/javascript" src = "../script/load-classes.js"> </script>
	
	</body>
</html>



