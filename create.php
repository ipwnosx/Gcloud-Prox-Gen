<?php
/******************************************************************************
 *
 * Create virtual servers in cloud with Squid 3 proxy ready to go
 * Edit config.php to set proxy username and password.
 *
 ******************************************************************************/

require __DIR__ . '/config.php';
require __DIR__ . '/includes/Cmd.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/gcloud.php';

showStartBanner();

echo "\nSelect Data Center: \n\n";

foreach ($dcRegions as $key => $location) {
    echo ($key + 1) . '. ' . $location['location'] . "\n";
}


echo "\nSelect: ";

$userSelection = (int) readInput();

if ($userSelection < 1 || $userSelection > count($dcRegions)) {
    die("\ninvalid selection\n");
}

$selectedRegion = $dcRegions[$userSelection - 1];

$currentRegion = $selectedRegion['region'];

echo "\n";
echo "select zone: \n\n";


foreach ($selectedRegion["zones"] as $key => $location) {
    echo ($key + 1) . '. ' . $location . "\n";
}

echo "\nSelect: ";

$userSelection = (int) readInput();

if ($userSelection < 1 || $userSelection > count($selectedRegion["zones"])) {
    die("\ninvalid selection\n");
}

$selectedZone = $selectedRegion["zones"][$userSelection - 1];

$selectedRegion["zone"] = $selectedZone;


if (! defined("INSTANCE_TYPE")) {
    echo "\n";
    echo "select instance type: \n\n";
    
    foreach ($gcloudInstnaceTypes as $key => $gcloudInstnaceType) {
        echo ($key + 1) . '. ' . $gcloudInstnaceType["info"] . "\n";
    }

    echo "\nSelect: ";

    $userSelection = (int) readInput();

    if ($userSelection < 1 || $userSelection > count($gcloudInstnaceTypes)) {
        die("\ninvalid selection\n");
    }

    define('INSTANCE_TYPE', $gcloudInstnaceTypes[$userSelection - 1]["type"]);
}

echo "\n";
echo "How many server you want to create: ";

$numServer = readInput();

if (! is_numeric($numServer)) {
    echo "Invalid number provided.\n";
    echo "Aborting...\n";
    exit();
}

showWaitMessage($numServer, $selectedRegion['location']);

if (AUTH_TYPE == "IP") {
    $userDataContent =  file_get_contents(__DIR__ . "/data/install-ip.sh");
    $userDataContent = str_replace("MY_IP_ADDR", ALLOW_IP, $userDataContent);
} else {
    $userDataContent =  file_get_contents(__DIR__ . "/data/install.sh");
    $userDataContent = str_replace("SQUID_USERNAME", SQUID_USERNAME, $userDataContent);
    $userDataContent = str_replace("SQUID_PASSWORD", SQUID_PASSWORD, $userDataContent);
    $userDataContent = str_replace("SQUID_PORT", SQUID_PORT, $userDataContent);
}

if (SQUID_PORT != 3128) {
    $squidCmd = '/bin/sed -i "s/http_port 3128/http_port ' . SQUID_PORT . '/g" /etc/squid/squid.conf';
    $userDataContent = str_replace("#SED_SQUIDPORT", $squidCmd, $userDataContent);
}

$userDataFile = "/tmp/serverok-gcloud.sh";

$fp = fopen($userDataFile,"w");
fwrite($fp, $userDataContent);
fclose($fp);

$serverNames = "";

for ($i=0; $i< $numServer; $i++) {
    $serverName = "sok-" . substr(time(),6,4) . "$i";
    $serverNames .= $serverName . ' ';
    sleep(1);
}

$cmd = GCLOUD_BIN . " compute instances create " . $serverNames .
    "--zone=" . $selectedRegion["zone"] . ' ' .
    "--machine-type=" . INSTANCE_TYPE . " " .
    "--image-family=debian-9 --image-project=debian-cloud " .
    "--metadata-from-file startup-script=" . $userDataFile . ' ' .
    "--format=json";

writeLog($cmd, "gcloud");
$result = Cmd::run($cmd);

$serverIPs = [];

foreach ($result as $serverInfo) {
    $serverIPs[] = $serverInfo->networkInterfaces[0]->accessConfigs[0]->natIP;
}

listProxy($serverIPs, true);

