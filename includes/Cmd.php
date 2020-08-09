<?php

/******************************************************************************
 *
 * Need a dedicated proxy solution? Try NanoProxies.io
 * Join the Discord: discord.gg/pPrbXaH
 *
 * Create virtual servers in cloud with Squid 3 proxy ready to go
 * Edit config.php to set proxy username and password.
 *
 ******************************************************************************/

class Cmd
{
    public function run($cmd) {
        $cmd = escapeshellcmd($cmd);
        exec($cmd, $result);
        $resultAll = implode("\n", $result);
        return json_decode($resultAll);
    }

}
