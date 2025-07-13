<link rel="stylesheet" href="../public/assets/css/style.css">
<?php

$logFile = 'c:/xampp/apache/logs/error.log'; // Path to your log file

if (!file_exists($logFile)) {
    die("Log file not found.");
}

$logs = file($logFile);

foreach ($logs as $log) {
    preg_match('/\[(.*?)\] \[php:(.*?)\] \[pid (\d+):tid (\d+)\] \[client (.*?)\] (.*?) in (.*?) on line (\d+), referer: (.*)/', $log, $matches);

    if ($matches) {
        echo "Date: {$matches[1]}" . "<br>";
        echo "Type: PHP {$matches[2]}" . "<br>";
        echo "PID: {$matches[3]} | TID: {$matches[4]}" . "<br>";
        echo "Client: {$matches[5]}" . "<br>";
        echo "File: {$matches[7]}" . "<br>";
        echo "Line: {$matches[8]}" . "<br>";
        echo "Message: {$matches[6]}" . "<br>";
        echo "Referer: {$matches[9]}" . "<br>";
        echo str_repeat("-", 80) . "" . "<br>";
        echo "<Br>";
    }
}
