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


//read token
$myfile = fopen("config/token.txt", "r+");
if($myfile){
	$token = fread($myfile,filesize("config/token.txt"));
	fclose($myfile);
}

	$provider = new \Discord\OAuth\Discord([
		'clientId'     => $client_id,
		'clientSecret' => $client_secret   //,
		//'redirectUri'  => 'http://localhost/index.php'
	]);

// if token existed, use the token
if(!empty($token))
{
	echo "use saved token : $token\n";

	// Get the user object.
	$user = $provider->getResourceOwner($token);

	// Get the guilds and connections.
	$guilds = $user->guilds;
	$connections = $user->connections;
	
	var_dump($guilds);
	echo "<br>eof";
}


if (! isset($_GET['code'])) {
	echo '<a href="'.$provider->getAuthorizationUrl().'">Login with Discord</a>';
	//echo "\n".$provider->getAuthorizationUrl()."\n";
} else {
	$token = $provider->getAccessToken('authorization_code', [
		'code' => $_GET['code'],
	]);

	var_dump($token);

	echo "<br />token = $token<br />";

	//test operations

    // Get the guilds and connections.
    // Get the user object.
    $user = $provider->getResourceOwner($token);

    $guilds = $user->guilds;
    $connections = $user->connections;
    echo "<br>";
    var_dump($user);
    echo "<br>";
    var_dump($guilds);
    echo "<br>";
    var_dump($connections);


	//save token
	$myfile = fopen("config/token.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $token);
	fclose($myfile);
	
	// Store the new token.
    echo "<br>";
	echo "done! token savedplease link to "."<a href='discord.php'>discord.php</a>"." again!";
    echo "<br><br><br>EOF<br>";
}
