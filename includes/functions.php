<?php
/******************************************************************************
 *
 * Creates Linode virtual machines with Squid 3 proxy ready to go
 * Edit config.php to set proxy username and password.
 *
 ******************************************************************************/

function readInput() {
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    $line = trim($line);
    fclose($handle);
    return $line;
}

function createDir($dirName) {
    if (!is_dir($dirName)) {
        if (!mkdir($dirName)) {
            die("Failed to create $dirName");
        }
    }
}

function dd($var) {
    print_r($var);
    exit;
}

function showStartBanner() {
    showLine();
    echo "Google Cloud Proxy Creator 2.0\n";
    echo "Support: admin@sneakerhandbook.com\n";
    showLine();
    echo "\n";
}

function showWaitMessage($numServer, $serverLocation) {
    echo "\n\n";
    echo "Setting up $numServer server(s) in $serverLocation.\n";
    echo "This can take several minutes depending on number of servers.\n";
    echo "\033[32m";
    echo "NOW IS THE TIME TO GO GET ANOTHER CUP OF COFFEE.\n\n";
    echo "\033[0m";
}

function listProxy($servers, $saveToFile = false) {
    if (empty($servers)) {
        echo "\nNo servers found.\n\n";
        return;
    }
    if ($saveToFile) {
        $saveToFileLocation = $_SERVER['HOME'] . '/proxy-' . date('Y-m-d-h-i-s') . '.txt';
    }
    newLine();
    foreach ($servers as $server) {
        if (AUTH_TYPE == "IP") {
            $proxyServerInfo = $server . ':' . SQUID_PORT . "\n";
        } else {
            $proxyServerInfo = $server . ':' . SQUID_PORT . ':' . SQUID_USERNAME . ':' . SQUID_PASSWORD . "\n";
        }
        echo $proxyServerInfo;
        if ($saveToFile) {
            error_log($proxyServerInfo, 3, $saveToFileLocation);
        }
    }
    newLine();
    if ($saveToFile) {
        echo "Proxy server info saved to file - $saveToFileLocation";
        newLine();
    }
}

function showLine($char='=') {
    for ($i=1; $i< 64; $i++) {
        echo "$char";
    }
    echo "\n";
}

function newLine() {
    echo "\n";
}

function writeLog($logText, $fileName = 'gcloud', $echo = 0) {

    $logFilePath = '/tmp/' . $fileName . '.log';

    if (! file_exists($logFilePath)) {
        if (! touch($logFilePath)) {
            echo "\nLog file ($logFilePath) creation failed.\n";
            exit;
        }
    }
    $logText = $logText . "\n";
    @error_log($logText, 3, $logFilePath);

    if ($echo == 1) echo "$logText";
}
