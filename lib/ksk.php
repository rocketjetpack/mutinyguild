<?php
$query = "SELECT sk_list_position.*,sk_users.username as name
		  FROM sk_list_position
		  INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
		  WHERE list_id = 3
		  ORDER BY position ASC";
$result = mysqli_query($dblink, $query);
//die(print_r($result));

while ($row = mysqli_fetch_array($result))
{
	if ($row['raid_id'] != -1) // important for refresh time
		$raid_active = true;
		
	$pool[] = array("pos" => $row['position'],
					"name" => $row['name'],
					"uid" => $row['user_id'],
					"active" => $row['raid_id'] != -1 ? true : false);
}

$smarty->assign("pool", $pool);

// latest raids
$query = "SELECT id FROM sk_raids WHERE list_id = 3 ORDER BY start DESC LIMIT 10";
$result = mysqli_query($dblink, $query);

$raids = array();
while ($row = mysqli_fetch_array($result))
	$raids[] = $row['id'];

// latest items from the according raids
$items = array();

if (mysqli_num_rows($result) > 0)
{
	$query = "SELECT * FROM sk_item_log
		  	INNER JOIN sk_users ON sk_item_log.user_id = sk_users.id
		  	INNER JOIN sk_raids ON sk_item_log.raid_id = sk_raids.id
		  	INNER JOIN sk_items ON sk_item_log.item_id = sk_items.id
		  	ORDER BY date DESC";
	$result = mysqli_query($dblink, $query);		

	$item_iterator = array();
	$iter = 0;
	while ($row = mysqli_fetch_array($result))
	{
			$lootTime = strftime("%H:%M", handleTZ($row['date']));
			$raidStart = timetostr(handleTZ($row['start']));
			$raidDate = strftime("%b %d %Y", handleTZ($row['start']));
			$raidEnd = strftime("%H:%M", handleTZ($row['end']));
		
			if (empty($item_iterator[$row['raid_id']]))
				$item_iterator[$row['raid_id']] = 0;

			$items[$iter] = array("username" => $row['username'],
										"item_iterator" => $item_iterator[$row['raid_id']]++,
										"item" => 			getItemData($row['item_id'], $dblink),
										"lootmode" => lootmode($row['lootmode'], $row['pos_old'], $row['pos_new']),
										"loottime" => $lootTime,
										"raid_id" => $row['raid_id'],
										"raid_date" => $raidDate,
										"raid_title" => htmlentities($row['title']),
										"raid_start" => htmlentities($raidStart),
										"raid_end" => $raidEnd);
			$iter++;
	}
}

function AddKSKMember($name, $thisDB) {
	$userQuery = "INSERT INTO sk_users ( username ) values ( '" . $name . "' )";
	mysqli_query($thisDB, $userQuery);
	mysqli_commit($thisDB);
	$userIDQuery = "SELECT id from sk_users where username = '" . $name . "'";
	$result = mysqli_query($thisDB, $userIDQuery);
	$row = mysqli_fetch_array($result);
	$userID = $row['id'];
	$maxListPosQuery = "SELECT position from sk_list_position order by position desc limit 1";
	$result = mysqli_query($thisDB, $maxListPosQuery);
	$row = mysqli_fetch_array($result);
	$maxListPos = $row['position'];

	$newPos = $maxListPos + 1;
	$insertQuery = "INSERT INTO sk_list_position (list_id, user_id, position) values (3, '" . $userID . "', '" . $newPos . "')";
	mysqli_query($thisDB, $insertQuery);
	mysqli_commit($thisDB);

	header('Location: ' . $indexPage . '?section=ksk&alert=kskaddsuccess&message=Added KSK entry for ' . $name);
	die();
}

function ReplaceList($newListCSV, $thisDB) {
	$newListAr = explode(",", $newListCSV);

	//Make sure that all entries exist in the database
	$unfoundChars = array();
	foreach( $newListAr as $thisCharacter ) {
		$charQuery = "SELECT * from sk_users where username = '" . $thisCharacter . "'";
		$result = mysqli_query($thisDB, $charQuery);
		if( mysqli_num_rows($result) == 0 ) {
			$unfoundChars[] = $thisCharacter;
		}
	}

	if(count($unfoundChars) > 0) {
		$charnames = "";
		$iter = 0;
		foreach( $unfoundChars as $thisChar ) {
			$charnames .= $thisChar;
			$iter += 1;

			if( $iter < count($unfoundChars) ) {
				$charnames .= ',';
			}
		}
		header('Location: ' . $indexPage . '?section=ksk&alert=unknownChars&list=' . $charnames);
		die();
	}

	//print("<pre>");
	//print_r($newListAr);

	$listDetails = array();

	$inputQuery =  "SELECT sk_list_position.*,sk_users.username as name
					FROM sk_list_position
					INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
					WHERE list_id = 3
					ORDER BY position ASC";
	$result = mysqli_query($thisDB, $inputQuery);

	$output = array(
		"list_id" => "3"
	);

	while ($row = mysqli_fetch_array($result))
	{
		$pool[] = array("name" => $row['name'],
						"uid" => $row['user_id']);
	}
	$output['entries'] = $pool;

	$outputQuery = "insert into sk_list_archives (list) values ('" . json_encode($output) . "')";
	mysqli_query($thisDB, $outputQuery);

	$deleteQuery = "delete from sk_list_position";
	mysqli_query($thisDB, $deleteQuery);

	$index = 1;
	foreach( $newListAr as $thisMember){
		$userQuery = "SELECT id, username from sk_users where username = '" . $thisMember . "'";
		$result = mysqli_query($thisDB, $userQuery);
		while ($row = mysqli_fetch_array($result))
		{
			$thisUserID = $row['id'];
		}
		$listDetails[] = array(
			'pos' => $index,
			'user_id' => $thisUserID,
			'list_id' => '3'
		);
		$index+=1;
	}

	foreach( $listDetails as $newEntry ) {
		$insertQuery = "insert into sk_list_position (list_id, user_id, position) values ('" . $newEntry['list_id'] . "', '" . $newEntry['user_id'] . "', '" . $newEntry['pos'] . "')";
		mysqli_query($thisDB, $insertQuery);
	}
	mysqli_commit($thisDB);
	header('Location: ' . $indexPage . '?section=ksk');
	die();
}

function getItemData($itemid, $thisDB)
{
	$retVal = array();

	$query = "select * from sk_items where id = '" . $itemid ."'";
	$result = mysqli_query($thisDB, $query);

	$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
	$retVal["data"] = $data;

	switch( $retVal["data"][0]["quality"] ) {
		case 3:
			$retVal["data"][0]["quality"] = "Uncommon";
			break;
		case 4:
				$retVal["data"][0]["quality"] = "Rare";
				break;
		case 5:
			$retVal["data"][0]["quality"] = "Epic";
			break;
		case 6:
			$retVal["data"][0]["quality"] = "Legendary";
			break;
	}

	if( $retVal["data"][0]['quality'] == 6 ) {
		$retVal["data"][0]['quality'] = "Epic";
	}

	if( strlen($data[0]["image_name"]) == 0 )
	{
		$baseurl = "https://tbc.wowhead.com";
		$xmlterm = "xml";
		$opts = [ 'https' => [ 'max_redirects' => 3,], ];
		$queryString = http_build_query([
			'item' => $data[0]["id"],
		]);
		$requestUri = sprintf("%s/%s?%s", $baseurl, $queryString, $xmlterm);
		$xmlContents = simplexml_load_file($requestUri);
			if(isset($xmlContents->error))
		{	
			$retVal["error"] = "true";
			$retVal["error_msg"] = "Error retrieving XML from " . $requestUri;
			return $retVal;
		}
			$imageName = (string)$xmlContents->item[0]->icon . ".jpg";
		$query = "update sk_items set image_name = '" . $imageName . "' where id = " . $data[0]["id"];
		$result = mysqli_query($dblink, $query);
		$retVal["data"]["image_name"] = $imageName;
		$retVal["ImageCheck"] = "Updated database record.";
	} else {
		$retVal["data"][0]["image_name"] = $data[0]["image_name"];
	}
		// Check for missing tooltip_html field
	$query = "select * from sk_items where id = " . $data[0]["id"] . "";
	$result = mysqli_query($thisDB, $query);
	$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
	if( strlen($data[0]["tooltip_html"]) == 0 )
	{
		$baseurl = "https://tbc.wowhead.com";
		$xmlterm = "xml";
		$opts = [ 'https' => [ 'max_redirects' => 3,], ];
		$queryString = http_build_query([
			'item' => $data[0]["id"],
		]);
		$requestUri = sprintf("%s/%s?%s", $baseurl, $queryString, $xmlterm);
		$xmlContents = simplexml_load_file($requestUri);
			if(isset($xmlContents->error))
		{	
			$retVal["error"] = "true";
			$retVal["error_msg"] = "Error retrieving XML from " . $requestUri;
			return $retVal;
		}
			$tooltiphtml = (string)$xmlContents->item[0]->htmlTooltip . ".jpg";
		$query = "update sk_items set tooltip_html = '" . $tooltiphtml . "' where id = " . $data[0]["id"];
		$result = mysqli_query($thisDB, $query);
		$retVal["data"]["tooltip_html"] = $tooltiphtml;
		$retVal["TooltipCheck"] = "Updated database record.";
	} else {
		$retVal["data"][0]["tooltip_html"] = $data[0]["tooltip_html"];
	}

	$retVal['data'] = $retVal['data'][0];

	return $retVal;
}

$smarty->assign("lootlogentries", $iter);
$smarty->assign("items", $items);
?>