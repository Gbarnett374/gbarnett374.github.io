<?php 
require './include/db.php';

/**
 * Grabs the user info from $_SERVER and inserts it into a table. 
 */
function logUser()
{
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	$sql = "INSERT INTO user_tracking
	(ip_address, user_agent)
	VALUES({$ip_address},{$user_agent})";
}

loguser();