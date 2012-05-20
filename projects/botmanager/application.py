#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gtk

class Application:
	"""The main application class"""
	
	def execute(self):
		""""""
		gtk.main()

	def quit(self):
		"""Calling this method will stop the event loop and execution returns
		from execute().
		"""
		pass

	def onControlPanelAction(self, action, botName):
		""""""
		pass

	def showErrorMessage(self, message):
		""""""
		pass

	def botWindowController(self, botName):
		""""""
		pass
