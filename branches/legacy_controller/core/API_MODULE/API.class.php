<?php

class API {

	/** @Inject */
	public $command;
	
	/** @Inject */
	public $preferences;
	
	/** @Inject */
	public $chatBot;
	
	/** @Inject */
	public $setting;
	
	/** @Logger */
	public $logger;

	/** @Inject */
	public $accessLevel;

	/**
	 * @Setting("api_port")
	 * @Description("Port number to listen for API requests")
	 * @Visibility("edit")
	 * @Type("number")
	 * @Options("5250")
	 */
	public $defaultAPIPort = "5250";
	
	private $apisocket;

	/**
	 * @Event("connect")
	 * @Description("Opens a socket to listen for API requests")
	 * @DefaultStatus("0")
	 */
	public function connect($eventObj) {
		// bind to port 5250 on any address
		$address = '0.0.0.0';
		$port = $this->setting->get('api_port');

		// Create a TCP Stream socket
		$this->apisocket = socket_create(AF_INET, SOCK_STREAM, 0);
		socket_bind($this->apisocket, $address, $port);
		$errno = socket_last_error();
		if ($errno == 0) {
			$this->logger->log('INFO', 'API socket bound successfully');
		} else {
			$this->logger->log('ERROR', socket_strerror($errno));
		}
		socket_listen($this->apisocket);
		socket_set_nonblock($this->apisocket);
	}
	
	/**
	 * @Event("2sec")
	 * @Description("Checks for and processes API requests")
	 * @DefaultStatus("0")
	 */
	public function listen($eventObj) {
		/* Accept incoming requests and handle them as child processes */
		$client = @socket_accept($this->apisocket);
		if ($client !== false) {
			$clientHandler = new ClientHandler($client);

			// Read the input from the client
			$apiRequest = $clientHandler->readPacket();
			if ($apiRequest->version != API_VERSION) {
				$clientHandler->writePacket(new APIResponse(API_INVALID_VERSION, "API version must be: " . API_VERSION));
			}
			
			$password = $this->preferences->get($apiRequest->username, 'apipassword');
			if ($password === false) {
				$clientHandler->writePacket(new APIResponse(API_UNSET_PASSWORD, "Password has not been set for this user."));
			} else if ($password != $apiRequest->password) {
				$clientHandler->writePacket(new APIResponse(API_INVALID_PASSWORD, "Password was incorrect."));
			} else {
				if ($apiRequest->type == API_SIMPLE_MSG) {
					$type = 'msg';
					$apiReply = new APISimpleReply();
				} else if ($apiRequest->type == API_ADVANCED_MSG) {
					$type = 'api';
					$apiReply = new APIAdvancedReply();
				} else {
					$clientHandler->writePacket(new APIResponse(API_INVALID_REQUEST_TYPE, "Invalid request type."));
					return;
				}

				try {
					$responseCode = $this->process($type, $apiRequest->command, $apiRequest->username, $apiReply);
					$response = new APIResponse($responseCode, $apiReply->getOutput());
				} catch (APIException $e) {
					$response = new APIResponse(API_EXCEPTION, $e->getResponseMessage());
				} catch (Exception $e) {
					$response = new APIResponse(API_EXCEPTION, $e->getMessage());
				}
				$clientHandler->writePacket($response);
			}
		}
	}
	
	private function process($channel, $message, $sender, $sendto) {
		list($cmd, $params) = explode(' ', $message, 2);
		$cmd = strtolower($cmd);
		
		$commandHandler = $this->command->getActiveCommandHandler($cmd, $channel, $message);
		
		// if command doesn't exist
		if ($commandHandler === null) {
			$this->chatBot->spam[$sender] += 20;
			return API_UNKNOWN_COMMAND;
		}

		// if the character doesn't have access
		if ($this->accessLevel->checkAccess($sender, $commandHandler->admin) !== true) {
			$this->chatBot->spam[$sender] += 20;
			return API_ACCESS_DENIED;
		}

		// record usage stats
		if ($this->setting->get('record_usage_stats') == 1) {
			Registry::getInstance('usage')->record($channel, $cmd, $sender, $commandHandler);
		}
	
		$syntaxError = $this->command->callCommandHandler($commandHandler, $message, $channel, $sender, $sendto);
		$this->chatBot->spam[$sender] += 10;
		
		if ($syntaxError === true) {
			return API_SYNTAX_ERROR;
		} else {
			return API_SUCCESS;
		}
	}
	
	/**
	 * @Command("apipassword")
	 * @AccessLevel("mod")
	 * @Description("Set your api password")
	 * @Matches("/^apipassword (.*)$/i")
	 */
	public function apipasswordCommand($message, $channel, $sender, $sendto, $arr) {
		$this->preferences->save($sender, 'apipassword', $arr[1]);
		$sendto->reply("Your API password has been updated successfully.");
	}
}

?>