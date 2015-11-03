<?php 
require './include/db.php';
/**
 * Gets the size of the table from the information schema & determines if threshold is met. 
 * @param  [object] $dbc        - the mysqli db connection object. 
 * @param  [string] $db_name    - the name of the database where the table we want to check resides.
 * @param  [string] $table_name - the table we want to check the size of. 
 * @param  [int]    $threshold  - The number of MB we use to determine to truncate the table. 
 */
function getTableSize($dbc, $db_name, $table_name, $threshold)
{
    $sql = "SELECT table_name AS 'Table', 
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size_MB' 
    FROM information_schema.TABLES 
    WHERE table_schema = '{$db_name}'
    AND table_name = '{$table_name}'";

    if ($result = $dbc->query($sql)) {
        $row = $result->fetch_assoc();
        if ($row['Size_MB'] >= $threshold) {
            truncateTable($dbc, $table_name);
            sendEmail(true, $row['Size_MB'], $table_name);
        } else{
            sendEmail(false, $row['Size_MB'], $table_name);
        }
    }   
}

/**
 * Sends an email to the administrator when this script is run. 
 * @param  [bool]          $truncated  - If the table should be truncated or not. 
 * @param  [int]           $table_size - The size of the table in MB. 
 * @param  [string]        $table_name - The name of the table to truncate. 
 * @param  [bool]|[string] $error      - Set to false by default, if there is an error then it will contain the error message. 
 */
function sendEmail($truncated, $table_size, $table_name, $error = false)
{
    date_default_timezone_set('America/New_York');
    $date = date('m/d/Y h:i:s a', time());
    $msg = "The Current time is: {$date}\n";

    if ($truncated) {
        $msg .= "{$table_name} has been truncated. The size of the table was {$table_size} MB";
    } else if ($error) {
        $msg .= $error;
    } else {
        $msg .= "{$table_name} is currently {$table_size} MB";
    }

    echo $msg;
    $to = "bahh374@aol.com";
    $subject = "Database Cleanup Script Executed";
    $headers = "From: Bahhnet.Synology.Me" . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    //Send the email. Need to setup Postfix. 
    // mail($to, $subject, $msg, $headers);
}
/**
 * Truncates the specified table. 
 * @param  [object] $dbc        - The MySqli DB Connection Object.
 * @param  [string] $table_name - The name of the table to be truncated. 
 */
function truncateTable($dbc, $table_name)
{
    $sql = "TRUNCATE TABLE {$table_name}";
    if (!$dbc->query($sql)) {
        throw new Exception("Error truncating the table {$table_name}");
    }
}

/**********************************EXECUTE SCRIPT****************************************************/
$db_name = "gbarnett_website";
$table_name = "user_tracking";

//Threshold is in MB. 
$threshold = 1000;

try{
    getTableSize($dbc, $db_name, $table_name, $threshold);
} catch (Exception $e) {
    $error = $e->getMessage();
    sendEmail($dbc, $db_name, $table_name, $error);
}