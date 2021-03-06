
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
			$grade = $_POST['grade'];
			$family_id = $_POST['family_id'];
			$classroom_id = $_POST['classroom_id'];
			
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

			
		<div class="main-container"> <!-- Header will be inserted here! -->	</div>

		<h1>Student Creation</h1>
		
		<form action="../admin/create_student.php" method="post" autocomplete="off" style="text-align: center;" />

			<?= $_SESSION['message']  ?>
			<p>First Name: <input type="text" name="first_name" required /></p>
			<p>Last Name: <input type="text" name="last_name" required /></p>
			<p>Grade: <input type="text" name="grade" required /></p>
			<p>Family Username: 
			
			<select id = "choose-family" select name="family_id">
				<!-- This will be populated dynamically -->
			</select>

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



