title Budabot Admin UI
:: path where to look for theme engines's directory
set GTK_PATH=C:\Sources\adminui\win32
:: path to theme's gtkrc-file
set GTK2_RC_FILES=C:\Sources\adminui\adminui\themes\Cillop-Midnite\gtk-2.0\gtkrc
:: start the application
.\win32\php -c php-win.ini -f adminui/adminui.php -- %*

:: This file is part of Budabot.
::
:: Budabot is free software: you can redistribute it and/org modify
:: it under the terms of the GNU General Public License as published by
:: the Free Software Foundation, either version 3 of the License, or
:: (at your option) any later version.
::
:: Budabot is distributed in the hope that it will be useful,
:: but WITHOUT ANY WARRANTY; without even the implied warranty of
:: MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
:: GNU General Public License for more details.
::
:: You should have received a copy of the GNU General Public License
:: along with Budabot. If not, see <http://www.gnu.org/licenses/>.