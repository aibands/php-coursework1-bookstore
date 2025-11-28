<?php
    $logFile = 'bookstore_log.txt';

    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
    } else {
        $logContent = "Log file not found.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bookstore Log</title>
</head>
<body>
    <h1>Bookstore Log</h1>
    <!-- make it preformatted so it start new line-->
    <pre><?= $logContent ?></pre>
</body>
</html>
