<?php

class Budapi {

	// api request version
	const API_VERSION = 1.1;
	
	// request types
	const API_SIMPLE_MSG = 0;
	const API_ADVANCED_MSG = 1;
	
	// response status codes
	const API_SUCCESS = 1;
	const API_FAILURE = 0;

	private $host;
	private $port;
	private $username;
	private $password;
	
	/**
	 * Constructor method of the class.
	 */
	public function __construct($connection = NULL) {
		// set default values
		$this->port = 5250;
		$this->host = 'localhost';
		$this->username = '';
		$this->password = '';
		$this->connection = $connection;
		if (!$this->connection) {
			$this->connection = new BudapiConnection();
		}
	}

	/**
	 * This method returns bot's ip port.
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * This method sets port of the bot where to connect.
	 * @param $port bot's ip port
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 * This method returns bot's address.
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * This method sets bot's host address.
	 * @param $host bot's address (IP or hostname)
	 */
	public function setHost($host) {
		$this->host = $host;
	}

	/**
	 * This method returns name of the user.
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * This method sets name of an user who will access the bot.
	 * @param $name user's name
	 */
	public function setUsername($name) {
		$this->username = $name;
	}

	/**
	 * This method returns password of the user.
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * This method sets password of an user who will access the bot.
	 * @param $pass user's password
	 */
	public function setPassword($pass) {
		$this->password = $pass;
	}
	
	/**
	 * This method sends a command to Budabot bot through its API.
	 *
	 * Throws BudapiSocketException if an error occurs while connecting,
	 * sending or reading data from the socket.
	 * Throws BudapiServerException if returned message's status is not
	 * API_SUCCESS.
	 *
	 * @param $command command's name and parameters
	 * @return returned message
	 */
	public function sendCommand($command, $payload = NULL) {
		$request = new StdClass();
		$request->version  = self::API_VERSION;
		$request->syncId   = 0; // TODO: what is this?
		$request->type     = self::API_SIMPLE_MSG; // TODO: check what type should be used from $payload
		$request->username = $this->getUsername();
		$request->password = $this->getPassword();
		$request->command  = $command;
		$request = json_encode($request);
		$response = $this->connection->request($this->getHost(), $this->getPort(), $request);
		if ($response === FALSE) {
			throw new BudapiSocketException($this->connection->errorMessage);
		}
		$responseObject = json_decode($response);
		// throw error if status isn't a success
		if ($responseObject->status != self::API_SUCCESS) {
			throw new BudapiServerException($responseObject->message, $responseObject->status);
		}
		return $responseObject->message;
	}
}

/**
 * This class provides functionality for socket handling. This class
 * should be kept as simple as possible as it cannot be easily unit tested.
 */
class BudapiConnection {

	public $errorMessage;
	
	public function request($host, $port, $input) {
		$output = FALSE;
		try {
			$socket = @fsockopen($host, $port, $code, $error, 5);
			if ($socket === FALSE) {
				throw new Exception($error);
			}
			if (@fputs($socket, pack("n", strlen($input))) === FALSE) {
				throw new Exception('Failed to write to socket');
			}
			if (@fputs($socket, $input) === FALSE) {
				throw new Exception('Failed to write to socket');
			}
			$size = @fread($socket, 2);
			if ($size === FALSE) {
				throw new Exception('Failed to read from socket');
			}
			$size = @array_pop(@unpack("n", $size));
			$output = '';
			// read $size amount of bytes from socket
			for ($bytesLeft = $size; $bytesLeft > 0; $bytesLeft -= strlen($bytes)) {
				$readLength = min($bytesLeft, 1024);
				$bytes = @fread($socket, $readLength);
				if ($bytes === FALSE) {
					throw new Exception('Failed to read from socket');
				}
				$output .= $bytes;
			}
		} catch(Exception $e) {
			$this->errorMessage = $e->getMessage();
		}
		@fclose($socket);
		return $output;
	}
}

class BudapiServerException extends Exception {
}

class BudapiSocketException extends Exception {
}
