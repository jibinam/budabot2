# Introduction #

TODO

Discussion thread :: http://budabot.com/forum/viewtopic.php?p=3829#p3829

# Installation #
Here's instructions of how to install and run the Bot Manager.

## For Windows: ##
There is no need to install anything for the manager as the manager comes as an executable (BotManager.exe) in the bot's package.
However, if you wish to execute it from sources, you will need following dependencies:
  * Download and install Python 2.7 32bit interpreter :: [Download](http://www.python.org/ftp/python/2.7.3/python-2.7.3.msi)
  * Download and install Python GTK bindings :: [Download](http://ftp.gnome.org/pub/GNOME/binaries/win32/pygtk/2.24/pygtk-all-in-one-2.24.2.win32-py2.7.msi)
  * OPTIONAL: Download GTK theme package and extract from the package _lib\gtk-2.0\2.10.0\engines\libclearlooks.dll_ file to _C:\Python27\Lib\site-packages\gtk-2.0\runtime\lib\gtk-2.0\2.10.0\engines_ :: [Download](http://freefr.dl.sourceforge.net/project/gtk-win/GTK%2B%20Themes%20Package/2009-09-07/gtk2-themes-2009-09-07-win32_bin.zip)
  * Download and install Python Setuptools :: [Download](http://pypi.python.org/packages/2.7/s/setuptools/setuptools-0.6c11.win32-py2.7.exe)
  * Install Python eggs Appdirs, ConfigObj, Tendo and Twisted by running in command prompt: _c:\python27\scripts\easy\_install configobj appdirs tendo twisted_
  * Install Python egg Pywin32 by running in command prompt: _c:\python27\scripts\easy\_install.exe http://freefr.dl.sourceforge.net/project/pywin32/pywin32/Build%20217/pywin32-217.win32-py2.7.exe_
  * OPTIONAL: Install Python egg elib.intl by running in command prompt: _c:\python27\scripts\easy\_install.exe https://github.com/dieterv/elib.intl/zipball/master_
  * Clone https://github.com/Budabot/Budabot.git and enter _botmanager_ subfolder
  * Double click botmanager.py to run the Bot Manager application

## For Ubuntu (and maybe other nixes): ##
GTK and Python seems to come preinstalled so there is no need to worry about those.
  * Install Python Setuptools, Python dev-package and GTK Pixmap theme engine :: _sudo apt-get install python-setuptools python-dev gtk2-engines-pixbuf_
  * Install Python eggs Appdirs, Tendo and Twisted :: _sudo easy\_install appdirs tendo twisted_
  * Install Python egg ConfigObj :: _sudo apt-get install python-configobj_, or alternatively using setuptools: _sudo easy\_install configobj_
  * Clone https://github.com/Budabot/Budabot.git and enter _botmanager_ subfolder
  * Open terminal to bot manager's directory and run: _./botmanager.py_ to run the Bot Manager application

## Configuration Files ##
Bot Manager stores its list of bots to a ini-file. Here's paths to the ini-file for Ubuntu and Windows 7:

**In Windows 7:** _C:\Users\$USERNAME\AppData\Local\budabot.com\Budabot Bot Manager\1.0\settings.ini_

**In Ubuntu:** _/home/$USERNAME/.config/budabot bot manager/1.0/settings.ini_
An empty file is created when Bot Manager is ran the first time.