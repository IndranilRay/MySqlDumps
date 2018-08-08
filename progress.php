<?php
require_once 'config.php';

$DB_TBLName = "progress"; // MySQL Table Name

if( $_POST['progress'] == 'start' && $_POST['timestmp'] != '' ){
	$timestmp = $_POST['timestmp'];
	$query = "SELECT progress FROM $DB_TBLName where timestmp = {$timestmp}";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	echo $row[0];
	//echo "hi";
}

// $tms = 1533762083;
// $query = "SELECT progress FROM $DB_TBLName where timestmp = {$tms}";
// $result = $mysqli->query($query);
// $row = $result->fetch_row();
// echo '<pre>';
// print_r($row[0]);
// exit;