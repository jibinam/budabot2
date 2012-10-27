<?php

require __DIR__ . '/../vendor/autoload.php';
require_once 'AOChatServer.php';
require_once 'JSONRPCServer.php';

class ServerController implements IAOChatModel {

	public $privateMessages = array();
	public $tellMessages = array();

	private $loop = null;

	public function __construct($loop, $aoServer) {
		$this->loop = $loop;
		$that = $this;

		$this->aoServer = $aoServer;
		$this->aoServer->setModel($this);
		
		$this->aoServer->on('private_message', function($gid, $msg, $blob) use ($that) {
			$that->privateMessages []= $msg;
		});
		$this->aoServer->on('tell_message', function($uid, $msg, $blob) use ($that) {
			$info = $that->aoServer->getCharInfo($uid);
			$that->tellMessages[strtolower($info->name)] []= $msg;
		});
	}

	/**
	 * Sets list of characters that the bot can log in with.
	 * Called by the test runner via JSON-RPC call.
	 */
	public function setAccountCharacters($characters) {
		$this->accountChars = $characters;
	}

	/**
	 * Sends a tell message to the bot.
	 * Called by the test runner via JSON-RPC call.
	 */
	public function sendTellMessage($asName, $message) {
		$this->aoServer->sendTellMessage($asName, $message);
	}

	/**
	 * Waits for given private message from bot.
	 * Throws an exception if the given timeout occurs (in seconds).
	 * Called by the test runner via JSON-RPC call.
	 */
	public function waitPrivateMessage($timeout, $value) {
		$that = $this;
		$result = $this->blockUntil($timeout, function() use ($that, $value) {
			forEach($that->privateMessages as $message) {
				if (stripos($message, $value) !== false) {
					return true;
				}
			}
		});
		if (!$result) {
			throw new Exception("Failed to receive message: $message");
		}
	}

	/**
	 * Sets a character as logged in. 
	 * Called by the test runner via JSON-RPC call.
	 */
	public function buddyLogin($name) {
		$this->aoServer->addBuddy($name, true);
	}

	/**
	 * Clears any tell messages send to given character.
	 * Called by the test runner via JSON-RPC call.
	 */
	public function clearTellMessagesOfCharacter($name) {
		$this->tellMessages[strtolower($name)] = array();
	}

	/**
	 * Sends a tell $message to bot as $asName character.
	 * Called by the test runner via JSON-RPC call.
	 */
	public function sendTellMessageToBot($asName, $message) {
		$this->aoServer->sendTellMessage($asName, $message);
	}

	/**
	 * Returns array of tell messages send to character $name.
	 * Called by the test runner via JSON-RPC call.
	 */
	public function getTellMessagesOfCharacter($name) {
		$name = strtolower($name);
		if (isset($this->tellMessages[$name])) {
			return $this->tellMessages[$name];
		}
		return array();
	}

	/**
	 * Waits until a tell message with given array of phrases have been received.
	 * Throws an exception if $timeout occurs.
	 * Called by the test runner via JSON-RPC call.
	 */
	public function waitForTellMessageWithPhrases($timeout, $phrases) {
		$that = $this;
		$result = $this->blockUntil($timeout, function() use ($that, $phrases) {
			forEach($that->tellMessages as $name => $messages) {
				forEach($messages as $message) {
					forEach($phrases as $phrase) {
						if (stripos($message, $phrase) !== false) {
							return true;
						}
					}
				}
			}
		});
		if (!$result) {
			throw new Exception("Failed to receive tell message with phrases:\n" . print_r($phrases, true));
		}
	}

	public function getAccountCharacters() {
		return $this->accountChars;
	}

	private function blockUntil($timeout, $callback) {
		$endTime = time() + intval($timeout);
		while (time() < $endTime) {
			$result = call_user_func($callback);
			if ($result) {
				return true;
			}
			$this->loop->tick();
		}
		return false;
	}
}

$chatPort = $argv[1];
$rpcPort  = $argv[2];

$loop      = React\EventLoop\Factory::create();

$aoServer   = new AOChatServer($loop, $chatPort);
$controller = new ServerController($loop, $aoServer);
$rpcServer  = new JSONRPCServer($loop, $rpcPort, $controller);

$loop->run();


