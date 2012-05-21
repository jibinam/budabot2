#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject
import gtk

class SystrayController(gobject.GObject):
	""""""

	# Define custom signals that this class can emit.
	__gsignals__ = {
		# this signal is emitted when user attempts to open the control panel
		'open_requested': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
		# this signal is emitted when user attempts to change control panel's visibility
		'toggle_requested': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
		# this signal is emitted when user attempts to exit the application
		'exit_requested': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
	}

	def __init__(self):
		"""Constructor method."""
		self.__gobject_init__()
		self.icon = gtk.StatusIcon()
		self.icon.set_from_stock(gtk.STOCK_FILE)
		self.icon.connect('activate', self.onSystrayClicked)
		self.icon.connect('popup-menu', self.onMenu)
		self.icon.set_visible(True)
		self.icon.set_blinking(False)
		
		# build context menu
		self.contextMenu = gtk.Menu()
		self.contextMenu.connect('enter-notify-event', self.onMouseEnterContextMenu)
		self.contextMenu.connect('leave-notify-event', self.onMouseLeaveContextMenu)
		self.itemOpen = gtk.MenuItem('Open')
		self.itemOpen.set_visible(True)
		self.itemOpen.connect('activate', self.onOpenClicked)
		self.contextMenu.append(self.itemOpen)
		itemExit = gtk.MenuItem('Exit')
		itemExit.set_visible(True)
		itemExit.connect('activate', self.onExitClicked)
		self.contextMenu.append(itemExit)
		
		# set default action as bold
		# TODO: to helper function
		label = self.itemOpen.get_children()
		label = label[0]
		label.set_markup('<b>' + label.get_text() + '</b>')
		
		self.closeTimerId = None

	def onSystrayClicked(self, sender):
		"""This callback handler is called when user clicks the systray icon."""
		self.emit('toggle_requested')

	def onOpenClicked(self, sender):
		"""This callback handler is called when user attempts to open the control panel."""
		self.emit('open_requested')

	def onExitClicked(self, sender):
		"""This callback handler is called when user attempts to exit the application."""
		self.emit('exit_requested')

	def onMouseLeaveContextMenu(self, sender, event):
		"""This callback handler is called when mouse cursor leaves
		right-click-context-menu's area.
		Starts the timeout which closes the context menu automatically.
		"""
		if self.closeTimerId == None:
			self.closeTimerId = gtk.timeout_add(1000, self.closeContextMenu)

	def onMouseEnterContextMenu(self, sender, event):
		"""This callback handler is called when mouse cursor enters
		right-click-context-menu's area.
		Stops the timeout which closes the context menu automatically.
		"""
		if self.closeTimerId != None:
			gtk.timeout_remove(self.closeTimerId)
			self.closeTimerId = None

	def closeContextMenu(self):
		"""This method closes the context menu."""
		self.contextMenu.popdown()
		return False

	def onMenu(self, sender, button, activateTime):
		"""This callback handler is called when popup menu should be shown."""
		#gtk.StatusIcon.position_menu(self.contextMenu, self.icon)
		self.contextMenu.popup(None, None, None, button, activateTime)
		#self.startCloseTimout()

	def onControlPanelVisibilityChanged(self, sender, visibility):
		"""This callback handler is called control panel is either shown or hidden."""
		# disable/enable context menu's open item
		self.itemOpen.set_sensitive(visibility == False)

# register class so that custom signals will work
gobject.type_register(SystrayController)