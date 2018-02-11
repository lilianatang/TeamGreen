<?php
// Used to display message that user has been deleted.
session_start();
$_SESSION['message']="";

/* This class deletes a user from the DB
 * the database.
 * Usage: $var = new delete_User()
 * Return: new connection to DB
*/
class delete_User 
{
	private $connection;

	// the construction is to initiate the connection in delete_User class
	// Author: Liliana Quyen Tang
	function __construct()
	{
		require_once '..\include_php\db_connect.php';

		$db = new DB_Connect();
		$this->connection = $db->connect();
	}
	/* deletes a user from the database
	* Usage: $user->delete_user();
	* Return: None
	*/
	function delete_user()
	{
		if ($_SERVER['REQUEST_METHOD'] ==  "POST")
		{
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$family_id = $_POST['family_id'];
			
			$sql = "Delete FROM students where family_id = $family_id and first_name = '$first_name' and last_name = '$last_name'";
			if(mysqli_query($this->connection, $sql))
			{
				$_SESSION['message'] = "User successfully deleted";
			}
			else
			{
				die('Error: ' . mysqli_error($this->connection));
			}
			$this->connection->close();
		}		
	}
}
$use = new delete_User();
$use->delete_user();
?>
<head>
	
		<meta charset="UTF-8">
		
		<title>Student Account Removal</title>
		
		<!-- Link to External CSS for the html head Located in the css folder -->
		<link rel="stylesheet"  href="../style/headerStyle.css" type="text/css">

		<!-- Link to External CSS for the html body Located in the css folder -->

	
	</head>
	
	<!--
	main_div_pages - containers i used to move around the layout.
	- using google as a place holder for the hyperlink to our own pages for the <q> tages
	-->
	<body>
	
		<div class="main-container"> <!-- Header will be inserted here -->	</div>
		
		<h1>Student Account Deletion</h1>
		
		<form action="../admin/remove_student.php" method="post" autocomplete="off" style = "text-align: center;"/>
		
			<?= $_SESSION['message']  ?>
			<br>
			
			<p>Family username:
			<select id = "choose-family" name = "family_id" >
				<!-- This will be populated dynamically -->
			</select> </p>
			
			<p>Student's first name to be deleted: <input type="text" name="first_name" required /></p>
			<p>Student's last name to be deleted: <input type="text" name="last_name" required /></p>
			
			<input type="submit" value="Submit" name="Submit" />
		</form>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
			<script type="text/javascript"> 
			jQuery(document).ready(function($){
				$("body .main-container").load("adminHeader.html");
			});
		</script>
		
		<!-- This script populates the choose-family selector  -->
		<script type="text/javascript" src = "../script/load-families.js"> </script>
		
	</body>
</html>