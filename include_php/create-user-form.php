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
			if(mysqli_query($this->connection, $sql))
			{
				$_SESSION['message'] = "New user successfully created";
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

<h1>Family Account Creation</h1>
<form action="create-user-form.php" method="post" autocomplete="off" />
<?= $_SESSION['message']  ?>
<p>Create Username: <input type="text" name="username" required /></p>
<p>Create Password: <input type="text" name="password" required /></p>
<input type="submit" value="Submit" name="Submit" />
</form>
