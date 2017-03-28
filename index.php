<?php
require 'vendor/autoload.php';

$config = array(
    'debug' => true,
    'log.enabled' => true,
    'database.file' => "users.txt"
);


$app = new \Slim\Slim($config);

$app->get('/', function () {
    echo "Presentr API";
});

$app->get('/available-users', function () use ($app) {
	$users = $app->loadUsers;

	echo json_encode($users);
});

$app->post('/available-users/:login', function ($login) use ($app) {	
	$databaseFile = $app->config('database.file');

	$user = array("login" => $login);

	$users = $app->loadUsers;
	array_push($users, $user);

	$usersJson = json_encode($users);
	file_put_contents($databaseFile, $usersJson);

	echo json_encode($user);
});

$app->loadUsers = function() use ($app) {
	$databaseFile = $app->config('database.file');

	$fileContents = @file_get_contents($databaseFile);
	if( (!file_exists($databaseFile)) || $fileContents === FALSE || strlen($fileContents) == 0) {
		$users = array();
	} else {
		$users = json_decode($fileContents, /* assoc */ true);
	}	
	return $users;
};

$app->run();


?>