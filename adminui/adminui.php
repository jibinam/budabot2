<?php

// load botwindow.glade file
$botWindowBuilder = new GtkBuilder();
$botWindowBuilder->add_from_file(dirname(__FILE__) . '/botwindow.glade');

// get some widgets and objects for easier access
$botWindow  = $botWindowBuilder->get_object('botwindow');
$outputView = $botWindowBuilder->get_object('outputView');
$outputModel = $outputView->get_buffer();

// clicking window's x-button quits the main event loop
$botWindow->connect_simple('destroy', array('gtk', 'main_quit'));

// set some example text to our bot's output view
$outputModel->set_text('Bot stdout goes here');

// show bot window and start main event loop
$botWindow->show_all();
Gtk::main();
