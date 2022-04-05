<?php
require("lib/config.php");
require_once("lib/functions.php");

$doRaid = False;
$resume = False;
$smarty->assign("resumedRaid", False);

if ( isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1 && isset($_POST['raid']) && isset($_POST['otp']) && isset($_POST['do']) && $_POST['do'] == 'handle_loot' && isset($_POST['list']) && isset($_POST['raid_name']))
{
    $doRaid = True;
} else if ( isset($_SESSION) && isset ($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1 && $_SESSION['isLootMaster'] == 1 && isset($_POST['resume_raid_id']) && isset($_POST['otp']) && isset($_POST['do']) && $_POST['do'] == 'handle_loot') {
    $resumedRaidId = $dblink->real_escape_string($_POST['resume_raid_id']);
    $resumedRaidOtp = $dblink->real_escape_string($_POST['otp']);
    $doRaid = True;
    $resume = True;
    $smarty->assign("resumedRaid", True);
}

if ( $doRaid == True) {
    if( $resume == False ) {
        // If no raid name has been supplied set the raid name to the current timestamp
        if($_POST['raid_name'] == '') { $_POST['raid_name'] = time(); }

        // We have all the information necessary to start a new raid
        // Step 1: Build array of information about raid participants
        $query = "SELECT sk_list_position.*,sk_users.username as name
        FROM sk_list_position
        INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
        WHERE list_id = ".$_POST['list']."
        ORDER BY sk_users.username ASC";

        $result = mysqli_query($dblink, $query);
        $pool = array(); // list 

        while ($row = mysqli_fetch_assoc($result)) {
            if( in_array($row['user_id'], $_POST['raid'] )) {
                $pool[] = array("pos" => $row['position'], "name" => $row['name'], "uid" => $row['user_id']);
            }
        }
        $position = array_column($pool, 'pos');
        array_multisort($position, SORT_ASC, $pool);
        $smarty->assign("pool", $pool);

        // Step 2: Create raid entry in DB
        // Step 2a: Check if this is a repeat creation, if so skip this step
        $list = $dblink->real_escape_string(intval($_POST['list']));
        $smarty->assign('listid', $list);
        $title = $dblink->real_escape_string($_POST['raid_name']);
        $otp = $dblink->real_escape_string($_POST['otp']);

        $query = "select * from sk_raids where otp = " . $otp . " order by id asc";
        $result = mysqli_query($dblink, $query);
        if (mysqli_num_rows($result) == 0) {
            if( !$resumedRaid ) {
                $query = "INSERT INTO sk_raids (list_id, title, is_active, otp) VALUES ($list, '".$title."', 1, " . $otp . ")";
                mysqli_query($dblink, $query);
                $raid_id = mysqli_insert_id($dblink);
                mysqli_commit($dblink);
            } else {
                $_SESSION['raidid'] = $_POST['resume_raid_id'];
                $raid_id = $_POST['resume_raid_id'];
            }
        } else {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['active_raid'] = $row['id'];
            $raid_id = $row['id'];
            
            $query = "delete from sk_raids where otp = " . $otp . " and id != " . $row['id'];
            mysqli_query($dblink, $query);
            mysqli_commit($dblink);
        }
        $smarty->assign('raidid', $raid_id);
        
        if (count($_POST['raid']) > 0) {
            $playerIds = array();
            $pos_details = array();

            foreach( $pool as $p) {

                $playerIds[] = $p['uid'];
                $pos_details[] = array(
                    'user_id' => $p['uid'],
                    'username' => $p['name'],
                    'pos' => $p['pos']
                );
            }

            $query = "UPDATE sk_list_position SET raid_id = $raid_id WHERE user_id IN (".implode(",", $playerIds).")";
            mysqli_query($dblink, $query);
            $query = "UPDATE sk_list_position SET raid_id = -1 WHERE AND user_id NOT IN (".implode(",", $playerIds).")";
            mysqli_query($dblink, $query);
            // Set the newly started raid as active
            $query = "UPDATE sk_lists SET active_raid = $raid_id WHERE id = ".$list."";
            mysqli_query($dblink, $query);
            
            $smarty->assign('raid_list_details', $pos_details);
        }
    } else {
        // Get a list of users assigned to this raid
        $query = "SELECT sk_list_position.*,sk_users.username as name
        FROM sk_list_position
        INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
        WHERE raid_id = " . $resumedRaidId . " ORDER BY sk_users.username ASC";
        $result = mysqli_query($dblink, $query);
        $pool = array(); // list 

        while ($row = mysqli_fetch_assoc($result)) {
            $pool[] = array(
                'pos' => $row['position'],
                'name' => $row['name'],
                'uid' => $row['user_id']
            );
        }
        $position = array_column($pool, 'pos');
        array_multisort($position, SORT_ASC, $pool);
        $smarty->assign("raidid", $resumedRaidId);
        $smarty->assign("listid", 3);
        $smarty->assign("pool", $pool);

        $playerIds = array();
        $pos_details = array();

        foreach( $pool as $p) {
            $playerIds[] = $p['uid'];
            $pos_details[] = array(
                'user_id' => $p['uid'],
                'username' => $p['name'],
                'pos' => $p['pos']
            );
        }

        $smarty->assign('raid_list_details', $pos_details);
    }
} else if( $_POST['do'] == 'changeraidmembers') {
    // Get current raid members
    $raidQuery = "SELECT * FROM sk_raids where is_active = 1 and id = " . $_POST['raidid'];
    $raidMembersQuery = "SELECT sk_list_position.*,sk_users.username as name
    FROM sk_list_position
    INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
    WHERE list_id = 3 AND sk_list_position.raid_id = " . $_POST['raidid'] . "
    ORDER BY sk_users.username ASC";

    $raidMemberResult = mysqli_query($dblink, $raidMembersQuery);
    $raidMembers = array();
    while( $row = mysqli_fetch_assoc($raidMemberResult) ) {
        $raidMembers[] = array(
            'user_id' => $row['user_id'],
            'position' => $row['position'],
            'name' => $row['name']
        );
    }
    $smarty->assign("in_progress_raid_members", $raidMembers);
    $smarty->assign("in_progress_raid_id", $_POST['raidid']);
    $smarty->assign("raid_in_progress", 1);

}

$activeRaidQuery = "SELECT * FROM sk_raids where is_active = 1";
$result = mysqli_query($dblink, $activeRaidQuery);
$row = mysqli_fetch_array($result);
if( mysqli_num_rows($result) > 0 ) {
	// There is an unfinished raid in progress.  Set the needed information and resume it.
	$activeRaid = array(
        'raid_id' => $row['id'],
        'list_id' => $row['list_id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => null,
        'is_active' => 1,
        'otp' => $row['otp'],
        'log_id' => $row['log_id'],
        'members' => GetMembersByRaid($row['id'], $dblink)
    );
}

?>