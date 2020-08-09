<?php
/******************************************************************************
 *
 * Create virtual servers in cloud with Squid 3 proxy ready to go
 * Edit config.php to set proxy username and password.
 *
 ******************************************************************************/

require 'config.php';
require 'includes/Cmd.php';
require 'includes/functions.php';
require 'includes/gcloud.php';

showStartBanner();

$serverIPs = [];

$cmd = GCLOUD_BIN . " compute instances list --format=json";
$result = Cmd::run($cmd);

foreach ($result as $serverInfo) {
    $serverName = $serverInfo->name;
    if (strpos($serverName, "sok-") === false) {
        echo "Skipping $serverName\n";
        continue;
    }
    $serverIPs[] = $serverInfo->networkInterfaces[0]->accessConfigs[0]->natIP;
}

listProxy($serverIPs);

