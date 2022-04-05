<?php
/*
 * sktrack
 * Configuration
 *
 * Author: hexa-
 * File: config.inc.php
 * Created: 26.03.2012, 05:10:02
 */

ini_set("date.timezone", "America/New_York");

// mysql settings
$db 			= array(); 
$db["host"]		= "*****";
$db["user"]		= "*****";
$db["pass"]		= "*****";
$db["db"]		= "*****";

// compensate for timezone differences between mysql and php
$tz			= array();
$tz['mysql']		= "UTC-8";
$tz['target']		= "UTC-6";

setlocale(LC_ALL, $sk['locale']);

$dblink = mysqli_connect($db["host"], $db["user"], $db["pass"], $db["db"]);
if (mysqli_connect_errno()) {
    die("Connect failed: %s\n" + mysqli_connect_error());
    exit();
 }

mysqli_select_db($dblink, $db["db"]);

// Discord auth link
$config['auth_link'] = 'https://discord.com/api/oauth2/authorize?client_id=929058979617857537&redirect_uri=https%3A%2F%2Fwww.mutiny-guild.com%2Findex.php&response_type=code&scope=guilds.members.read'

unset($db);
?>