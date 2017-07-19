<?php

require("vendor/autoload.php");

//read token if exists
$c = fopen("config/token.txt", "r+");
if($c){
    $token_existed = true;

    $access_token = fread($c,filesize("config/token.txt"));
    $token = new \League\OAuth2\Client\Token\AccessToken(array('access_token' => $access_token));
    fclose($c);
}

var_dump($token);

echo "<hr>";

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