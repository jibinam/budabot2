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


$path = getcwd() . "/modules/INFO_MODULE/info/";
$fileExt = ".txt";
$msg = "";

// if they want the list of topics
if (preg_match("/^info$/i", $message)) {
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
		
		$linkContents = '';
		forEach ($topicList as $topic) {
			$linkContents .= Text::makeLink($topic, "/tell {$chatBot->name} info {$topic}", 'chatcmd') . "\n";  
		}
		
		if ($linkContents) {
			$msg = Text::makeBlob('Topics (' . count($topicList) . ')', $linkContents);
		} else {
			$msg = "No topics available.";   
		}
	} else {
		$msg = "Error reading topics.";	
	}
} else if (preg_match("/^info (.*)$/i", $message, $arr)) {
	// if they want a certain topic
	// get the filename and read in the file
	$fileName = $arr[1];
	$info = getTopicContents($path, $fileName, $fileExt);
	
	// make sure the $ql is an integer between 1 and 300
	if (!$info) {
		$msg = "No info for $fileName could be found";
	} else {	
		$msg = Text::makeBlob($fileName, $info);
	}
}

$chatBot->send($msg, $sendto);

?>
