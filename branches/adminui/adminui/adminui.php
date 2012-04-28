<?php

require_once 'BotProcess.class.php';

/**
 * This callback function is called when Budabot sends standard output.
 */
function onBotStdoutReceived($object, $data) {
	echo "STDOUT: $data";
}

/**
 * This callback function is called when Budabot sends standard errors.
 */
function onBotStderrReceived($object, $data) {
	echo "STDERR: $data";
}

/**
 * This callback function is called when Budabot is shutdown.
 */
function onBotDied() {
	echo "Oops! The bot just went dead!\n";
}

// create bot process object and connect to its signals
$process = new BotProcess();
$process->connect('stdout_received', 'onBotStdoutReceived');
$process->connect('stderr_received', 'onBotStderrReceived');
$process->connect_simple('stopped', 'onBotDied');
$process->start();

// load botwindow.glade file
$botWindowBuilder = new GtkBuilder();
$botWindowBuilder->add_from_file(dirname(__FILE__) . '/botwindow.glade');

// get some widgets and objects for easier access
$botWindow  = $botWindowBuilder->get_object('botwindow');
$outputView = $botWindowBuilder->get_object('outputView');
$outputModel = $outputView->get_buffer();

// clicking window's x-button quits the main event loop + the running bot
$botWindow->connect_simple('destroy', array($process, 'stop'));
$botWindow->connect_simple('destroy', array('gtk', 'main_quit'));

// set some example text to our bot's output view
$outputModel->set_text('Bot stdout goes here');

// show bot window and start main event loop
$botWindow->show_all();
Gtk::main();
