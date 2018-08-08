<?php
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR);
set_time_limit ( 60 ) ;
require_once 'config.php';

$DB_TBLName = "madrid_2001"; // MySQL Table Name
$DB_PROGRESS_TBLName = "progress"; // Progress Table
$xls_filename = 'export_'.date('Y-m-d').'.xls'; // Define Excel (.xls) file name

if ($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') '
	. $mysqli->connect_error);
}

if($_POST['timestmp']	!=	'' ) {
	$timestamp = $_POST['timestmp'];

	$del_sql = "DELETE FROM $DB_PROGRESS_TBLName WHERE timestmp ={$timestamp}";
	$mysqli->query($del_sql);

	$insert_sql = "INSERT INTO $DB_PROGRESS_TBLName (progress,timestmp) VALUES (0,{$timestamp})";
	$mysqli->query($insert_sql);
	echo $mysqli->insert_id;
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


if( $result = $mysqli->query($sql) ) {

	// Get field information for all fields
	$fieldinfo = $result->fetch_fields();
	foreach($fieldinfo as $val) {
		echo $val->name."\t";
	}
	print("\n");

	$cnt = 0;
	// fetch object array
	while ( $row = $result->fetch_row() ) {
		$schema_insert = "";
		$cnt++;

		if ( $cnt % 10000 == 0 && $cnt <= 100000 ){
			$update_sql = "UPDATE $DB_PROGRESS_TBLName  SET progress=progress + 10 WHERE timestmp ={$timestamp} ";
			$update = $mysqli->query($update_sql);
			sleep($delay);
		}
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