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
			$username = $this->connection->real_escape_string($_POST['username']);
			
			$sql = "Delete FROM users where username = '$username'";
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

<h1>User Deletion</h1>
<form action="delete-user-form.php" method="post" autocomplete="off" />
<?= $_SESSION['message']  ?>
<p>Username to be deleted: <input type="text" name="username" required /></p>
<input type="submit" value="Submit" name="Submit" />
</form>
