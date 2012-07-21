from distutils.core import setup
import py2exe
import os
import re
import gtk

GTK_RUNTIME_DIR = os.path.join(os.path.split(os.path.dirname(gtk.__file__))[0], "runtime")
assert os.path.exists(GTK_RUNTIME_DIR), "Cannot find GTK runtime data"
GTK_THEME_ENGINES_DIR = os.path.join("lib", "gtk-2.0", "2.10.0", "engines")

def generate_data_files(prefix, tree, file_filter=None):
	"""
	Walk the filesystem starting at "prefix" + "tree", producing a list of files
	suitable for the data_files option to setup(). The prefix will be omitted
	from the path given to setup(). For example, if you have

		C:\Python26\Lib\site-packages\gtk-2.0\runtime\etc\...

	...and you want your "dist\" dir to contain "etc\..." as a subdirectory,
	invoke the function as

		generate_data_files(
			r"C:\Python26\Lib\site-packages\gtk-2.0\runtime",
			r"etc")

	If, instead, you want it to contain "runtime\etc\..." use:

		generate_data_files(
			r"C:\Python26\Lib\site-packages\gtk-2.0",
			r"runtime\etc")

	Empty directories are omitted.

	file_filter(root, fl) is an optional function called with a containing
	directory and filename of each file. If it returns False, the file is
	omitted from the results.
	"""
	data_files = []
	for root, dirs, files in os.walk(os.path.join(prefix, tree)):
		to_dir = os.path.relpath(root, prefix)

		if file_filter is not None:
			file_iter = (fl for fl in files if file_filter(root, fl))
		else:
			file_iter = files

		data_files.append((to_dir, [os.path.join(root, fl) for fl in file_iter]))

	non_empties = [(to, fro) for (to, fro) in data_files if fro]

	return non_empties

class Target:
	def __init__(self, **kw):
		self.__dict__.update(kw)
		# for the versioninfo resources
		self.version = '1.0'
		self.company_name = 'budabot.com'
		self.copyright = 'GPL'
		self.name = 'Budabot Bot Manager'
		self.description = 'Bot Manager application for Budabot.'

windows_target = Target(
	script = 'botmanager.py',
	dest_base = 'BotManager',
)

dataFiles = []
dataFiles += generate_data_files('.', '',
	lambda root, name: name == 'settingsspec.ini')
dataFiles += generate_data_files('.', '',
	lambda root, name: re.search('[.]glade$', name) is not None)
dataFiles += generate_data_files('.', 'themes')
dataFiles += generate_data_files(GTK_RUNTIME_DIR, GTK_THEME_ENGINES_DIR, 
	lambda root, name: name == 'libpixmap.dll' or name == 'libclearlooks.dll')

setup(
	options = {
		'py2exe': {
			#'packages':'encodings',
			'includes': 'cairo, pango, pangocairo, atk, gobject, gio',
			#'compressed': 1,
			#'optimize': 2,
			#'bundle_files': 1,
			'dll_excludes': ['w9xpopen.exe']
		}
	},
	windows = [windows_target],
	data_files = dataFiles
	#zipfile = None
)
