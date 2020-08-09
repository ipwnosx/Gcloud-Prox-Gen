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

$cmd = GCLOUD_BIN . " compute instances list --format=json";
$result = Cmd::run($cmd);

$deleteServerFolder = __DIR__ . "/delete/";

if (! is_dir($deleteServerFolder)) {
    if (!mkdir($deleteServerFolder)) {
        die("Unable to create folder $deleteServerFolder\n");
    }
}

foreach ($result as $server) {
    $serverName = $server->name;
    if (strpos($serverName, "sok-") === false) {
        echo "Skipping $serverName\n";
        continue;
    }
    $serverZoneUrl = $server->zone;
    $serverZoneUrlParts = explode("/", $serverZoneUrl);
    $serverZone = $serverZoneUrlParts[8];
    $deleteServerFile = $deleteServerFolder . $serverName;
    $fp = fopen("$deleteServerFile", "w");
    fwrite($fp, $serverZone);
    fclose($fp);
    echo "Server $serverName marked for deletion\n";
}

echo "\nLogin to your Google Cloud Console and make sure there is no running instances.\n";
