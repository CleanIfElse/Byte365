<?php
	require 'vendor/.composer/autoload.php';

	$fb = new \Facebook\Facebook([
	  'app_id' => '131911117569792',
	  'app_secret' => '78bb1f40ce7dff565c40f92476be20e9',
	  'default_graph_version' => 'v2.10',
	  //'default_access_token' => '{access-token}', // optional
	]);

	$data = [
		'message' => 'Testing SadPostBot.'
	];

	$response = $fb->post('/me/photos', $data, '131911117569792|t3aAiCA1fFYplKTI1uY9cMET_tg');

	print 'test';
?>
