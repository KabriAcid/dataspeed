<?php

$logFile = 'c:/xampp/apache/logs/error.log'; // Path to your log file

if (!file_exists($logFile)) {
    die("Log file not found.");
}

$logs = file($logFile);

foreach ($logs as $log) {
    preg_match('/\[(.*?)\] \[php:(.*?)\] \[pid (\d+):tid (\d+)\] \[client (.*?)\] (.*?) in (.*?) on line (\d+), referer: (.*)/', $log, $matches);

    if ($matches) {
        echo "Date: {$matches[1]}\n";
        echo "Type: PHP {$matches[2]}\n";
        echo "PID: {$matches[3]} | TID: {$matches[4]}\n";
        echo "Client: {$matches[5]}\n";
        echo "File: {$matches[7]}\n";
        echo "Line: {$matches[8]}\n";
        echo "Message: {$matches[6]}\n";
        echo "Referer: {$matches[9]}\n";
        echo str_repeat("-", 80) . "\n";
    }
}
