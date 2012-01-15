<?php

require_once dirname(__FILE__) . '/../../src/Budapi.php';
require_once 'Model.class.php';
require_once 'View.class.php';

/**
 * This class acts as a controller for testpage example project.
 */
class Controller {
	
	/**
	 * Constructor method.
	 */
	public function __construct() {
		$this->model = new Model();
		$this->view = new View($this->model);
	}

	/**
	 * Sends command to Budabot bot through its API.
	 * @param $command command name and parameters
	 * @return returned message
	 */
	private function sendRequest($command) {
		$api = new Budapi();
		// set bot's address
		$api->setHost($this->model->server);
		$api->setPort($this->model->port);
		// set credentials
		$api->setUsername($this->model->username);
		$api->setPassword($this->model->password);
		// send command and return returned message
		// this throws an exception on error
		return $api->sendCommand($command);
	}

	/**
	 * Executes the controller.
	 */
	public function execute() {
		try {
			if ($this->model->action == 'Connect') {
				$this->sendRequest('');
				$this->model->login();
				$this->view->message = 'Connection established successfully.';
		
			} else if ($this->model->action == 'Logout') {
				$this->model->logout();
			} else if ($this->model->command && $this->model->username) {
				$this->view->message = $this->sendRequest($this->model->command);
			}
		} catch (Exception $e) {
			$this->view->message = $e->getMessage();
		}
		echo $this->view->render();
	}
}
