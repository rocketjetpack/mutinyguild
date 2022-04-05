<?php
// Enable error display for development

if( isset($_SESSION['isDev']) && $_SESSION['isDev'] == True) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('max_execution_time', 300);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
}

ini_set("date.timezone", "America/New_York");

$db 			= array(); 
$db["host"]		= "*****";
$db["user"]		= "*****";
$db["pass"]		= "*****";
$db["db"]		= "*****";


$kskdb 			= array(); 
$kskdb["host"]		= "*****";
$kskdb["user"]		= "*****";
$kskdb["pass"]		= "*****";
$kskdb["db"]		= "*****";


$indexPage = 'https://www.mutiny-guild.com/index.php';

$discord = array(
    "client_id" => "*****",
    "client_secret" => "*****",
    "scope" => "identify",
    "urls" => array(
        "authorize" => "https://discord.com/api/v8/oauth2/authorize",
        "token" => "https://discord.com/api/v8/oauth2/token",
        "api" => "https://discord.com/api/v8/users/@me",
        "revoke" => "https://discord.com/api/v8/oauth2/token/revoke",
        "redirect" => "https://www.mutiny-guild.com/index.php"
    )
);

$loglink = 'https://classic.warcraftlogs.com/reports';

$tz			= array();
$tz['mysql']		= "America/Los_Angeles";
$tz['target']		= "America/New_York";

$sk['locale']		= "en_US";
setlocale(LC_ALL, $sk['locale']);

$kskdblink = mysqli_connect($kskdb["host"], $kskdb["user"], $kskdb["pass"], $kskdb["db"]);
mysqli_select_db($kskdblink, $kskdb["db"]);
unset($kskdb);

$dblink = mysqli_connect($db["host"], $db["user"], $db["pass"], $db["db"]);
if (mysqli_connect_errno()) {
    die("Connect failed: %s\n" + mysqli_connect_error());
    exit();
 }

mysqli_select_db($dblink, $db["db"]);

unset($db);
?>