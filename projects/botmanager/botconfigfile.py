#! /usr/bin/env python
# -*- coding: utf-8 -*-

import re

class BotPhpConfigFile(object):
	""""""

	def __init__(self, filePath):
		"""Constructor method."""
		super(BotPhpConfigFile, self).__init__()
		self.filePath = filePath
		self.vars = {}

	def load(self):
		"""This method loads the settings from a file."""
		vars = {}
		with open(self.filePath, 'r') as file:
			prefix = r'^\s*\$vars\[[\'"](.+)[\'"]\]\s*=\s*'
			postfix = r'\s*;'
			for line in file:
				# search for var with a string value:
				match = re.search(prefix + r'[\'"](.*)[\'"]' + postfix, line)
				if match:
					vars[match.group(1)] = match.group(2)
					continue
				# search for var with a non-string value:
				match = re.search(prefix + '(.*)' + postfix, line)
				if match:
					vars[match.group(1)] = int(match.group(2))
		self.vars = vars

	def save(self):
		"""This method saves the settings to a file."""
		with open(self.filePath, 'r+b') as file:
			contents = file.read()
			contents = contents.replace('?>', '') # remove possibly offending php end-tag
			for name, value in self.vars.items():
				matcher = re.compile(r'^\s*\$vars\[[\'"]{0}[\'"]\]\s*=.*;'.format(name), re.MULTILINE)
				# wrap string value to quotes
				if isinstance(value, str):
					value = '"{0}"'.format(value)
				varString = '$vars[\'{0}\'] = {1};'.format(name, value)
				if matcher.search(contents):
					# replace existing variable
					contents = matcher.sub(varString, contents)
				else:
					# add new variable to file's end
					contents += '\n{0}\n'.format(varString)
			# write contents back to the file
			file.seek(0)
			file.truncate()
			file.write(contents)

	def getVar(self, name):
		"""Return variable's value of given name."""
		return self.vars[name]

	def setVar(self, name, value):
		"""Sets variable's value of given name."""
		self.vars[name] = value

	def __iter__(self):
		"""Enables ability to iterate through the file's variables
		with 'for...in'.
		"""
		for key, value in self.vars.items():
			yield key, value
