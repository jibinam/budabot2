#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject
from gtk import ListStore
from bot import Bot

class BotModel(ListStore):
	""""""
	
	def __init__(self, settingModel):
		"""Constructor method."""
		super(BotModel, self).__init__(gobject.TYPE_PYOBJECT, gobject.TYPE_STRING)
		self.settingModel = settingModel

	def load(self):
		"""Loads configured bots from settings"""
		names = self.settingModel.getBotNames()
		for name in names:
			bot = self.getBotByName(name)
			if bot == None:
				# create new bot object and add it to model
				bot = Bot(name, self.settingModel)
				self.append((bot, bot.getName()))
			# TODO: load bot settings from settingModel...

	def getBotByName(self, name):
		""""""
		# loop through rows and return a bot with given name if found
		for row in self:
			if row[0].getName() == name:
				return row[0]
		return None

	def getAllBots(self):
		"""Returns list of all bots in the model."""
		bots = []
		# collect and return all bots
		for row in self:
			bots.append(row[0])
		return bots

