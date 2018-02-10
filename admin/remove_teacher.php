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
			//$username = $this->connection->real_escape_string($_POST['username']);
			
			$user_id = $_POST['choose-teacher'];
			
			$sql = "Delete FROM users where user_id = '$user_id';
			Delete from teachers where user_id = $user_id;";
			
			if(mysqli_multi_query($this->connection, $sql))
			{
				$_SESSION['message'] = "User successfully deleted";
			}
			else
			{
				$_SESSION['message'] = 'Error: ' . mysqli_error($this->connection);
			}
			$this->connection->close();
		}		
	}
}
$use = new delete_User();
$use->delete_user();
?>
<html>
<head>
	
		<meta charset="UTF-8">
		
		<title>Teacher Account Removal</title>
		
		<!-- Link to External CSS for the html head Located in the css folder -->
		<link rel="stylesheet"  href="../style/headerStyle.css" type="text/css">

		<!-- Link to External CSS for the html body Located in the css folder -->
	
	</head>
	
	<!--
	main_div_pages - containers I used to move around the layout.
	- using google as a place holder for the hyperlink to our own pages for the <q> tages
	-->
	<body>

		<div class="main-container">
		</div>
		<h1>Teacher Deletion</h1>
		
		<form action="../admin/remove_teacher.php" method="post" autocomplete="off" style = "text-align: center;"/>
		
			<?= $_SESSION['message']  ?>
			<br><br>
			<label>Teacher to be deleted: </label>
			<select id = "choose-teacher" name ='choose-teacher'>
				
			</select>
			<br><br>
			<input type="submit" value="Submit" name="Submit" />
		
		</form>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.js"></script>

		<script type="text/javascript"> 
			jQuery(document).ready(function($){
				$("body .main-container").load("adminHeader.html");
			});
		</script>
		<script type="text/javascript" src = "../script/load-teachers.js"> </script>
</body>
</html>