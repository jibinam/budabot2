#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject

class Process(gobject.GObject):
	"""The Process class executes new Budabot processes."""
	
	# Define custom signals that this class can emit.
	__gsignals__ = {
		# emitted when the process has finished executing
		'stopped': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
		# emitted when the process sends data to standard output
		'stdout_received': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, (gobject.TYPE_STRING,)),
		# emitted when the process sends data to standard error
		'stderr_received': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, (gobject.TYPE_STRING,)),
	}
	
	def __init__(self):
		"""Constructor method."""
		self.super(Process, self).__init__()
	
	def setParameters(self, parameters):
		"""Sets a string of parameters which are passed to php-executable when
		the process is started.
		"""
		pass
		
	def start(self):
		"""Calling this method will start the bot as its own process.
		When the bot is running its stdout and stderr is emitted
		with 'stdout_received' and 'stderr_received' signals.
		
		Call stop() to terminate the process.
		"""
		pass
	
	def stop(self):
		"""Calling this method terminates the running bot process.
		Emits 'stopped' when finished.
		"""
		pass
		
	def isRunning(self):
		"""Returns true if the process is running."""
		pass

	def readStdout(self):
		"""Reads data from process's STDOUT and emits the data through
		'stdout_received' signal.
		This method is called automatically by a timer when process
		is running.
		"""
		pass

	def readStderr(self):
		"""Reads data from process's STDERR and emits the data through
		'stderr_received' signal.
		This method is called automatically by a timer when process
		is running.
		"""
		pass

	def checkIfDead(self):
		"""Checks if the process is currently running or not, calls stop() if not.
		This method is called automatically by a timer when process is running.
		"""
		pass

	def readNewContent(self, file):
		"""Reads any new content from given file and returns it to caller."""
		pass

	def createTempFile(self):
		"""Creates a temp file and returns its info back to caller."""
		pass

	def reset(self):
		"""Stops any polling timers, closes handles and terminates the running
		process if any and resets values back to default.
		"""
		pass

# register class so that custom signals will work
gobject.type_register(Process)
