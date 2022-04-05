<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);

error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', '929058979617857537');
define('OAUTH2_CLIENT_SECRET', 'NczNWIZtIIJrTqWos4Fep4mfJ8iUJ6D0');

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/v8/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';




if( get('action') == 'logout') {
    logout($revokeURL, array(
        'token' => session('access_token'),
        'token_type_hint' => 'access_token',
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
    ));
    unset($_SESSION['access_token']);
    header('location: ' . $_SERVER['PHP_SELF']);
    die();
}

session_start();

if(get('action') == 'login') {

    $params = array(
      'client_id' => OAUTH2_CLIENT_ID,
      'redirect_uri' => 'https://www.mutiny-guild.com/model/auth_example.php',
      'response_type' => 'code',
      'scope' => 'identify'
    );
  
    // Redirect the user to Discord's authorization page
    header('Location: https://discord.com/api/oauth2/authorize' . '?' . http_build_query($params));
    die();
  }

  if(get('code')) {

    // Exchange the auth code for a token
    $token = apiRequest($tokenURL, array(
      "grant_type" => "authorization_code",
      'client_id' => OAUTH2_CLIENT_ID,
      'client_secret' => OAUTH2_CLIENT_SECRET,
      'redirect_uri' => 'https://www.mutiny-guild.com/model/auth_example.php',
      'code' => get('code')
    ));

    print("Token data: <br /><pre>");
    print_r($token);
    print("</pre>");
    
    $logout_token = $token->access_token;
    $_SESSION['access_token'] = $token->access_token;
  
  
    header('Location: ' . $_SERVER['PHP_SELF']);
  }

if( session('access_token')) {
    $user = apiRequest($apiURLBase);
    echo '<h3>Logged in</h3>';
    echo '<h4>Welcome, ' . $user->username . '</h4>';
    echo '<pre>';
    print_r($user);
    echo '</pre>';
} else {
    echo '<h3>Not logged in</h3>';
    echo '<p><a href="?action=login">Log In</a></p>';
}

if( get('action') == 'logout') {
    logout($revokeURL, array(
        'token' => session('access_token'),
        'token_type_hint' => 'access_token',
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET
    ));
    unset($_SESSION['access_token']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    die();
}

function apiRequest($url, $post=FALSE, $headers=array()) {
    
    print("apiRequest called for:<br />");
    print("URL: " . $url . "<br />");
    print("Data: <br />");
    print("<pre>");
    print_r($post);
    print("</pre>");
    

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  
    $response = curl_exec($ch);
  
  
    if($post) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
      curl_setopt($ch, CURLOPT_POST, 1);
      $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    }
  
    $headers[] = 'Accept: application/json';
  
    if(session('access_token'))
      $headers[] = 'Authorization: Bearer ' . session('access_token');
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    
    return json_decode($response);
  }

function logout($url, $data=array()) {
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
        CURLOPT_POSTFIELDS => http_build_query($data)
    ));
    $response = curl_exec($ch);
    return json_decode($response);
}

function get($key, $default=NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) { 
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}


/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("oauth2.model.php");

$oauth2 = new OAuth2("929058979617857537", "110ec65fbb45e2ac0ec289b2529dbb3390e752519018d5b9786e13930e3c56ab", "https://www.mutiny-guild.com/model/auth_example.php");
//echo "oauth2 instantiated<br />";

if ($oauth2->isRedirected() === false) { // Did the client already logged in ?
    // The parameters can be a combination of the following: connections, email, identity or guilds
    // More information about it here: https://discordapp.com/developers/docs/topics/oauth2#shared-resources-oauth2-scopes
    // The others parameters are not available with this library
    $oauth2->startRedirection(['identify']);
} else {
    // We preload the token to see if everything happened without error
    $ok = $oauth2->loadToken();
    if ($ok !== true) {
        // A common error can be to reload the page because the code returned by Discord would still be present in the URL
        // If this happen, isRedirected will return true and we will come here with an invalid code
        // So if there is a problem, we redirect the user to Discord authentification
        $oauth2->startRedirection(['identify']);
    } else {
        // ---------- USER INFORMATION
        $answer = $oauth2->getUserInformation(); // Same as $oauth2->getCustomInformation('users/@me')
        if (array_key_exists("code", $answer)) {
            exit("An error occured: " . $answer["message"]);
        } else {
            echo "Welcome " . $answer["username"] . "#" . $answer["discriminator"];
        }

        echo '<br/><br/>';
        // ---------- CONNECTIONS INFORMATION
        $answer = $oauth2->getConnectionsInformation();
        if (array_key_exists("code", $answer)) {
            exit("An error occured: " . $answer["message"]);
        } else {
            foreach ($answer as $a) {
                echo $a["type"] . ': ' . $a["name"] . '<br/>';
            }
        }
    }
}
*/
?>