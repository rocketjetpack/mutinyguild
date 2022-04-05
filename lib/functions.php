<?php
require("lib/config.php");

function PostToURL($url, $data) {
    print("<html>");
    print("<head>");
    print("<title>Mutiny Guild Redirect</title>");
    print("</head>");
    print("<body>");
    print("<form id='autoform' action='" . $url . "' method='post'>");
    foreach( $data as $key => $value ){
        print("<input type='hidden' name='" . $key . "' value='" . $value . "' />");
    }
    print("</form>");
    print("<script type='text/javascript'>");
    print("document.getElementById('autoform').submit();");
    print("</script>");
    print("</body>");
    print("</html>");
}

function GetDatabaseLink() {
    $db 			= array(); 
    $db["host"]		= "*****";
    $db["user"]		= "*****";
    $db["pass"]		= "*****";
    $db["db"]		= "*****";

    $dblink = mysqli_connect($db["host"], $db["user"], $db["pass"], $db["db"]);
    if (mysqli_connect_errno()) {
        die("Connect failed: %s\n" + mysqli_connect_error());
        exit();
     }
    
    mysqli_select_db($dblink, $db["db"]);
    unset($db);
    return $dblink;
}

function GetMembersByRaid($raid_id, $thisDB) {
    $membersQuery = "SELECT sklp.list_id, sklp.user_id, sklp.position, sku.username from sk_list_position sklp inner join sk_users sku on sku.id = sklp.user_id where raid_id = '" . $raid_id . "'";
    $result = mysqli_query($thisDB, $membersQuery);
    $retVal = array();
    while( $row = mysqli_fetch_array($result) ) {
        $retVal[] = array(
            'user_id' => $row['user_id'],
            'list_id' => $row['list_id'],
            'position' => $row['position'],
            'username' => $row['username']
        );
    }
    return $retVal;
}

function GetMemberById( $discordId ) {
    $dblink = GetDatabaseLink();
    $query = "SELECT u.displayName, u.name, u.discriminator FROM users u where id = '" . $discordId . "'";
    $result = mysqli_query($dblink, $query);

    $retVal = array();

    if( mysqli_num_rows($result) == 1 ) {
        while ($row = mysqli_fetch_array($result)) { 
            $retVal['displayName'] = $row['displayName'];
            $retVal['username'] = $row['name'];
            $retVal['discriminator'] = $row['discriminator'];
            $roleQuery = "SELECT ru.user_id, dr.name from roles_by_user ru inner join discord_roles dr on dr.id = ru.role_id where user_id = '" . $discordId . "' and dr.name != '@everyone'";
            $roleResult = mysqli_query($dblink, $roleQuery);
            while ($roleRow = mysqli_fetch_array($roleResult)) { 
                $retVal['roles'][] = $roleRow['name'];
            }
        }
    } else {
        PostToURL( $indexPage, array(
            'do' => 'error',
            'message' => 'Could not load member data.'
        ));
    }

    return $retVal;
}

function handleTZ($timestamp)
{
	global $tz;

	if (strtolower($tz['mysql']) != strtolower($tz['target']))
	{
		// timestamps in mysql can have a different timezone as system,
		// thanks to http://stackoverflow.com/questions/4573660/php-mysql-timestamp-and-timezones
		$time = new DateTime($timestamp, new DateTimeZone($tz['mysql']));
		$time->setTimezone(new DateTimeZone($tz['target']));
		
		return $time->getTimestamp();	
		
	} else return strtotime($timestamp);

}

function timetostr($timestamp)
{
	$date = "";
	//if (date("Y-m-d", $timestamp) == date("Y-m-d"))
	//	$date .= "Heute, ".strftime("%H:%M", $timestamp);
	//elseif (date("Y-m-d", $timestamp) == date("Y-m-d", time() - 86400))
	//	$date .= "Gestern, ".strftime("%H:%M", $timestamp);
	//else
	$date .= strftime("%b %d %Y, %H:%M", $timestamp);

	return $date;

}

function lootmode($mode, $old = -1, $new = -1)
{
	switch ($mode)
	{
		case 0:
			$lootmode = "Suicide";
			$lootmode .= " (".($old)." &#8658; ".($new).")"; // append old->new pos
			break;
		case 1:
			$lootmode = "Roll";
			break;
		case 2:
			$lootmode = "Disenchant";
			break;
		case 3:
			$lootmode = "Other";
			break;
		default:
			$lootmode = "ERROR";
			break;
	}
	
	return $lootmode;
	
}

function IsLootMaster( $discordId ) {
    //print("checking if discordId " . $discordId . " has the Loot Master role<br>");
    $dblink = GetDatabaseLink();
    $query = "SELECT ru.user_id, dr.name from roles_by_user ru inner join discord_roles dr on dr.id = ru.role_id where user_id = '" . $discordId . "' and dr.name = 'Loot Master'";
    //print($query . "<br>");
    $result = mysqli_query($dblink, $query);

    if( mysqli_num_rows($result) == 0 ) {
        return False;
    } else {
        return True;
    }
}

function IsAllowedToLogin( $discordId ) {
    //if( $discordId == '234109264048685056') {
    //    return False;
    //}
    $dblink = GetDatabaseLink();
    $query = "SELECT ru.user_id, dr.name from roles_by_user ru inner join discord_roles dr on dr.id = ru.role_id where user_id = '" . $discordId . "' and dr.name != '@everyone'";
    $result = mysqli_query($dblink, $query);

    if( mysqli_num_rows($result) == 0 ) {
        return False;
    } else {
        while( $row = mysqli_fetch_array($result) ) {
            $roleName = $row['name'];
            if( $roleName == "Crewmate" || $roleName = "Friends and Family" ) {
                return True;
            }
        }
    }
}

function discordApiRequest($url, $post=False, $headers=array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    //$response = curl_exec($ch);

    if( $post ) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    }

    $headers[] = 'Accept: application/json';

    if( isset($_SESSION['access_token']) ) {
        $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response, True);
}
?>