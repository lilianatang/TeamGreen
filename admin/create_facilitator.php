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
			$phone_number= $_POST['phone_number'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$family_id 	= $_POST['family_id'];	
			$sql = "INSERT INTO facilitator(family_id, first_name, last_name, email, address, phone_number) VALUES"
			. "($family_id, '$first_name','$last_name', '$phone_number', '$email', '$address')";
			
			$result = mysqli_query($this->connection, $sql);
			
			if($result)
			{
				$_SESSION['message'] = "New facilitator successfully created";
			}
			else
			{
				die('Error:');
			}

			$this->connection->close();
		}		
	}
	
}
$use = new Create_User();
$use->create_user();
?>
<!DOCTYPE html>
<html>
<head>
	
		<meta charset="UTF-8">
		
		<title>Facilitator Creation</title>
		
		<!-- Link to External CSS for the html head Located in the css folder -->
		<link rel="stylesheet"  href="../style/headerStyle.css" type="text/css">

		<!-- Link to External CSS for the html body Located in the css folder -->

		<!-- Link to Google font Aclonica. -->
		<link href='https://fonts.googleapis.com/css?family=Aclonica' rel='stylesheet'>

	
	</head>
	
	<body>

		<div class="main-container"> <!-- Header will be inserted here! --> </div>
		
		<h1>Facilitator Creation</h1>
		
		<form action="../admin/create_facilitator.php" method="post" autocomplete="off" style="text-align: center;">
		
		<?= $_SESSION['message']  ?>
		
		<p>First Name: <input type="text" name="first_name" required></p>
		<p>Last Name: <input type="text" name="last_name" required></p>
		<p>Phone Number: <input type="text" name="phone_number" required></p>
		<p>Email: <input type="text" name="email" required></p>
		<p>Address: <input type="text" name="address" required></p>
		
		<label>Family Username: </label> 
		<select id = "choose-family" name = "family_id" >
			<!-- This will be populated dynamically -->
		</select>
		
		<br><br>
		<input type="submit" value="Submit" name="Submit">
		
		</form>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.js"></script>
		<script type="text/javascript"> 
			jQuery(document).ready(function($){
				
				// This code loads the header 
				$("body .main-container").load("adminHeader.html");
				
				// This code populates the family username selection 
				$.post("../include_php/get-families.php", function(data){
					
					// Organize data from the query 
					var family_info = data.split(",");
					family_info.pop();
					
					// Go through each piece of data and create a selection out of it
					for (var i = 0; i < family_info.length; i ++){
						
						// Split data so [0] = family id and [1] = family username
						var family_specific = family_info[i].split(" ");
						
						// Create selection
						var new_option = $("<option value = '" + family_specific[0] + 
						"'>" + family_specific[1] + " </option>");
						
						// Add selection
						$("#choose-family").append(new_option);
					}
					
				});
				
			});
		</script>
	</body>
</html>

