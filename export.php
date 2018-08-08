<?php
require_once 'config.php';
// /***** EDIT BELOW LINES *****/
// $DB_Server = "localhost"; // MySQL Server
// $DB_Username = "root"; // MySQL Username
// $DB_Password = "root"; // MySQL Password
// $DB_DBName = "metrics_air_quality"; // MySQL Database Name
// $DB_TBLName = "madrid_2001"; // MySQL Table Name
// $mysqli = new mysqli('localhost', 'root', 'root', 'metrics_air_quality');

$xls_filename = 'export_'.date('Y-m-d').'.xls'; // Define Excel (.xls) file name

if ($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') '
	. $mysqli->connect_error);
}
   
/***** DO NOT EDIT BELOW LINES *****/
// Create MySQL connection
$sql = "Select BEN,CO,EBE,MXY,NMHC,NO_2,station from $DB_TBLName LIMIT 100000";

   
// Header info settings
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");
   
/***** Start of Formatting for Excel *****/
// Define separator (defines columns in excel &amp; tabs in word)
$sep = "\t"; // tabbed character


if( $result = $mysqli->query($sql, MYSQLI_USE_RESULT) ) {

	// Get field information for all fields
	$fieldinfo = $result->fetch_fields();
	foreach($fieldinfo as $val) {
		echo $val->name."\t";
	}
	print("\n");


	// fetch object array
	while ( $row = $result->fetch_row() ) {
		$schema_insert = "";
		for($j=0; $j<$result->field_count; $j++)
	    {
	      if(!isset($row[$j])) {
	        $schema_insert .= "NULL".$sep;
	      }
	      elseif ($row[$j] != "") {
	        $schema_insert .= "$row[$j]".$sep;
	      }
	      else {
	        $schema_insert .= "".$sep;
	      }
	    }
	    $schema_insert = str_replace($sep."$", "", $schema_insert);
	    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
	    $schema_insert .= "\t";
	    print(trim($schema_insert));
	    print "\n";
	}

	// free result set 
	$result->close();
}
$mysqli->close();
  
?>