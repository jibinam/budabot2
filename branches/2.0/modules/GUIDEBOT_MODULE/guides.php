<?php
   /*
   ** Author: Tyrence (RK2)
   ** Description: Statistics for implants at given ql
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 13-OCT-2007
   ** Date(last modified): 13-OCT-2007
   ** 
   ** Copyright (C) 2007 Jason Wheeler
   */


$path = getcwd() . "/modules/GUIDEBOT_MODULE/guides/";
$fileExt = ".txt";
$msg = "";

if ($message == "guides") {
	$message = "guides guides";
}
	
// if they want the list of topics
if (preg_match("/^guides list$/i", $message)) {
	if ($handle = opendir($path)) {
		$topicList = array();

		/* This is the correct way to loop over the directory. */
		while (false !== ($fileName = readdir($handle))) {
			// if file has the correct extension, it's a topic file
			if (strpos($fileName, $fileExt)) {
				$topicList[] =  str_replace($fileExt, '', $fileName);
			}
		}

		closedir($handle);
		
		global $vars;
		global $settings;
		$linkContents = '';
		forEach ($topicList as $topic) {
			$linkContents .= Text::makeLink($topic, "/tell " . $vars['name'] . " " . $settings['symbol'] . "info $topic", 'chatcmd') . "\n";  
		}
		
		if ($linkContents) {
			$msg = Text::makeBlob('Topics (' . count($topicList) . ')', $linkContents);
		} else {
			$msg = "No topics available.";   
		}
	} else {
		$msg = "Error reading topics.";	
	}
} else if (preg_match("/^guides (.*)$/i", $message, $arr)) {
	// if they want a certain topic
	// get the filename and read in the file
	$fileName = strtolower($arr[1]);
	$info = getTopicContents($path, $fileName, $fileExt);
	
	// make sure the $ql is an integer between 1 and 300
	if (!$info) {
		$msg = "No info for $fileName could be found";
	} else {	
		$msg = Text::makeBlob(ucfirst($fileName), $info);
	}
}

$this->send($msg, $sendto);

?>
