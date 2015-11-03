<?php 
require './include/db.php';

$db_name = "gbarnett_website";
$table_name = "user_tracking";
$threshold = 1000;
function getTableSize($dbc, $db_name, $table_name, $threshold)
{
	$sql = "SELECT table_name AS 'Table', 
	ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size_MB' 
	FROM information_schema.TABLES 
	WHERE table_schema = '{$db_name}'
	AND table_name = '{$table_name}'";

	if ($result = $dbc->query($sql)) {
		$row = $result->fetch_row();
		if ($row['Size_MB'] >= $threshold) {
			truncateTable($dbc, $table_name);
			sendEmail(true, $row['Size_MB'], $table_name);
		} else{
			sendEmail(false, $row['Size_MB'], $table_name);
		}
	}	
}

function sendEmail($truncated, $table_size, $table_name)
{
	date_default_timezone_set('America/New_York	');
	$date = date('m/d/Y h:i:s a', time());
	$msg = "The Current time is: {$date}\n";

	if ($truncated) {
		$msg .= "{$table_name} has been truncated. The size of the table was {$table_size} MB";
	} else {
		$msg .= "{$table_name} is currently {$table_size} MB";

	}
	echo $msg;

}

function truncateTable($dbc, $table_name)
{
	$sql = "TRUNCATE TABLE {$table_name}";
	$dbc->query($sql);
}

getTableSize($dbc, $db_name, $table_name, $threshold);