#! /usr/bin/env python
# -*- coding: utf-8 -*-

import weakref

class CallbackWrapper(object):
	"""
	Wrapper for callbacks.
	The wrapper holds weak reference to the callback.
	
	From http://stackoverflow.com/questions/1364923/ by Liori.
	"""

	def __init__(self, sender, callback):
		self.weak_obj = weakref.ref(callback.im_self)
		self.weak_fun = weakref.ref(callback.im_func)
		self.sender = sender
		self.handle = None

	def __call__(self, *things):
		obj = self.weak_obj()
		fun = self.weak_fun()
		if obj is not None and fun is not None:
			return fun(obj, *things)
		elif self.handle is not None:
			self.sender.disconnect(self.handle)
			self.handle = None
			self.sender = None

def weak_connect(sender, signal, callback):
	"""Connects given callback weakly to given signal so that the callback
	will not increase reference count.
	
	This will help with memory handling as the signal-connects will not prevent
	garbage collector from removing unneeded objects.
	"""
	wrapper = CallbackWrapper(sender, callback)
	wrapper.handle = sender.connect(signal, wrapper)
	return wrapper.handle
