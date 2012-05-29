#! /usr/bin/env python
# -*- coding: utf-8 -*-

import json
import struct
from twisted.internet import defer
from twisted.internet.protocol import Protocol, ClientFactory

# api request version
API_VERSION = '1.2'

# request types
API_SIMPLE_MSG = 0
API_ADVANCED_MSG = 1

# response status codes
API_SUCCESS = 0
API_INVALID_VERSION = 1
API_UNSET_PASSWORD = 2
API_INVALID_PASSWORD = 3
API_INVALID_REQUEST_TYPE = 4
API_UNKNOWN_COMMAND = 5
API_ACCESS_DENIED = 6
API_SYNTAX_ERROR = 7
API_EXCEPTION = 8

class Budapi(object):
	""""""

	def __init__(self):
		"""Constructor method."""
		self.port = 5250
		self.host = '127.0.0.1'
		self.username = None
		self.password = None

	def getPort(self):
		"""This method returns bot's ip port."""
		return self.port

	def setPort(self, port):
		"""This method sets port of the bot where to connect."""
		self.port = port

	def getHost(self):
		"""This method returns bot's address."""
		return self.host

	def setHost(self, host):
		"""This method sets bot's host address."""
		self.host = host

	def getUsername(self):
		"""This method returns name of the user."""
		return self.username

	def setUsername(self, name):
		"""This method sets name of an user who will access the bot."""
		self.username = name

	def getPassword(self):
		"""This method returns password of the user."""
		return self.password

	def setPassword(self, password):
		"""This method sets password of an user who will access the bot."""
		self.password = password

	def sendCommand(self, command, payload = None):
		"""This method sends a command to Budabot bot through its API.
		Returns twisted deferred.
		"""
		factory = BudapiClientFactory(self.username, self.password, command)
		from twisted.internet import reactor
		reactor.connectTCP(self.host, self.port, factory)
		return factory.deferred

class BudapiException(Exception):
	""""""

class BudapiServerException(BudapiException):
	""""""

class BudapiSocketException(BudapiException):
	""""""

class BudapiProtocol(Protocol):
	""""""
	def connectionMade(self):
		request = {
			'version': API_VERSION,
			'username': self.factory.username,
			'password': self.factory.password,
			'command': self.factory.command,
			'type': API_SIMPLE_MSG,
			'syncId': 0
		}
		requestJson = json.dumps(request)
		data = '%s%s' % (struct.pack('!H', len(requestJson)), requestJson)
		print data
		self.transport.write(data)

	def dataReceived(self, data):
		print data

class BudapiClientFactory(ClientFactory):
	"""A factory for BudapiProtocols."""
	protocol = BudapiProtocol

	def __init__(self, username, password, command):
		self.deferred = defer.Deferred()
		self.username = username
		self.password = password
		self.command  = command
