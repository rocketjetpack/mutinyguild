<?php 
session_start();

require_once("lib/config.php");
require_once("lib/smarty.php");
require_once("lib/functions.php");

if (isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1)
{
	// If th current user has the LootMaster role in Discord set the session information to true
	if( IsLootMaster($_SESSION['discord']['id']) )
	{
		$_SESSION['isLootMaster'] = 1;
		$smarty->assign('isLootMaster', True);
	} else {
		unset($_SESSION['isLootMaster']);
		$smarty->assign('isLootMaster', False);
	}

	$smarty->assign("member_name", $_SESSION['member']['displayName']);
	if($_SESSION['member']['displayName'] == "Kast"){
		$_SESSION['isDev'] = 1;
		$smarty->assign('isDev', True);
	} else {
		$smarty->assign('isDev', False);
	}
	$smarty->assign("logged_in", True);
	$smarty->assign("member_id", $_SESSION['discord']['id']);
} else {
	$smarty->assign("logged_in", false);
}
?>