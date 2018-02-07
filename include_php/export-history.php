<form action="export-history.php" method="post">
  <input type="submit" value="Export History!">
</form>

<?php
// Used to display message that user has been created.
session_start();
$_SESSION['message']="";

//PLEASE NOTE: When looking at the excel export, both date columns might appear as #####, this is ok
// it is a default of excel when data in a row is larger than the cell, just increase the cell size 
// and the ###### should disappear automatically for all the rows. 
class Export_History
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
	
	function export()
	{
		if ($_SERVER['REQUEST_METHOD'] ==  "POST")
		{
			 date_default_timezone_set('America/Edmonton');
			 $date = "History-" . date("Y-M-d_h.i.sa"); 
			 $file_ending = "xls";
			 $sep = "\t";
			 
			 $query = "Select * from history order by family_id";
			 $result = mysqli_query($this->connection,$query);
			 
			 //cleans buffer, used to prevent html code from being exported to excel file
			 ob_end_clean();
			 
			 //Sets column names @ top of excel file
			 $columnHeader = '';
			 $columnHeader = "Family_ID" . "\t" . "Hours Completed" . "\t" . "Hours Needed" . "\t" . "Date Started" . "\t" . "Date Ended" . "\t";
			 
			 $setData = '';
			 
			 while($rec = mysqli_fetch_row($result))
			 {
				 $rowData = '';
				 foreach($rec as $value)
				 {
					 $value = '"' . $value . '"' . $sep;  
					 $rowData .= $value;  
				 }   
					 $setData .= trim($rowData) . "\n"; 
			 } 
			 header("Content-type: application/octet-stream");  
			 header("Content-Disposition: attachment; filename=$date.$file_ending");  
			 header("Pragma: no-cache");  
			 header("Expires: 0");  
			 
			 echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
		}
		$this->connection->close();
	}
}	
$use = new Export_History();
$use->export();
?>
