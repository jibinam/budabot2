<?php
   /*
   Whereis Module Ver 1.1
   Written By Jaqueme
   For Budabot
   Database Adapted From One Originally 
   Compiled by Malosar For BeBot
   Whereis Database Module
   Written 5/11/07
   Last Modified 5/14/07
   */

$links = array("Help;chatcmd:///tell <myname> help whereis");

$msg = '';
if (preg_match("/^whereis (.+)$/i", $message, $arr)) {
	$search = $arr[1];
	$search = ucwords(strtolower($search));
	$data = $db->query("SELECT * FROM whereis WHERE name LIKE '%".str_replace("'", "''", $search)."%'");
	$whereis_found = $db->numrows();
	$whereis = '';
	
	forEach ($data as $row) {
		$whereis .= "<yellow>$row->name \n <green>Can be found $row->answer\n";
	}
	
	if ($whereis_found > 1) {
		
		$whereis = "There are $whereis_found matches to your query.\n\n" . $whereis;
	
		$msg = Text::makeBlob("Result of Whereis Search For $search", $whereis);
	} else if ($whereis_found == 1) {
		$msg = $whereis;
	} else {
		$msg = "<yellow>There were no matches for your search.</end>";
	}
}
else {
	$msg = "<yellow>You must enter valid search criteria.</end>\n";
}

$chatBot->send($msg , $sendto);

?>