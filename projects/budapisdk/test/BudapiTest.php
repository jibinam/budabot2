<?php

require_once 'src/Budapi.php';

require_once 'Mockery/Loader.php';
$loader = new Mockery\Loader;
$loader->register();

class BudapiTest extends PHPUnit_Framework_TestCase {
	/**
	 * This method is called just before executing a testXXX method.
	 */
	public function setup() {
		$this->connectionMock = Mockery::mock('BudapiConnectionMock');
		$this->api = new Budapi($this->connectionMock);
		
		$this->defaultResponse = '{ "status": "'. Budapi::API_SUCCESS .'", "message": "" }';
	}

	/**
	 * This method is called right after executing a testXXX method.
	 */
	public function teardown() {
		// evaluate all mock expectations
		Mockery::close();
	}

	public function testSetHost() {
		$this->api->setHost('example.com');
		$this->assertEquals('example.com', $this->api->getHost());
	}

	public function testGetHostReturnsDefaultValue() {
		$this->assertEquals('localhost', $this->api->getHost());
	}

	public function testSetPort() {
		$this->api->setPort(12345);
		$this->assertEquals(12345, $this->api->getPort());
	}

	public function testGetPortReturnsDefaultValue() {
		$this->assertEquals(5250, $this->api->getPort());
	}
	
	public function testSetUsername() {
		$this->api->setUsername('dummy');
		$this->assertEquals($this->api->getUsername(), 'dummy');
	}

	public function testGetUsernameReturnsEmptyStringByDefault() {
		$this->assertTrue('' === $this->api->getUsername());
	}

	public function testSetPassword() {
		$this->api->setPassword('dummy');
		$this->assertEquals('dummy', $this->api->getPassword());
	}

	public function testGetPasswordReturnsEmptyStringByDefault() {
		$this->assertTrue('' === $this->api->getPassword());
	}
	
	public function testSendCommandRequestsFromCorrectHostAndPort() {
		$this->connectionMock->shouldReceive('request')->with('example.com', 12345, Mockery::any())->andReturn($this->defaultResponse)->once();
		$this->api->setHost('example.com');
		$this->api->setPort(12345);
		$this->api->sendCommand('dummy');
	}

	public function testSendCommandRequestsWithCorrectCredentials() {
		$api = $this->api;
		$checker = function ($apiRequest) use ($api) {
			$apiRequest = json_decode($apiRequest);
			return $apiRequest->username == $api->getUsername() && $apiRequest->password == $api->getPassword();
		};
		$this->connectionMock->shouldReceive('request')->with(Mockery::any(), Mockery::any(), Mockery::on($checker))->andReturn($this->defaultResponse)->once();
		$api->setUsername('dummy');
		$api->setPassword('pass');
		$api->sendCommand('dummycommand');
	}
	
	public function testSendCommandRequestsWithCorrectCommand() {
		$command = 'dummycommand';
		$checker = function ($apiRequest) use ($command) {
			$apiRequest = json_decode($apiRequest);
			return $apiRequest->command == $command;
		};
		$this->connectionMock->shouldReceive('request')->with(Mockery::any(), Mockery::any(), Mockery::on($checker))->andReturn($this->defaultResponse)->once();
		$this->api->sendCommand('dummycommand');
	}

	public function testSendCommandRequestsWithCorrectApiVersion() {
		$checker = function ($apiRequest) {
			$apiRequest = json_decode($apiRequest);
			return $apiRequest->version == Budapi::API_VERSION;
		};
		$this->connectionMock->shouldReceive('request')->with(Mockery::any(), Mockery::any(), Mockery::on($checker))->andReturn($this->defaultResponse)->once();
		$this->api->sendCommand('dummycommand');
	}

	public function testSendSimpleCommand() {
		$checker = function ($apiRequest) {
			$apiRequest = json_decode($apiRequest);
			return $apiRequest->type === Budapi::API_SIMPLE_MSG;
		};
		$this->connectionMock->shouldReceive('request')->with(Mockery::any(), Mockery::any(), Mockery::on($checker))->andReturn($this->defaultResponse)->once();
		$this->api->sendCommand('dummycommand');
	}

	public function testSendCommandReturnsResponseMessage() {
		$this->connectionMock->shouldReceive('request')->andReturn('{ "status": "'. Budapi::API_SUCCESS .'", "message": "dummy response" }');
		$result = $this->api->sendCommand('dummycommand');
		$this->assertEquals('dummy response', $result);
	}

	/**
	 * @expectedException        BudapiServerException
	 * @expectedExceptionMessage dummy error message
	 */
	public function testSendCommandThrowsExceptionOnFailedStatus() {
		$this->connectionMock->shouldReceive('request')->andReturn('{ "status": "'. Budapi::API_FAILURE .'", "message": "dummy error message" }');
		$result = $this->api->sendCommand('dummycommand');
	}

	/**
	 * @expectedException        BudapiSocketException
	 * @expectedExceptionMessage dummy error message
	 */
	public function testSendCommandThrowsExceptionOnConnectionError() {
		$this->connectionMock->errorMessage = 'dummy error message';
		$this->connectionMock->shouldReceive('request')->andReturn(FALSE);
		$result = $this->api->sendCommand('dummycommand');
	}

}