#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject

class SettingModel:
	""""""

	def __init__(self):
		"""Constructor method."""
		pass

	def load(self):
		"""This method saves the settings to a file."""
		pass

	def save(self):
		"""This method saves the settings to a file."""
		pass

	def getConfigurationFilePath(self, botName):
		"""This method either shows or hides the dialog."""
		pass

	def getApiPortRangeLow(self):
		""""""
		pass

	def getApiPortRangeHigh(self):
		""""""
		pass

	def getBotNames(self):
		""""""
		pass

	def getSettingsFilePath(self):
		"""Returns path to settings file where this class's data is saved."""
		pass

	def getValue(self, botName, tagName):
		""""""
		pass

	def setGlobalValue(self, tagName, value):
		""""""
		pass
