#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject
import appdirs
from configobj import ConfigObj, ConfigObjError
from validate import Validator
import os

class SettingModel(gobject.GObject):
	""""""

	# Define custom signals that this class can emit.
	__gsignals__ = {
		# this signal is emitted when an error occurs
		'error': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, (gobject.TYPE_STRING,)),
	}

	def __init__(self):
		"""Constructor method."""
		self.__gobject_init__()

	def load(self):
		"""This method loads the settings from a file."""
		self.config = self.readSettingsFile()

	def save(self):
		"""This method saves the settings to a file."""
		if self.config:
			self.config.write()

	def getApiPortRangeLow(self):
		""""""
		if self.config:
			return self.config['common']['apiportrangelow']
		return None

	def getApiPortRangeHigh(self):
		""""""
		if self.config:
			return self.config['common']['apiportrangehigh']
		return None

	def getBotNames(self):
		"""Returns a list of bot names."""
		names = []
		if self.config:
			for name in self.config['bots']:
				names.append(name)
			return self.config['bots']
		return names

	def readSettingsFile(self):
		"""Reads and returns settings object."""
		config = None
		# get a path to the ini-file + create directory for it
		configDir = appdirs.user_data_dir('Budabot Bot Manager', 'budabot.com', '1.0')
		if not os.path.exists(configDir):
			os.makedirs(configDir)
		configPath = os.path.join(configDir, 'settings.ini')
		# load the ini-file
		try:
			config = ConfigObj(infile = configPath, create_empty = True, encoding = 'UTF8', configspec = 'settingsspec.ini')
		except(ConfigObjError, IOError), e:
			self.emit('error', 'Failed to read settings from "%s": %s' % (configPath, e))
			return None
		# validate the ini-file
		validator = Validator()
		results = config.validate(validator)
		if results != True:
			message = 'Failed to read settings from "%s":\n' % configPath
			for (sectionList, key, _) in flatten_errors(config, results):
				if key is not None:
					message += 'The "%s" key in the section "%s" failed validation\n' % (key, ', '.join(sectionList))
				else:
					message += 'The following section was missing: %s\n' % ', '.join(sectionList)
			self.emit('error', message)
			return None
		return config

	def getValue(self, botName, name):
		""""""
		if self.config:
			return self.config[botName][name]
		return None

# register class so that custom signals will work
gobject.type_register(SettingModel)

