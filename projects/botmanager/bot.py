#! /usr/bin/env python
# -*- coding: utf-8 -*-

import budapi
from process import Process
from botconfigfile import BotPhpConfigFile
import pango
import socket
import gtk
import re

class Bot:
	""""""

	def __init__(self, name, settingModel):
		"""Constructor method."""
		self.name = name
		self.settingModel = settingModel
		self.api = budapi.Budapi()
		self.process = Process()
		self.consoleModel = gtk.TextBuffer()
		self.configFile = None
		self.noRestart = False

		self.process.connect('stdout_received', self.onBotStdoutReceived)
		self.process.connect('stderr_received', self.onBotStderrReceived)
		self.process.connect('stopped', self.onBotDied)

		tagTable = self.consoleModel.get_tag_table()

		# create a red bolded error tag
		# TODO: to helper function
		errorTag = gtk.TextTag('error')   
		errorTag.set_property('foreground', 'red')
		errorTag.set_property('weight', pango.WEIGHT_BOLD)
		tagTable.add(errorTag)

		# create a blue response tag
		# TODO: to helper function
		errorTag = gtk.TextTag('response')   
		errorTag.set_property('foreground', 'lightblue')
		tagTable.add(errorTag)

	def getName(self):
		"""Returns name of the bot."""
		return self.name

	def getConsoleModel(self):
		"""Returns console model"""
		return self.consoleModel

	def start(self):
		"""Starts the bot."""
		# do nothing if bot process is still running.
		if self.process.isRunning():
			return

		configPath = self.settingModel.getValue(self.name, 'configfile')
		self.configFile = BotPhpConfigFile(configPath)
		self.configFile.load()
		port = self.configFile.getVar('API Port')

		# make sure that port is within defined range
		lowPort  = self.settingModel.getApiPortRangeLow()
		highPort = self.settingModel.getApiPortRangeHigh()
		if port < lowPort or port > highPort:
			port = lowPort
			self.configFile.setVar('API Port', port)
			self.configFile.save()

		# find a free port if currently set port is not free
		if self.isPortFree(port) == False:
			for port in range(low, high + 1):
				if self.isPortFree(port):
					self.configFile.setVar('API Port', port)
					self.configFile.save()
					break

		self.noRestart = False
		self.process.setConfigFilePath(configPath)
		self.process.setWorkingDirectoryPath(self.settingModel.getValue(self.name, 'installdir'))
		self.process.start()

	def isPortFree(self, port):
		"""Returns True if given TCP/IP port is free."""
		s = None
		try:
			s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
			s.bind(('0.0.0.0', port))
			return True
		except socket.error, e:
			# TODO: ignore error which indicates that port is in use
			self.emit('error', 'Failed to check if port is free, error was: ' + e)
			try:
				s.close()
			except:
				pass
		return False

	def restart(self):
		"""Restarts the bot."""
		self.sendCommand(0, 'restart')

	def shutdown(self):
		"""Shutdowns the bot."""
		self.noRestart = True
		self.sendCommand(0, 'shutdown')

	def terminate(self):
		"""Terminates the bot."""
		self.noRestart = True
		self.process.stop()

	def sendCommand(self, channel, command):
		"""Sends command to the bot process through its API."""
		if self.configFile == None:
			return
		# do nothing if the bot process is not running.
		if self.process.isRunning() == False:
			return
		# prefix command depending of channel
		if channel == 1: # org channel
			command = 'say org ' + command
		elif channel == 2: # private channel
			command = 'say priv ' + command
		# pull API settings from setting model
		self.api.setUsername(self.configFile.getVar('SuperAdmin'))
		self.api.setPassword('')
		self.api.setHost('127.0.0.1')
		self.api.setPort(self.configFile.getVar('API Port'))
		# send command
		self.api.sendCommand(command).addCallbacks(self.onCommandSuccess, self.onCommandFailed)

	def onCommandSuccess(self, response):
		""""""
		self.insertToModel(response + "\n", 'response')

	def onCommandFailed(self, failure):
		""""""
		r = failure.trap(budapi.BudapiServerException, Exception)
		if r == budapi.BudapiServerException:
			message = None
			errorMessage = failure.value.args[0]
			errorStatus = failure.value.args[1]
			if errorStatus == budapi.API_UNSET_PASSWORD or errorStatus == budapi.API_INVALID_PASSWORD:
				message = "Your credentials are incorrect, make sure you have set your API password with command 'apipassword'\n"
			elif errorStatus == budapi.API_ACCESS_DENIED:
				message = "Access denied! You have don't have permissions to execute this command\n"
			elif errorStatus == budapi.API_UNKNOWN_COMMAND:
				message = "Failed to sent the message, the command was not found\n"
			elif errorStatus == budapi.API_SYNTAX_ERROR:
				message = "Failed to sent the message, there was a syntax error with your command\n"
			else:
				message = "Server sent error status: " + errorStatus + ", with message: " + errorMessage + "\n"
			self.insertToModel(message, 'error')
		elif r == Exception:
			self.insertToModel(str(failure.value) + "\n", 'error')

	def onBotStdoutReceived(self, sender, data):
		"""This callback function is called when Budabot sends standard output."""
		self.insertToModel(data)
		if re.search("^The bot is shutting down.$", data, re.IGNORECASE):
			self.noRestart = True

	def onBotStderrReceived(self, sender, data):
		"""This callback function is called when Budabot sends standard errors."""
		self.insertToModel(data, 'error')

	def onBotDied(self, sender):
		"""This callback function is called when Budabot is shutdown."""
		# restart the bot if needed
		if (self.noRestart == False):
			self.insertToModel("Restarting the bot\n")
			self.start()

	def insertToModel(self, message, tagName=''):
		"""This method adds message to end of gtk.TextView's model.
		Optional tag of name tagName is applied to the message.
		"""
		if (message):
			start = self.consoleModel.get_char_count()
			self.consoleModel.insert(self.consoleModel.get_iter_at_offset(start), message)
			end = self.consoleModel.get_char_count()
			# wrap the text to given tag if needed
			if tagName:
				self.consoleModel.apply_tag_by_name(tagName, self.consoleModel.get_iter_at_offset(start), self.consoleModel.get_iter_at_offset(end))

