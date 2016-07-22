<?php
require '../include/db.php';
/**
 * Gets the size of the table from the information schema & determines if threshold is met.
 * @param  [object] $dbc        - the mysqli db connection object.
 * @param  [string] $dbName    - the name of the database where the table we want to check resides.
 * @param  [string] $tableName - the table we want to check the size of.
 * @param  [int]    $threshold  - The number of MB we use to determine to truncate the table.
 */
function getTableSize($dbc, $dbName, $tableName, $threshold)
{
    $sql = "SELECT table_name AS 'Table', 
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size_MB' 
    FROM information_schema.TABLES 
    WHERE table_schema = '{$dbName}'
    AND table_name = '{$tableName}'";

    if ($result = $dbc->query($sql)) {
        $row = $result->fetch_assoc();
        if ($row['Size_MB'] >= $threshold) {
            truncateTable($dbc, $tableName);
            sendEmail(true, $row['Size_MB'], $tableName);
        } else {
            sendEmail(false, $row['Size_MB'], $tableName);
        }
    }
}

/**
 * Sends an email to the administrator when this script is run.
 * @param  [bool]          $truncated  - If the table should be truncated or not.
 * @param  [int]           $tableSize - The size of the table in MB.
 * @param  [string]        $tableName - The name of the table to truncate.
 * @param  [bool]|[string] $error - Set to false by default, if there is an error then it will contain the error message.
 */
function sendEmail($truncated, $tableSize, $tableName, $error = false)
{
    date_default_timezone_set('America/New_York');
    $date = date('m/d/Y h:i:s a', time());
    $msg = "The Current time is: {$date}\n";

    if ($truncated) {
        $msg .= "{$tableName} has been truncated. The size of the table was {$tableSize} MB";
    } elseif ($error) {
        $msg .= $error;
    } else {
        $msg .= "{$tableName} is currently {$tableSize} MB";
    }

    $sendTo = "bahh374@aol.com";
    $subject = "Database Cleanup Script Executed";
    $headers = "From: Gbarnett.net" . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    //Send the email.
    mail($sendTo, $subject, $msg, $headers);
}
/**
 * Truncates the specified table.
 * @param  [object] $dbc        - The MySqli DB Connection Object.
 * @param  [string] $tableName - The name of the table to be truncated.
 */
function truncateTable($dbc, $tableName)
{
    $sql = "TRUNCATE TABLE {$tableName}";
    if (!$dbc->query($sql)) {
        throw new Exception("Error truncating the table {$tableName}");
    }
}

/**********************************EXECUTE SCRIPT****************************************************/
$dbName = "gbarnett_website";
$tableName = "user_tracking";

//Threshold is in MB. 
$threshold = 1000;

try {
    getTableSize($dbc, $dbName, $tableName, $threshold);
} catch (Exception $e) {
    $error = $e->getMessage();
    sendEmail($dbc, $dbName, $tableName, $error);
}
