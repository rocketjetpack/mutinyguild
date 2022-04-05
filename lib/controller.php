<?php
require_once("lib/config.php");
require_once("lib/session.php");
require_once("lib/functions.php");

if (isset ($_POST['do']))
{
	$do = $_POST['do'];
    
    //print("<pre>"); print_r($_SESSION); die();

    $smarty->assign("alert_success", False);
    $smarty->assign("alert_error", False);

    if( $do == "refreshList" && isset($_SESSION) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1 && isset($_POST['raidId'])) {
        $raidId = $dblink->real_escape_string($_POST['raidId']);
        // fetch all positions in the current raid
		$raidPositions = array();
		$query = 'select skp.user_id, sku.username, skp.position from sk_list_position skp inner join sk_users sku on sku.id = skp.user_id where raid_id = ' . $raidId . ' order by skp.position asc';
		$result = mysqli_query($dblink, $query);

		while( $row = mysqli_fetch_assoc($result) ) {
			$raidPositions[] = [
				'charName' => $row['username'],
				'position' => $row['position']
			];
		}

        $retVal = array();
        $retVal['error'] = 'false';
        $retVal['list'] = $raidPositions;

        die(json_encode($retVal));
    } else if( $do == "award" && isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1 && isset($_POST['itemId']) && isset($_POST['playerId']) && isset($_POST['raidId']) && isset($_POST['listId']) && isset($_POST['awardMethod'])) {
        $itemId = $dblink->real_escape_string($_POST['itemId']);
		$playerId = $dblink->real_escape_string($_POST['playerId']);
		$raidId = $dblink->real_escape_string($_POST['raidId']);
		$listId = $dblink->real_escape_string($_POST['listId']);
		$lootMethod = $dblink->real_escape_string($_POST['awardMethod']);
        $signee = $_SESSION['discord']['id'];
		$retVal = array();
		$retVal['error'] = 'false';

        // Fetch the current list position for the user
        $query = "select position from sk_list_position where list_id = " . $listId . " and raid_id = " . $raidId . " and user_id = " . $playerId;
		$result = mysqli_query($dblink, $query);	

        if (mysqli_num_rows($result) == 0 ) { 
			$retVal['error'] = 'true';
			$retVal['error_msg'] = 'Could not find the user with id ' . $playerId . ' on list with id ' . $listId . ' and raid with id ' . $raidId;
			die(json_encode($retVal));
		} else if (mysqli_num_rows($result) > 1 ) {
			$retVal['error'] = 'true';
			$retVal['error_msg'] = 'Returned more than one result for player with id ' . $playerId . ' on list with id ' . $listId . ' and raid with id ' . $raidId;
			die(json_encode($retVal));
		}

        while ($row = mysqli_fetch_array($result)) {
			$retVal['playerOldPosition'] = $row['position'];
		}

        // fetch all positions in the current raid
		$raidPositions = array();
		$query = 'select user_id, position from sk_list_position where raid_id = ' . $raidId . ' order by position asc';
		$result = mysqli_query($dblink, $query);

		while( $row = mysqli_fetch_assoc($result) ) {
			$raidPositions[] = [
				'playerId' => $row['user_id'],
				'position' => $row['position']
			];
		}

        if( $lootMethod == "roll" )
		{
			//Simple loot assignment that has no impact on list positions, need only create the loot log entry
			$lootLogQuery = "insert into sk_item_log ( user_id, item_id, list_id, raid_id, pos_old, pos_new, lootmode, signee ) values ( " . $playerId . ", " . $itemId . ", " . $listId . ", " . $raidId . ", -1, -1, 1, " . $signee . ")";
			mysqli_query($dblink, $lootLogQuery);
            mysqli_commit($dblink);
		} else if ( $lootMethod == "de" ) {
			$lootLogQuery = "insert into sk_item_log ( user_id, item_id, list_id, raid_id, pos_old, pos_new, lootmode, signee ) values ( " . $playerId . ", " . $itemId . ", " . $listId . ", " . $raidId . ", -1, -1, 2, " . $signee . ")";
			mysqli_query($dblink, $lootLogQuery);
            mysqli_commit($dblink);
		} else if ( $lootMethod == "other") {
			$lootLogQuery = "insert into sk_item_log ( user_id, item_id, list_id, raid_id, pos_old, pos_new, lootmode, signee ) values ( " . $playerId . ", " . $itemId . ", " . $listId . ", " . $raidId . ", -1, -1, 3, " . $signee . ")";
			mysqli_query($dblink, $lootLogQuery);
            mysqli_commit($dblink);
		} else if( $lootMethod == "bid" ) {
			// Separate the associtive array $raidPositions into two arrays
			// one with playerId's
			// one with positions
			$newPlayerOrder = array();
			$positions = array();
			foreach( $raidPositions as $oldPos) {
				// if this loop includes the player assigned loot we skip them and add them later to the bottom of the $oldPlayerOrder
				if( $oldPos['playerId'] != $playerId )
				{
					$newPlayerOrder[] = $oldPos['playerId'];
				}
				$positions[] = $oldPos['position'];
			}
			$newPlayerOrder[] = $playerId;

			//Do some basic sanity checks (all array lengths should be the same)
			if( count($raidPositions) != count($positions) || count($raidPositions) != count($newPlayerOrder) )
			{
				$retVal['error'] = 'true';
				$retVal['error_msg'] = 'Invalid list lengths detected.  Original list had ' . count($raidPositions) . ' entries.  New player order list has ' .  count($newPlayerOrder) . ' entries.  New position list has ' . count($positions) . ' entries.';
				die(json_encode($retVal));
			}

			// Combine the $newPlayerOrder and $positions arrays into a new associative array
			$newRaidPositions = array();
			for ($i = 0; $i < count($newPlayerOrder); $i++) {
				$newRaidPositions[$i]['user_id'] = $newPlayerOrder[$i];
				$newRaidPositions[$i]['position'] = $positions[$i];
			}

			//$retVal['oldPositions'] = $raidPositions;
			//$retVal['newPositions'] = $newRaidPositions;
			$retVal['playerNewPosition'] = $newRaidPositions[count($newRaidPositions)-1]['position'];

			// Enter the loot into the loot log and update the sk_list_position with the $newRaidPositions data
			// Do this as a transaction to make sure it all happens or none of it happens, no partially committed bids
			
			mysqli_begin_transaction($dblink);
			
			try {
				$lootLogQuery = "insert into sk_item_log ( user_id, item_id, list_id, raid_id, pos_old, pos_new, lootmode, signee ) values ( " . $playerId . ", " . $itemId . ", " . $listId . ", " . $raidId . ", " . $retVal['playerOldPosition'] . ", " . $retVal['playerNewPosition'] . ", 0, " . $signee . ")";
				mysqli_query($dblink, $lootLogQuery);
				foreach( $newRaidPositions as $newPosition ) {
					$retVal['pos_updates'][] = $newPosition;
					// Delete the old position for the user
					$query = "delete from sk_list_position where user_id = " . $newPosition['user_id'];
					mysqli_query($dblink, $query);
					$retVal['queries'][] = $query;
					
					// Re-enter the user at the new position
					$query = "insert into sk_list_position (list_id, user_id, position, raid_id) values ( " . $listId . ", " . $newPosition['user_id'] . ", " . $newPosition['position'] . ", " . $raidId . ")";
					mysqli_query($dblink, $query);
					$retVal['queries'][] = $query;				
				}
				mysqli_commit($dblink);
				die(json_encode($retVal));
			} catch ( mysqli_sql_exception $exception ) {
				mysqli_rollback($dblink);
				$retVal['error'] = 'true';
				$retVal['error_msg'] = 'Loot bid transaction failed to apply.';
				die(json_encode($retVal));
			}
			mysqli_rollback($dblink);
		}		
		die(json_encode($retVal));
    } else if($do == "changeraidmembers" && isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1) {
        require_once("lib/ksk.php");
        require_once("lib/raid.php");
        $template = "ksk-changeraid.tpl";
    } else if($do == "confirmmemberchange" && isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1) {
        require_once("lib/ksk.php");
        require_once("lib/raid.php");
        $query = "UPDATE sk_list_position SET raid_id = " . $_POST['resume_raid_id'] . " WHERE user_id IN (".implode(",", $_POST['raid']).")";
        mysqli_query($dblink, $query);
        $query = "UPDATE sk_list_position SET raid_id = -1 WHERE user_id NOT IN (".implode(",", $_POST['raid']).")";
        mysqli_query($dblink, $query);
        header('Location: https://www.mutiny-guild.com/index.php?section=ksk&action=startraid');
    } else if($do == "endraid" && isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1) {
        $retVal = array(
            'error' => 'false',
            'error_msg' => ''
        );
        $raidId = $dblink->real_escape_string($_POST['raidId']);
        $query = "UPDATE sk_raids SET is_active = 0 where id = '" . $raidId . "'";
        if( isset($_POST['logLink']) )
        {
            $query = "UPDATE sk_raids SET is_active = 0, log_id = '" . $dblink->real_escape_string($_POST['logLink']) . "' WHERE id = " . $raidId;
        }        
        $result = mysqli_query($dblink, $query);
        die(json_encode($retVal));
    } else if($do == "overwritelist" && isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1)
    {
        require_once("lib/ksk.php");
        //Archive the current list
        ReplaceList($_POST['listcontents'], $dblink);
    } else if ($do == "addmember" && isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1) {
        require_once("lib/ksk.php");
        AddKSKMember($dblink->real_escape_string($_POST['newmember']), $dblink);
    } else if ($do == "handle_loot" && isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1) {
        require_once("lib/ksk.php");
        require_once("lib/raid.php");
        $template = "run-raid.tpl";
    }else if ($do == "login") {
        $params = array(
            'client_id' => $discord['client_id'],
            'redirect_uri' => $discord['urls']['redirect'],
            'response_type' => 'code',
            'scope' => $discord['scope']
        );

        // Redirect the user to Discord's authorization page
        header('Location: ' . $discord['urls']['authorize'] . '?' . http_build_query($params));
    } else if ( $do == "logout" ) {
        $_SESSION = array();
        session_destroy();
        header('Location: ' . $indexPage);
    }else if ($do == "success") {
        $smarty->assign("alert_success", True);
        $smarty->assign("success_message", $_POST['message']);
    }
    else if ($do == "error") {
        $smarty->assign("alert_error", True);
        $smarty->assign("error_message", $_POST['message']);
    }
} else if( isset ($_GET['code']) ) {
    // Discord auth has returned with a code, exchange it for a token and redirect to $indexPage
    $token = discordApiRequest( $discord['urls']['token'], array(
        'grant_type' => 'authorization_code',
        'client_id' => $discord['client_id'],
        'client_secret' => $discord['client_secret'],
        'redirect_uri' => $discord['urls']['redirect'],
        'code' => $_GET['code']
    ));
    
    if( isset($token['error'])) {
        PostToURL( $indexPage, array(
            'do' => 'error',
            'message' => 'Discord authentication error: ' . $token['error_description']
        ));
    }

    if( isset( $token['access_token']) ) {
        $_SESSION['access_token'] = $token['access_token'];
        $_SESSION['refresh_token'] = $token['refresh_token'];
        $_SESSION['scope'] = $token['scope'];
        $_SESSION['token_type'] = $token['token_type'];

        // We have a token!  Let's ensure that the user is allowed to login.
        $userData = discordApiRequest($discord['urls']['api']);
        $_SESSION['discord']['id'] = $userData['id'];
        $_SESSION['discord']['username'] = $userData['username'];
        $_SESSION['discord']['discriminator'] = $userData['discriminator'];

        if( IsAllowedToLogin($_SESSION['discord']['id'] ) ) {
            $_SESSION['logged_in'] = True;
            $_SESSION['member'] = GetMemberById($_SESSION['discord']['id']);
            header('Location: ' . $indexPage . '?alert=discordSuccess');
        } else {
            session_destroy();
            unset($_SESSION['discord']);
            unset($_SESSION['access_token']);
            unset($_SESSION['refresh_token']);
            unset($_SESSION['scope']);
            unset($_SESSION['token_type']);
            header('Location: ' . $indexPage . '?alert=discordFailure');
        }        
    } else {
        header('Location: ' . $indexPage . '?do=error&error=discordToken');
    }
    die();
}

// Template selection


if( isset($_GET['section']) && $_GET['section'] == 'about') {
    $template = "about.tpl";
} else if( isset($_GET['section']) && $_GET['section'] == 'progression') {
    $template = "progression.tpl";
} else if( isset($_GET['section']) && $_GET['section'] == 'raidhistory') {
    $template = "raidhistory.tpl";
}else if( isset($_GET['section']) && $_GET['section'] == 'ksk' && isset($_SESSION['discord'])) {
    require("lib/ksk.php");
    if( isset($_GET['action']) && $_GET['action'] == 'import') {
        $template = "ksk-import.tpl";    
    } else if( isset($_GET['action']) && $_GET['action'] == 'addmember') {
        $template = "ksk-addmember.tpl"; 
    } else if( isset($_GET['action']) && $_GET['action'] == 'startraid') {
        // Determine if there is an active raid that needs to be resumed
        $query = "select * from sk_raids where is_active = 1";
        $result = mysqli_query($dblink, $query);
        if (mysqli_num_rows($result) > 0) {
            // There is a raid that needs completion
            $row = mysqli_fetch_array($result);
            PostToURL("https://www.mutiny-guild.com/index.php", array(
                'do' => 'handle_loot',
                'otp' => $row['otp'],
                'resume_raid_id' => $row['id']
            ));
            die();
        }


        $smarty->assign("oneTimeRaidId", time());
        $template = "ksk-startraid.tpl"; 
    } else {
       $template = "ksk.tpl";
    }
}

$smarty->assign("template", $template);
?>