#! /usr/bin/env python
# -*- coding: utf-8 -*-

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
		pass

	def getPort(self):
		"""This method returns bot's ip port."""
		pass

	def setPort(self, port):
		"""This method sets port of the bot where to connect."""
		pass

	def getHost(self):
		"""This method returns bot's address."""
		pass

	def setHost(self, host):
		"""This method sets bot's host address."""
		pass

	def getUsername(self):
		"""This method returns name of the user."""
		pass

	def setUsername(self, name):
		"""This method sets name of an user who will access the bot."""
		pass

	def getPassword(self):
		"""This method returns password of the user."""
		pass

	def setPassword(self, password):
		"""This method sets password of an user who will access the bot."""
		pass

	def sendCommand(self, command, payload = None):
		"""This method sends a command to Budabot bot through its API.
		Throws BudapiSocketException if an error occurs while connecting,
		sending or reading data from the socket.
		Throws BudapiServerException if returned message's status is not
		API_SUCCESS.
		"""
		return ''

class BudapiException(Exception):
	""""""

class BudapiServerException(BudapiException):
	""""""

class BudapiSocketException(BudapiException):
	""""""
