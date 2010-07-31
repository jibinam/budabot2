<?php
// TODO
if (preg_match("/^cmdlist$/i", $message, $arr) || preg_match("/^cmdlist (.*)$/i", $message, $arr)) {
	$list  = "<header>::::: Bot Settings -- Command List :::::<end>\n\n";
	
	if ($arr[1] != '') {
		$cmdSearchSql = "AND c.cmd LIKE '%{$arr[1]}%'";
	}

	$sql = "
		SELECT
			c.cmd,
			c.description,
			c.module,
			c.file,
			c.admin
		FROM
			cmdcfg_<myname> c
		WHERE
			1
			$cmdSearchSql
		GROUP BY
			c.cmd, c.description, c.module
		ORDER BY
			cmd ASC";
	$db->query($sql);

	while ($row = $db->fObject()) {
		$guild = '';
		$priv = '';
		$msg = '';

		$on = Text::makeLink('ON', "/tell <myname> config cmd $row->cmd enable all", 'chatcmd');
		$off = Text::makeLink('OFF', "/tell <myname> config cmd $row->cmd disable all", 'chatcmd');
		$adv = Text::makeLink('Adv.', "/tell <myname> config cmd $row->cmd $row->module", 'chatcmd');
		
		if ($row->msg_avail == 0) {
			$tell = "|_";
		} else if ($row->msg_status == 1) {
			$tell = "|<green>T<end> ($row->admin)";
		} else {
			$tell = "|<red>T<end> ($row->admin)";
		}
		
		if ($row->guild_avail == 0) {
			$guild = "|_";
		} else if ($row->guild_status == 1) {
			$guild = "|<green>G<end> ($row->admin)";
		} else {
			$guild = "|<red>G<end> ($row->admin)";
		}
		
		if ($row->priv_avail == 0) {
			$priv = "|_";
		} else if ($row->priv_status == 1) {
			$priv = "|<green>P<end> ($row->admin)";
		} else {
			$priv = "|<red>P<end> ($row->admin)";
		}
		
		if ($row->description != "") {
			$list .= "$row->cmd [$row->file] ($adv$tell$guild$priv): $on  $off - ($row->description)\n";
		} else {
			$list .= "$row->cmd - ($adv$tell$guild$priv): $on  $off\n";
		}
	}

	$msg = Text::makeLink("Bot Settings -- Command List", $list);
 	$this->send($msg, $sendto);
}

?>