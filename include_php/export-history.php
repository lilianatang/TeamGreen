<!DOCTYPE html>
<html>

	<head>
	
		<meta charset="UTF-8">
		
		<title>Caraway Export Facilitation History </title>
		
		<!-- Link to External CSS for the html head Located in the css folder -->
		<link rel="stylesheet"  href="../style/headerStyle.css" type="text/css">
	
	</head>
	<body>
		
		<div class="main-container">
			<!---java script inserts header into here -->
		</div>
	
		<h1>Export Facilitation History </h1>
	
		<!-- Download button -->
		<form action="export-history.php" method="post" style = "text-align: center;">
			<input type="submit" value="Download Facilitation History">
		</form>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.js"></script>
		
		<!-- Inserts the header -->
		<script type="text/javascript"> 
			jQuery(document).ready(function($){
				$("body .main-container").load("../admin/adminHeader.html");			
			});
		</script>
		
	</body>
</html>


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

