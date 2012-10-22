<?php

require 'vendor/autoload.php';

class RpcStub {
	public function __construct($port) {
		$this->client = new JsonRpc\RpcClient("http://127.0.0.1:$port/");
	}
	
	public function __call($method, $arguments) {
		$response = $this->client->__call($method, $arguments);
		if (isset($response->error)) {
			throw new Exception($response->error->message, $response->error->code);
		}
		return $response->result;
	}
}

$serverProcess = proc_open('php server.php', array(), $pipes, null,	null, array('bypass_shell' => true));
if (!is_resource($serverProcess)) {
	print "Failed to start aochat server!";
	return;
}

$stub = new RpcStub(11223);

$result = $stub->setAccountCharacters(array('Uutisankka'));
var_dump($result);

//proc_terminate($serverProcess);
