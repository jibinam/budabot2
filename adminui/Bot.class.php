<?php

require_once 'Process.class.php';
require_once 'budapi/Budapi.php';
// load Budabot's ConfigFile class
require_once dirname(__FILE__) . '/../core/ConfigFile.class.php';

class Bot {

	private $name;
	private $settingModel;
	private $consoleModel;

	public function __construct($name, $settingModel) {
		$this->name = $name;
		$this->settingModel = $settingModel;
		$this->api = new Budapi();
		$this->process = new Process();
		$this->consoleModel = new GtkTextBuffer();

		$this->process->connect('stdout_received', array($this, 'onBotStdoutReceived'));
		$this->process->connect('stderr_received', array($this, 'onBotStderrReceived'));
		$this->process->connect_simple('stopped', array($this, 'onBotDied'));

		$tagTable = $this->consoleModel->get_tag_table();

		// create a red bolded error tag
		$errorTag = new GtkTextTag('error');   
		$errorTag->set_property('foreground', 'red');
		$errorTag->set_property('weight', Pango::WEIGHT_BOLD);
		$tagTable->add($errorTag);

		// create a blue response tag
		$errorTag = new GtkTextTag('response');   
		$errorTag->set_property('foreground', 'lightblue');
		$tagTable->add($errorTag);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getConsoleModel() {
		return $this->consoleModel;
	}
	
	public function start() {
		$configPath = $this->settingModel->getConfigurationFilePath($this->name);
		$configFile = new ConfigFile($configPath);
		$configFile->load();
		$port = $configFile->getVar('API Port');
		
		// find a free port if currently set port is not free and update
		// the config file
		if (!$this->isPortFree($port)) {
			$low = $this->settingModel->getApiPortRangeLow();
			$high = $this->settingModel->getApiPortRangeHigh();
			for($port = $low; $port <= $high; $port++) {
				if ($this->isPortFree($port)) {
					$configFile->setVar('API Port', $port);
					$configFile->save();
					break;
				}
			}
		}

		$this->process->start();
	}
	
	/**
	 * Returns true if given TCP/IP port is free.
	 */
	private function isPortFree($port) {
		$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket === false) {
			trigger_error('Failed to create a socket, error was: ' . socket_strerror(socket_last_error()), E_USER_WARNING);
			return false;
		}
		if (@socket_bind($socket, '0.0.0.0', $port) === false) {
			$errorCode = socket_last_error();
			// show error only if the failure was caused something else except
			// that some other process has the $port already in use
			if ($errorCode != 10048) {
				trigger_error('Failed to bind a socket, error was: ' . socket_strerror($errorCode), E_USER_WARNING);
			}
			socket_close($socket);
			return false;
		}
		socket_close($socket);
		return true;
	}
	
	public function restart() {
		$this->sendCommand(0, 'restart');
	}
	
	public function shutdown() {
		$this->sendCommand(0, 'shutdown');
	}
	
	public function terminate() {
		$this->process->stop();
	}
	
	public function sendCommand($channel, $command) {

		switch ($channel) {
		case 1: // org channel
			$command = 'say org ' . $command;
			break;
		case 2: // private channel
			$command = 'say priv ' . $command;
			break;
		}
		
		// pull API settings from setting model
		$this->api->setUsername($this->settingModel->getApiUsername($this->name));
		$this->api->setPassword($this->settingModel->getApiPassword($this->name));
		$this->api->setHost($this->settingModel->getApiHost($this->name));
		$this->api->setPort($this->settingModel->getApiPort($this->name));
		
		// send command and handle errors that might occur
		try {
			$response = $this->api->sendCommand($command);
			$this->insertToModel($response . "\n", 'response');
		}
		catch (BudapiServerException $e) {
			$message = "Server sent error code: " . $e->getCode() . "\n";
			switch ($e->getCode()) {
			case Budapi::API_UNSET_PASSWORD:
			case Budapi::API_INVALID_PASSWORD:
				$message = "Your credentials are incorrect, make sure you have set your API password with command 'apipassword'\n";
				break;

			case Budapi::API_ACCESS_DENIED:
				$message = "Access denied! You have don't have permissions to execute this command\n";
				break;

			case Budapi::API_UNKNOWN_COMMAND:
				if ($destinationType == 1) {
					$message = "Failed to sent the message, make sure that 'say org' command is enabled\n";
				}
				else if ($destinationType == 2) {
					$message = "Failed to sent the message, make sure that 'say priv' command is enabled\n";
				}
				else { // to chatbot
					$message = "Failed to sent the message, the command was not found\n";
				}
				break;
				
			case Budapi::API_SYNTAX_ERROR:
				$message = "Failed to sent the message, there was a syntax error with your command\n";
				break;
			}
			$this->insertToModel($message, 'error');
		}
		catch (Exception $e) {
			$this->insertToModel($e->getMessage() . "\n", 'error');
		}
	}
	
	/**
	 * This callback function is called when Budabot sends standard output.
	 */
	public function onBotStdoutReceived($object, $data) {
		$this->insertToModel($data);
	}

	/**
	 * This callback function is called when Budabot sends standard errors.
	 */
	public function onBotStderrReceived($object, $data) {
		$this->insertToModel($data, 'error');
	}

	/**
	 * This callback function is called when Budabot is shutdown.
	 */
	public function onBotDied() {
		$this->insertToModel("Oops! The bot just went dead!\n", 'error');
	}
	
	/**
	 * This method adds @a $message to end of GtkTextView's model.
	 * Optional tag of name @a $tagname is applied for the @a $message.
	 */
	private function insertToModel($message, $tagname = '') {
		if ($message) {
			$start = $this->consoleModel->get_char_count();
			$this->consoleModel->insert($this->consoleModel->get_iter_at_offset($start), $message);
			$end = $this->consoleModel->get_char_count();
			
			// wrap the text to given tag if needed
			if ($tagname) {
				$this->consoleModel->apply_tag_by_name($tagname, $this->consoleModel->get_iter_at_offset($start), $this->consoleModel->get_iter_at_offset($end));
			}
		}
	}
}
