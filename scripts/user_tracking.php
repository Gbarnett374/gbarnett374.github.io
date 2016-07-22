<?php 
require 'db.php';

/**
 * Grabs the user info from $_SERVER and inserts it into a table. 
 * @param $dbc - The db connection object.
 */
function logUser($dbc)
{
    $ipAddress = $dbc->real_escape_string($_SERVER['REMOTE_ADDR']);
    $userAgent = $dbc->real_escape_string($_SERVER['HTTP_USER_AGENT']);

    $sql = "INSERT INTO user_tracking
    (ip_address, user_agent)
    VALUES('{$ipAddress}','{$userAgent}')";

    $dbc->query($sql);
}

logUser($dbc);

//Close Connection. 
$dbc->close();
