#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gtk

class AddBotWizardController:
	""""""
	
	def __init__(self, botModel):
		# load addbotwizard.glade file
		self.builder = gtk.Builder()
		self.builder.add_from_file('addbotwizard.glade')

		self.selectActionPage = self.builder.get_object('selectActionPage')
		self.selectImportPage = self.builder.get_object('selectImportPage')
		self.addBotRadioButton = self.builder.get_object('addBotRadioButton')
		self.importBotRadioButton = self.builder.get_object('importBotRadioButton')
		self.importBotRadioButton.set_group(self.addBotRadioButton)

		self.assistant = gtk.Assistant()
		self.assistant.set_forward_page_func(self.getNextPage)
		self.assistant.connect('apply', self.onApplyClicked)
		self.assistant.connect('cancel', self.onCancelClicked)

		self.SELECT_ACTION_PAGE_INDEX = self.assistant.append_page(self.selectActionPage)
		self.assistant.set_page_title(self.selectActionPage, "Add or import bot")
		self.assistant.set_page_type(self.selectActionPage, gtk.ASSISTANT_PAGE_INTRO)
		self.assistant.set_page_complete(self.selectActionPage, True)

		self.SELECT_IMPORT_PAGE_INDEX = self.assistant.append_page(self.selectImportPage)
		self.assistant.set_page_title(self.selectImportPage, "Import existing bot")
		self.assistant.set_page_type(self.selectImportPage, gtk.ASSISTANT_PAGE_CONTENT)
		self.assistant.set_page_complete(self.selectImportPage, True)

	def show(self):
		"""This method shows the wizard to user."""
		self.assistant.show_all()

	def hide(self):
		"""This method hides the wizard from user."""
		self.assistant.hide()

	def getNextPage(self, currentPageIndex):
		"""This method is called by GtkAssistant's implementation to determine
		to which page index the wizard should change when user clicks
		forward-button. -1 means error.
		"""
		if currentPageIndex == self.SELECT_ACTION_PAGE_INDEX:
			if self.addBotRadioButton.get_property('active'):
				return -1
			elif self.importBotRadioButton.get_property('active'):
				return self.SELECT_IMPORT_PAGE_INDEX
			# no radio button selected, wtf?
			return -1
		elif currentPageIndex == self.SELECT_IMPORT_PAGE_INDEX:
			return -1

	def onApplyClicked(self, caller):
		""""""
		print 'apply clicked'

	def onCancelClicked(self, caller):
		""""""
		self.hide()
