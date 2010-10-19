<?php
	/*
	 * This command will search for the specified
	 * command and will return a link to the
	 * module configuration containing the command
	 *
	 * Author: Mindrila (RK1)
	 * Date: 21.05.2010
	 */

if (preg_match("/^searchcmd (.*)/i", $message, $arr))
{
	$sqlquery = "SELECT DISTINCT module FROM cmdcfg_<myname> WHERE `cmd` = '".strtolower($arr[1])."' ;";
	$data = $db->query($sqlquery);
	
	if ( 0 == $db->numrows())
	{
		$msg = "<highlight>".strtolower($arr[1])."<end> could not be found.";
		$chatBot->send($msg,$sendto);
		return;
	}
	
	$blob = '';
	$msg = '';
	foreach ($data as $row)
	{
		$foundmodule = strtoupper($row->module);
		$blob .= Text::make_link($foundmodule.' configuration', '/tell <myname> config '.$foundmodule, 'chatcmd') . "\n";
	}
	if (count($data) == 0)
	{
		$msg = "No results found.";
	}
	else
	{
		$msg = Text::make_link(count($data) . ' results found.', $blob);
	}
	$chatBot->send($msg, $sendto);
}


?>