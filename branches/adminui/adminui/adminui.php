<?php

require_once 'BotProcess.class.php';
require_once 'ControlPanelController.class.php';
require_once 'budapi/Budapi.php';

/**
 * This method adds @a $message to end of @a GtkTextView's model.
 * Optional tag of name @a $tagname is applied for the @a $message.
 */
function insertToModel($model, $message, $tagname = '') {
	if ($model && $message) {
		$start = $model->get_char_count();
		$model->insert($model->get_iter_at_offset($start), $message);
		$end = $model->get_char_count();
		
		// wrap the text to given tag if needed
		if ($tagname) {
			$model->apply_tag_by_name($tagname, $model->get_iter_at_offset($start), $model->get_iter_at_offset($end));
		}
	}
}

/**
 * This callback is called when output view's vertical
 * scrollbar ($adjustment) changes.
 * Scrolls the scrollbar to bottom of the scrollable area.
 */
function scrollViewToBottom($adjustment) {
	$adjustment->set_value($adjustment->upper - $adjustment->page_size);
}

/**
 * This callback function is called when Budabot sends standard output.
 */
function onBotStdoutReceived($object, $data) {
	global $outputView;
	insertToModel($outputView->get_buffer(), $data);
}

/**
 * This callback function is called when Budabot sends standard errors.
 */
function onBotStderrReceived($object, $data) {
	global $outputView;
	insertToModel($outputView->get_buffer(), $data, 'error');
}

/**
 * This callback function is called when Budabot is shutdown.
 */
function onBotDied() {
	global $outputView;
	insertToModel($outputView->get_buffer(), "Oops! The bot just went dead!\n", 'error');
}

/**
 * This callback function is called when user hits enter-key in the command
 * input entry.
 * Sends the entry's text as a command to the bot through Budapi SDK and
 * clears the entry field.
 */
function onCommandGiven() {
	global $commandEntry;
	global $argv;
	global $outputView;
	global $destinationSelector;
		
	$command = $commandEntry->get_text();
	// clear the input entry
	$commandEntry->set_text('');
	
	$destinationType = $destinationSelector->get_model()->get_value($destinationSelector->get_active_iter(), 1);
	switch ($destinationType) {
	case 1: // org channel
		$command = 'say org ' . $command;
		break;
	case 2: // private channel
		$command = 'say priv ' . $command;
		break;
	}
	
	$api = new Budapi();
	$api->setHost('127.0.0.1');

	// set tcp/ip port where the bot listening
	if (isset($argv[1])) {
		$api->setPort(intval($argv[1]));
	}
	// set username
	if (isset($argv[2])) {
		$api->setUsername($argv[2]);
	}
	// set api password
	if (isset($argv[3])) {
		$api->setPassword($argv[3]);
	}
	
	// send command and handle errors that might occur
	try {
		$response = $api->sendCommand($command);
		insertToModel($outputView->get_buffer(), $response . "\n", 'response');
	}
	catch (BudapiServerException $e) {
		$message = "Server sent error code: " . $e->getCode() . "\n";
		switch ($e->getCode()) {
		case Budapi::API_UNSET_PASSWORD:
		case Budapi::API_INVALID_PASSWORD:
			$message = "Your credentials are incorrect, make sure you have set your API password with command 'apipassword'\n";
			break;

		case Budapi::API_ACCESS_DENIED:
			$message = "Access denied! You have don't have permissions to execute this command\n";
			break;

		case Budapi::API_UNKNOWN_COMMAND:
			if ($destinationType == 1) {
				$message = "Failed to sent the message, make sure that 'say org' command is enabled\n";
			}
			else if ($destinationType == 2) {
				$message = "Failed to sent the message, make sure that 'say priv' command is enabled\n";
			}
			else { // to chatbot
				$message = "Failed to sent the message, the command was not found\n";
			}
			break;
			
		case Budapi::API_SYNTAX_ERROR:
			$message = "Failed to sent the message, there was a syntax error with your command\n";
			break;
		}
		insertToModel($outputView->get_buffer(), $message, 'error');
	}
	catch (Exception $e) {
		insertToModel($outputView->get_buffer(), $e->getMessage() . "\n", 'error');
	}
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
$outputScrollArea = $botWindowBuilder->get_object('outputScrollArea');
$outputView = $botWindowBuilder->get_object('outputView');
$commandEntry = $botWindowBuilder->get_object('commandInputEntry');
$destinationSelector = $botWindowBuilder->get_object('destinationSelector');
$outputModel = $outputView->get_buffer();
$tagTable = $outputModel->get_tag_table();

// create a red bolded error tag
$errorTag = new GtkTextTag('error');   
$errorTag->set_property('foreground', 'red');
$errorTag->set_property('weight', Pango::WEIGHT_BOLD);
$tagTable->add($errorTag);

// create a blue response tag
$errorTag = new GtkTextTag('response');   
$errorTag->set_property('foreground', 'blue');
$tagTable->add($errorTag);

// call scrollViewToBottom() when scroll area's vertical scrollbar changes
$outputScrollArea->get_vadjustment()->connect('changed', 'scrollViewToBottom');

// call onCommandGiven() when user hits enter-key within the entry
$commandEntry->connect_simple('activate', 'onCommandGiven');

// clicking window's x-button quits the main event loop + the running bot
$botWindow->connect_simple('destroy', array($process, 'stop'));
$botWindow->connect_simple('destroy', array('gtk', 'main_quit'));

// show bot window and start main event loop
$botWindow->show_all();

$controlPanel = new ControlPanelController();
$controlPanel->show();

Gtk::main();
