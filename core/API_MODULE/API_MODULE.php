<?php
	require_once 'ClientHandler.class.php';
	require_once 'APIRequest.class.php';
	require_once 'APIResponse.class.php';
	require_once 'API.class.php';
	require_once 'APIReply.class.php';
	
	$chatBot->registerInstance($MODULE_NAME, 'API', new API);
?>