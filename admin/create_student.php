
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
			$first_name = $this->connection->real_escape_string($_POST['first_name']);
			$last_name = $_POST['last_name']; 
			$grade = $_POST['grade'];
			$family_id = $_POST['family_id'];
			$classroom = $_POST['classroom_id'];
			
			$sql = "INSERT INTO students (first_name, last_name, family_id, grade, classroom_id) VALUES"
			. "('$first_name','$last_name', '$family_id', '$grade', '$classroom_id')";
			
			if(mysqli_query($this->connection, $sql))
			{
				$_SESSION['message'] = "New student successfully created";
			}
			else
			{
				die('Error: ' . mysqli_error($this->connection));
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
		
		<title>Student Creation</title>
		
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

<h1>Student Creation</h1>
<form action="create_student.php" method="post" autocomplete="off" />
<?= $_SESSION['message']  ?>
<p>First Name: <input type="text" name="first_name" required /></p>
<p>Last Name: <input type="text" name="last_name" required /></p>
<p>Grade: <input type="text" name="grade" required /></p>
<p>Family Name: <select name="family_id" size = "3">
	<option value = "mouse_family">Mouse Family</option>
	<option value = "dog_family">Dog Family</option>
	<option value = "cat_family">Cat Family</<option>
</select>

<p>Classroom Color: <select name="classroom_id" size = "3">
	<option value = 1>1. Pink</option>
	<option value = 2>2. Green</option>
	<option value = 3>3. Yellow</option>
	
</select>

<p><input type="submit" value="Submit" name="Submit" /></p>
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



