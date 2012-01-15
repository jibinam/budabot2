<?php

/**
 * This class acts as a model for testpage example project.
 * Provides easier and more abstract access to variables gotten from input
 * forms and sessions.
 */
class Model {
	/**
	 * Constructor method.
	 */
	public function __construct() {
		session_start();
	}

	/**
	 * Magick method for reading model's member variables.
	 * the value is returned first from POSTed data. If the POST-value doesn't
	 * exist then the value is read from SESSION data. If no data exist there
	 * either then a default value is returned.
	 * If unknown variable is requested then its value will be NULL.
	 */
	public function __get($name) {
		if (isset($_POST[$name])) {
			return $_POST[$name];
		}
		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}
		switch ($name) {
			// return default values
			case 'username': return 'admin';
			case 'password': return 'admin';
			case 'server':   return '127.0.0.1';
			case 'port':     return 5250;
		}
		return NULL;
	}
	
	/**
	 * Stores SESSION data to indicate that user has logged in.
	 */
	public function login() {
		$_SESSION['username'] = isset($_POST['username'])? $_POST['username']: '';
		$_SESSION['password'] = isset($_POST['password'])? $_POST['password']: '';
		$_SESSION['server']   = isset($_POST['server'])? $_POST['server']: '';
		$_SESSION['port']     = isset($_POST['port'])? $_POST['port']: '';
	}

	/**
	 * Clears SESSION data to indicate that user has logged out.
	 */
	public function logout() {
		session_destroy();
		session_start();
	}
	
	/**
	 * Returns TRUE if SESSION data has been set. FALSE otherwise.
	 */
	public function isLoggedIn() {
		return isset($_SESSION['username']) && 
			   isset($_SESSION['password']) &&
			   isset($_SESSION['server']) && 
			   isset($_SESSION['port']);
	}
	
}