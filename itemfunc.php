<?php


$db 			= array(); 
$db["host"]		= "*****";
$db["user"]		= "*****";
$db["pass"]		= "*****";
$db["db"]		= "*****";
$dblink = mysqli_connect($db["host"], $db["user"], $db["pass"], $db["db"]);

$retVal = array();
$retVal["error"] = "false";

if (mysqli_connect_errno()) {
	$retVal["error"] = "true";
	$retVal["error_msg"] = "Database connection failure.";
	die(json_encode($retVal));
}
mysqli_select_db($dblink, $db["db"]);
unset($db);



if( isset ($_GET['method']) )
{
	$method = $_GET['method'];
	$retVal["method"] = $method;

	if( $method == "populate") {
		header("Content-Type: text/html");
		print("<html>");
		print("populating database <br>");

		$query = "select * from sk_items where tooltip_html = '' limit 500";
		print("Set initial query for all item ids with no tooltip. <br>");

		$result = mysqli_query($dblink, $query);
		print("Query run <br>");
		$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
		print("mysqli_fetch_all <br>");
		$baseurl = "https://tbc.wowhead.com";
		$opts = [ 'https' => [ 'max_redirects' => 3,], ];
		$xmlterm = "xml";

		foreach( $data as $record){
			print_r($record['name']);
						
			$queryString = http_build_query([
				'item' => $record["id"],
			]);
			$requestUri = sprintf("%s/%s?%s", $baseurl, $queryString, $xmlterm);
			$xmlContents = simplexml_load_file($requestUri);

			if(isset($xmlContents->error))
			{	
				break;
			}

			$imageName = (string)$xmlContents->item[0]->htmlTooltip;
			$query = "update sk_items set tooltip_html = '" . $imageName . "' where id = " . $record["id"];
			$result = mysqli_query($dblink, $query);
			print("... image_name updated. <br>");
		}
		print("</html>");

		die();
	}
	
	header("Content-Type: application/json");
	if( $method == "search" ) {
		if( !isset($_GET['searchval']) ) { 
			$retVal["error"] = "true";
			$retVal["error_msg"] = "Method is set to search but no searchval variable is defined.";
			print(json_encode($retVal));
			die();
		}
		$name = $_GET['searchval'];
		if( strlen($name) < 3 ) {
			$retVal["error"] = "true";
			$retVal["error_msg"] = "Cowardly refusing to search for an item with less than a 3 character needle.";
			print(json_encode($retVal));
			die();
		 }
		$query = "select id, name from sk_items where name like '%" . $name ."%' order by name asc";
		$result = mysqli_query($dblink, $query);
		$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$retVal["data"]["items"] = $data;
		$retVal["data"]["num_items"] = count($retVal["data"]["items"]);
	} elseif( $method == "details" ) {
		$name = $_GET['itemid'];
		$query = "select id, name from sk_items where id = '" . $name ."'";
		$result = mysqli_query($dblink, $query);

		if ( $result->num_rows > 1 )
		{
			$retVal["error"] = "true";
			$retVal["error_msg"] = "More than one item returned in details query.";
		} elseif ( $result->num_rows == 0 )
		{
			$retVal["error"] = "true";
			$retVal["error_msg"] = "No item returned in details query.";
		}
		$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$retVal["data"] = $data;

		// Check for missing image_name field
		$query = "select * from sk_items where id = " . $data[0]["id"] . "";
		$result = mysqli_query($dblink, $query);
		$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
		$result = mysqli_query($dblink, $query);
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
			$result = mysqli_query($dblink, $query);
			$retVal["data"]["tooltip_html"] = $tooltiphtml;
			$retVal["TooltipCheck"] = "Updated database record.";
		} else {
			$retVal["data"][0]["tooltip_html"] = $data[0]["tooltip_html"];
		}

		$retVal["data"] = $retVal["data"][0];
		
	} else {
		$retVal["error"] = "true";
		$retVal["error_msg"] = "Unknown method specified in the request.";
	}

} else {
	$retVal["error"] = "true";
	$retVal["error_msg"] = "No method specified as part of the request.";
}



print(json_encode($retVal));
?>