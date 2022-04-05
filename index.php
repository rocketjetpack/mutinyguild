<?php
require_once("lib/config.php");
require_once("lib/session.php");
require_once("lib/functions.php");
require("lib/controller.php");

if( isset($_GET['alert']) && $_GET['alert'] == 'discordSuccess' ) {
    $smarty->assign("alert_success", True);
    $smarty->assign("success_message", "Discord authentication successful.");
} else if( isset($_GET['alert']) && $_GET['alert'] == 'discordFailure' ) {
    $smarty->assign("alert_error", True);
    $smarty->assign("error_message", "You are not a member of the Mutiny Discord server.");
} else if( isset($_GET['alert']) && $_GET['alert'] == 'unknownChars' ) {
    $smarty->assign("alert_error", True);
    $smarty->assign("error_message", "The following characters are unknown to the database: " . $_GET['list']);
} else if( isset($_GET['alert']) && $_GET['alert'] == 'kskaddsuccess' ) {
    $smarty->assign("alert_success", True);
    $smarty->assign("success_message", $_GET['message']);
}

header('content-type: text/html; charset=utf-8');
$smarty->display('structure.tpl');



?>
