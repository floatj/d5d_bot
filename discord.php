<?php
require("vendor/autoload.php");

//read clientId and clientSecret
$c = fopen("config/client_id.txt", "r");
if($c){
    $client_id = fread($c,filesize("config/client_id.txt"));
    fclose($c);
}
$c = fopen("config/client_secret.txt", "r");
if($c){
    $client_secret = fread($c,filesize("config/client_secret.txt"));
    fclose($c);
}


//read token if exists
$c = fopen("config/token.txt", "r+");
if($c){
    $token_existed = true;

	$access_token = fread($c,filesize("config/token.txt"));
    $token = new \League\OAuth2\Client\Token\AccessToken(array('access_token' => $access_token));
	fclose($c);
}

	$provider = new \Discord\OAuth\Discord([
		'clientId'     => $client_id,
		'clientSecret' => $client_secret   //,
		//'redirectUri'  => 'http://localhost/index.php'
	]);

// if token existed, use the token
if(!empty($token))
{
	echo "<br/>use saved token : $token<br/>";

	// Get the user object.
	$user = $provider->getResourceOwner($token);

	// Get the guilds and connections.
	$guilds = $user->guilds;
	$connections = $user->connections;

	var_dump($guilds);
	echo "<br>EOF<br>";
}


if (! isset($_GET['code'])) {
	echo '<br/><a href="'.$provider->getAuthorizationUrl().'">Login with Discord</a><br/>';
	//echo "\n".$provider->getAuthorizationUrl()."\n";
} else {
	$token = $provider->getAccessToken('authorization_code', [
		'code' => $_GET['code'],
	]);

	var_dump($token);

	echo "<br />token = $token<br />";


	//test operations
    echo "<br/> <p>Test Operations:</p> <br/>";

    // Get the guilds and connections.
    // Get the user object.

    //下面這行獨立出來要用到
    $user = $provider->getResourceOwner($token);

    $guilds = $user->guilds;
    $connections = $user->connections;
    echo "<br>";
    var_dump($user);
    echo "<br>";
    var_dump($guilds);
    echo "<br>";
    var_dump($connections);


    //save token (first time)
    if(!$token_existed) {

        $myfile = fopen("config/token.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $token);
        fclose($myfile);

        // Store the new token.
        echo "<br/><br/><br/>";
        echo "done! token saved, please link to " . "<a href='discord.php'>discord.php</a>" . " again!";
        echo "<br><br><br>EOF<br>";
    }
}
