#! /usr/bin/env python
# -*- coding: utf-8 -*-

class Bot:
	""""""
	
	def __init__(self, name, settingModel):
		"""Constructor method."""
		self.name = name
	
	def getName(self):
		"""Returns name of the bot."""
		return self.name
		
	def getConsoleModel(self):
		"""Returns console model"""
		pass
	
	def start(self):
		"""Starts the bot."""
		pass
	
	def isPortFree(self):
		"""Returns true if given TCP/IP port is free."""
		pass
		
	def restart(self):
		"""Restarts the bot."""
		pass

	def shutdown(self):
		"""Shutdowns the bot."""
		pass

	def terminate(self):
		"""Terminates the bot."""
		pass

	def sendCommand(self, channel, command):
		"""Sends command to the bot process through its API."""
		pass
	
	def onBotStdoutReceived(self, sender, data):
		"""This callback function is called when Budabot sends standard output."""
		pass

	def onBotStderrReceived(self, sender, data):
		"""This callback function is called when Budabot sends standard errors."""
		pass

	def onBotDied(self, sender):
		"""This callback function is called when Budabot is shutdown."""
		pass

	def insertToModel(self, message, tagName=''):
		"""This method adds message to end of GtkTextView's model.
		Optional tag of name tagname is applied to the message.
		"""
		pass
