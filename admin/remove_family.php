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
			$family_id = $_POST['family_id'];
			
			$sql = 
				"DELETE FROM users WHERE user_id in (Select user_id from family where family_id = $family_id);
				DELETE FROM family WHERE family_id = $family_id;";
				
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
<!DOCTYPE HTML>

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
		<div class="main-container"> <!-- Header will be inserted here --> </div>
		
		<h1>Family Deletion</h1>
		
		<form action="../admin/remove_family.php" method="post" autocomplete="off" style = "text-align: center;" >
		
			<?= $_SESSION['message']  ?>
			<br>
			<label>Username to be deleted:</label> 
			
			<select id = "choose-family" select name="family_id">
				<!-- This will be populated dynamically -->
			</select>
			
			<br><br>
			<input type="submit" value="Submit" name="Submit" />
		
		</form>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
		
		<!-- Insert the header -->
		<script type="text/javascript"> 
		jQuery(document).ready(function($){
			$("body .main-container").load("adminHeader.html");
		});
		</script>
		
		<!-- Inserts the family usernames in the selection -->
		<script type="text/javascript" src = "../script/load-families.js"> </script>
		
	</body>

</html>