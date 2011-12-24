<?php
   /*
   Dynacamp Module Ver 2.0
   Written By Jaqueme
   For Budabot
   Database Adapted From IGN Module Written By Drevil1
   RK Dynacamp Database Module
   Written 5/11/07
   Last Modified 5/27/07
   */

$links = array("Help" => "/tell <myname> help dyna");

if (preg_match ("/^dyna ([\\d]+)$/i", $message, $arr)) {
	$search = $arr[1];
	$range1 = $search - 25;
	$range2 = $search + 25;
	$data = $db->query("SELECT * FROM dynadb d JOIN playfields p ON d.playfield_id = p.id WHERE minQl > ? AND minQl < ? ORDER BY `minQl`", $range1, $range2);
	$count = count($data);

	$dynacamps = Text::make_header("Results Of Dynacamp Search For $search", $links);

	$dynacamps .= "There are $count locations matching your query\n\n";
	forEach($data as $row) {
		$coordLink = Text::make_chatcmd("{$row->cX}x{$row->cY} {$row->long_name}", "/waypoint $row->cX $row->cY $row->playfield_id");
		$dynacamps .="<pagebreak><yellow>$row->long_name:  Co-ordinates $coordLink\n";
		$dynacamps .="<green>Mob Type:  $row->mob\n";
		$dynacamps .="<blue>Level:  $row->minQl<yellow>-<blue>$row->maxQl\n\n";
	}
	
	$dynacamps = Text::make_blob("Dynacamps ($count found)", $dynacamps);
	$chatBot->send($dynacamps, $sendto);
} else if (preg_match ("/^dyna (.+)$/i", $message, $arr)) {
	$search = str_replace(" ", "%", $arr[1]);
	$data = $db->query("SELECT * FROM dynadb d JOIN playfields p ON d.playfield_id = p.id WHERE long_name LIKE ? OR short_name LIKE ? OR mob LIKE ? ORDER BY `minQl`", "%{$search}%", "%{$search}%", "%{$search}%");
	$count = count($data);

	$dynacamps = Text::make_header("Results Of Dynacamp Search For '$search'", $links);

	$dynacamps .= "There are $count locations matching your query\n\n";
	forEach($data as $row) {
		$coordLink = Text::make_chatcmd("{$row->cX}x{$row->cY} {$row->long_name}", "/waypoint $row->cX $row->cY $row->playfield_id");
		$dynacamps .="<pagebreak><yellow>$row->long_name:  Co-ordinates $coordLink\n";
		$dynacamps .="<green>Mob Type:  $row->mob\n";
		$dynacamps .="<blue>Level: $row->minQl<yellow>-<blue>$row->maxQl\n\n";
	}
	
	$dynacamps = Text::make_blob("Dynacamps ($count found)", $dynacamps);
	$chatBot->send($dynacamps, $sendto);
} else {
	$syntax_error = true;
}

?>