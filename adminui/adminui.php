<?php
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { 
	putenv('GTK_PATH=win32');
}
Gtk::rc_parse('adminui/themes/Cillop-Midnite/gtk-2.0/gtkrc');

require_once 'Application.class.php';

$application = new Application();
$application->execute();
