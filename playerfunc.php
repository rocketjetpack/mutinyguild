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

	if($method == "byname") {
		header("Content-Type: application/json");
		if( !isset($_GET['name']) ) { 
			$retVal["error"] = "true";
			$retVal["error_msg"] = "Method is set to byname but no name variable is defined.";
			print(json_encode($retVal));
			die();
		}

		$name = $dblink->real_escape_string($_GET['name']);
		$query = "select id, username from sk_users where username = '" . strtolower($name) . "'";
		$result = mysqli_query($dblink, $query);

		if( $result->num_rows > 1 )
		{
			$retVal["error"] = "true";
			$retVal["error_msg"] = "More than one user returned matching the username " . $name;
			print(json_encode($retVal));
			die();
		} else if( $result->num_rows == 0 ) {
			$retVal["error"] = "true";
			$retVal["error_msg"] = "No users founding matching username " . $name;
			print(json_encode($retVal));
			die();
		}
		$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$retVal["id"] = $data[0]['id'];
		$retVal["username"] = $data[0]['username'];
		
	}
} else {
	$retVal["error"] = "true";
	$retVal["error_msg"] = "No method specified as part of the request.";
}



print(json_encode($retVal));
?>