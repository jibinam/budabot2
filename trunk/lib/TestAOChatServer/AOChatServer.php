<?php

require_once 'AOChatServerPacket.php';

use Evenement\EventEmitter;

interface IAOChatModel {
	public function getAccountCharacters();
}

class AOChatServer extends EventEmitter {

	private $serverSocket;

	private $charsInfo = array();

	public function __construct($loop, $port) {
		$this->serverSocket = new React\Socket\Server($loop);
		$that = $this;

		$this->serverSocket->on('connection', function ($conn) use ($that) {
			print "Client connects\n";
			$conn->write($that->packetToData(new AOChatServerPacket('out', AOCP_LOGIN_SEED, 'testloginseed')));

			$conn->on('data', function ($data) use ($conn, $that) {
				if (strlen($data) < 4) {
					print 'Error: Packet length not available\n';
					$conn->close();
					return;
				}
				$head = substr($data, 0, 4);
				list(, $type, $len) = unpack('n2', $head);

				$data = substr($data, 4);

				$packet = new AOChatServerPacket('in', $type, $data);
				$that->emit('packet', array($conn, $packet));
			});
		});
		
		$this->on('packet', function ($conn, $packet) use ($that) {
			switch ($packet->type) {
				case AOCP_LOGIN_REQUEST:
					print "Client requests list of characters on the account when logging in\n";
					$data = array(array(), array(), array(), array());
					forEach ($that->model->getAccountCharacters() as $name) {
						$info = $that->getCharInfo($name);
						$data[0] []= $info->id;
						$data[1] []= $info->name;
						$data[2] []= $info->level;
						$data[3] []= $info->online;
					}
					$response = new AOChatServerPacket('out', AOCP_LOGIN_CHARLIST, $data);
					$conn->write($that->packetToData($response));
					break;

				case AOCP_LOGIN_SELECT:
					$id = $packet->args[0];
					$info = $that->getCharInfo($id);
					print "Client logs in with character (id: $id): {$info->name}\n";

					$response = new AOChatServerPacket('out', AOCP_LOGIN_OK, null);
					$conn->write($that->packetToData($response));

					$data = array(
						$info->id,
						$info->name
					);
					$response = new AOChatServerPacket('out', AOCP_CLIENT_NAME, $data);
					$conn->write($that->packetToData($response));
					break;

				case AOCP_CLIENT_LOOKUP:
					$name = $packet->args[0];
					$info = $that->getCharInfo($name);
					$data = array(
						$info->id,
						$info->name
					);
					print "Client looks up user {$name}'s id: {$info->id}\n";
					$response = new AOChatServerPacket('out', AOCP_CLIENT_LOOKUP, $data);
					$conn->write($that->packetToData($response));
					break;

				case AOCP_PRIVGRP_MESSAGE:
					list($gid, $msg, $blob) = $packet->args;
					print "Client sends private group message (gid: $gid): $msg, blob: $blob\n";
					break;

				case AOCP_BUDDY_ADD:
					list($uid, $type) = $packet->args;
					$info = $that->getCharInfo($uid);
					print "Client adds buddy with id $uid (type: $type, name: {$info->name})\n";
					break;

				case AOCP_PING:
					print "Client sends ping message: {$packet->args[0]}\n";
					$response = new AOChatServerPacket('out', AOCP_PING, $packet->args[0]);
					$conn->write($that->packetToData($response));
					break;

				default:
					print "Error: Client sends unknown packet type (type: {$packet->type})\n";
					var_dump($packet);
					$conn->close();
					break;
			}
		});
		
		$this->serverSocket->listen($port);
	}

	public function setModel(IAOChatModel $model) {
		$this->model = $model;
	}

	public function sendTellMessage() {
	}

	public function getCharInfo($char) {
		if (!is_numeric($char)) {
			forEach ($this->charsInfo as $info) {
				if ($info->name == $char) {
					return $info;
				}
			}
			$info = new StdClass();
			$info->name   = $char;
			$info->id     = mt_rand(1, 0x7FFFFFFF);
			$info->level  = mt_rand(1, 220);
			$info->online = false;
			$this->charsInfo []= $info;
		} else {
			forEach ($this->charsInfo as $info) {
				if ($info->id == $char) {
					return $info;
				}
			}
			$info = new StdClass();
			$info->name   = "UNKNOWN";
			$info->id     = $char;
			$info->level  = mt_rand(1, 220);
			$info->online = false;
			$this->charsInfo []= $info;
		}
		return $info;
	}

	/**
	 * Converts AOChatServerPacket into a data string.
	 * Copy-pasted from AOChat's send_packet() method.
	 */
	public function packetToData($packet) {
		$data = pack("n2", $packet->type, strlen($packet->data)) . $packet->data;
		return $data;
	}
}
