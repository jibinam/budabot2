<?php
   /*
   ** Module: TOWER_WATCH
   ** Author: Tyrence/Whiz (RK2)
   ** Description: Allows you to keep track of the opentimes of tower sites.
   ** Version: 1.2
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23-November-2007
   ** Date(last modified): 9-Mar-2010
   ** 
   ** Copyright (C) 2008 Jason Wheeler (bigwheels16@hotmail.com)
   **
   ** This module and all it's files and contents are licensed
   ** under the GNU General Public License.  You may distribute
   ** and modify this module and it's contents freely.
   **
   ** This module may be obtained at: http://www.box.net/shared/bgl3cx1c3z
   **
   */

if (preg_match("/^(open|openomni) (\\d+)$/i", $message, $arr) || preg_match("/^(open|openomni) (\\d+) (\\d+)$/i", $message, $arr)) {

	$lowql = $arr[2];
	if ($arr[3]) {
		$highql = $arr[3];
	} else {
		$highql = $arr[2];
	}

	if (strtolower($arr[1]) == 'openomni') {
		$title = "Scouted Omni bases open in the next hour with CT QL {$lowql}-{$highql}";
		$side_sql = "AND (s.faction = 'Omni' OR s.faction = 'Neutral')";
	} else {
		$title = "Scouted Clan bases open in the next hour with CT QL {$lowql}-{$highql}";
		$side_sql = "AND (s.faction = 'Clan')";
	}
	
	$openTimeSql = getOpenTimeSql(time() % 86400);
	
	$sql = "
		SELECT
			*
		FROM
			tower_site t
			JOIN scout_info s ON (t.playfield_id = s.playfield_id AND s.site_number = t.site_number)
			JOIN playfields p ON (t.playfield_id = p.id)
		WHERE
			$openTimeSql
			AND (s.ct_ql BETWEEN $lowql AND $highql)
			$side_sql
		ORDER BY
			close_time";
	$data = $db->query($sql);
	$numrows = count($data);
	
	$blob = '';
	forEach ($data as $row) {
		$gas_level = getGasLevel($row->close_time);
		$site_link = Text::make_chatcmd("$row->short_name $row->site_number", "/tell <myname> lc $row->short_name $row->site_number");
		$gas_change_string = "$gas_level->color $gas_level->gas_level - $gas_level->next_state in " . gmdate('H:i:s', $gas_level->gas_change) . "<end>";
		
		$blob .= "$site_link <white>- {$row->min_ql}-{$row->max_ql}, $row->ct_ql CT, $row->org_name,<end>$gas_change_string <white>[by $row->scouted_by]<end>\n";
	}
	
	if ($numrows > 0) {
		$msg = Text::make_blob($title, $title . "\n\n" . $blob);
	} else {
		$msg = "No sites found.";
	}
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}
?>