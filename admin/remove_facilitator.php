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
		require_once 'C:\wamp64\www\TeamGreen\include_php\db_connect.php';

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
			$first_name = $this->connection->real_escape_string($_POST['first_name']);
			$last_name = $_POST['last_name'];
			
			$sql = "Delete FROM facilitator where first_name = '$first_name' and last_name = '$last_name'";
			if(mysqli_query($this->connection, $sql))
			{
				$_SESSION['message'] = "User successfully deleted";
			}
			else
			{
				die('Error: ' . mysqli_error($mysqli));
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
		
		<title>Facilitator Account Removal</title>
		
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
	<h1>Facilitator Account Deletion</h1>
	<form action="../admin/remove_facilitator.php" method="post" autocomplete="off" />
	<?= $_SESSION['message']  ?>
	<p>Facilitaor's first name to be deleted: <input type="text" name="first_name" required /></p>
	<p>Facilitator's last name to be deleted: <input type="text" name="last_name" required /></p>
	
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