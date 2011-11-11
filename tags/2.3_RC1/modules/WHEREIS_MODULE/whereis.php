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

$links = array("Help" => "/tell <myname> help whereis");

$msg = '';
if (preg_match("/^whereis (.+)$/i", $message, $arr)) {
	$search = $arr[1];
	$search = ucwords(strtolower($search));
	$db->query("SELECT * FROM whereis WHERE name LIKE '%".str_replace("'", "''", $search)."%'");
	$whereis_found = $db->numrows();
	$whereis = '';
	
	$data = $db->fobject("all");
	forEach ($data as $row) {
		$whereis .= "<yellow>$row->name \n <green>Can be found $row->answer\n\n";
	}
	
	if ($whereis_found > 1) {
		$header = Text::make_header("Result of Whereis Search For $search", $links);
		$header .= "There are $whereis_found matches to your query.\n\n";
		
		$whereis = $header . $whereis;
	
		$msg = Text::make_blob("Whereis", $whereis);
	} else if ($whereis_found == 1) {
		$msg = $whereis;
	} else {
		$msg = "<yellow>There were no matches for your search.<end>";
	}
	$chatBot->send($msg , $sendto);
} else {
	$syntax_error = true;
}

?>