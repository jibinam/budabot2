<?php
 
$window = new GtkWindow();
$window->set_title('Hello world');
$window->connect_simple('destroy', array('gtk', 'main_quit'));
 
$widget = new GtkLabel("Hello Budabot!'");
$widget->set_usize(200, 100);
$window->add($widget);
 
$window->show_all();
Gtk::main();

