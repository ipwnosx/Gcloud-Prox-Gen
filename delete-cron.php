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

$deleteServerFolder = __DIR__ . "/delete/";

if (!is_dir($deleteServerFolder)) {
    die("ERROR: Directory not found: $deleteServerFolder");
}

if ($dh = opendir($deleteServerFolder)) {
    while (($file = readdir($dh)) !== false) {
        if (filetype($deleteServerFolder . $file) == "file") {
            $serverName = $file;
            $serverZone = file_get_contents($deleteServerFolder . $serverName);
            unlink($deleteServerFolder . $serverName);
            $cmd = GCLOUD_BIN . " compute instances delete " .
                $serverName .
                " --zone=" . $serverZone . " -q";
            writeLog($cmd, "gcloud-delete");
            $result = Cmd::run($cmd);
        }
    }
    closedir($dh);
}