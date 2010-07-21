<?php

class Text {
/*===============================
** Name: format_message
** Formats an outgoing message with correct colors, replaces values, etc
*/	public static function format_message($message) {
		// Color
		$message = str_replace("<header>", Settings::get('default_header_color'), $message);
		$message = str_replace("<error>", Settings::get('default_error_color'), $message);
		$message = str_replace("<highlight>", Settings::get('default_highlight_color'), $message);
		$message = str_replace("<black>", "<font color='#000000'>", $message);
		$message = str_replace("<white>", "<font color='#FFFFFF'>", $message);
		$message = str_replace("<yellow>", "<font color='#FFFF00'>", $message);
		$message = str_replace("<blue>", "<font color='#8CB5FF'>", $message);
		$message = str_replace("<green>", "<font color='#00DE42'>", $message);
		$message = str_replace("<white>", "<font color='#FFFFFF'>", $message);
		$message = str_replace("<red>", "<font color='#ff0000'>", $message);
		$message = str_replace("<orange>", "<font color='#FCA712'>", $message);
		$message = str_replace("<grey>", "<font color='#C3C3C3'>", $message);
		$message = str_replace("<cyan>", "<font color='#00FFFF'>", $message);

		$message = str_replace("<myname>", $this->name, $message);
		$message = str_replace("<tab>", "    ", $message);
		$message = str_replace("<end>", "</font>", $message);
		$message = str_replace("<symbol>", Settings::get("symbol") , $message);
		
		$message = str_replace("<neutral>", "<font color='#EEEEEE'>", $message);
		$message = str_replace("<omni>", "<font color='#00FFFF'>", $message);
		$message = str_replace("<clan>", "<font color='#F79410'>", $message);
		$message = str_replace("<unknown>", "<font color='#FF0000'>", $message);

		return $message;
	}

/*===============================
** Name: makeHeader
** Make header.
*/	public static function makeHeader($title, $links = null) {
		$color = Settings::get('default_header_color');
		$baseR = hexdec(substr($color,14,2)); $baseG = hexdec(substr($color,16,2)); $baseB = hexdec(substr($color,18,2));
		$color2 = "<font color='#".strtoupper(substr("00".dechex($baseR*.75),-2).substr("00".dechex($baseG*.75),-2).substr("00".dechex($baseB*.75),-2))."'>";
		$color3 = "<font color='#".strtoupper(substr("00".dechex($baseR*.50),-2).substr("00".dechex($baseG*.50),-2).substr("00".dechex($baseB*.50),-2))."'>";
		$color4 = "<font color='#".strtoupper(substr("00".dechex($baseR*.25),-2).substr("00".dechex($baseG*.25),-2).substr("00".dechex($baseB*.25),-2))."'>";

		//Title
		$header = $color4.":::".$color3.":::".$color2.":::".$color;
		$header .= "$title";
		$header .= "</font>:::</font>:::</font>:::</font>\n";

		if ($links == TRUE) {
			$links = array( 'Help' => "chatcmd:///tell <myname> help",
					'About' => "chatcmd:///tell <myname> about",
					'Download' => "chatcmd:///start http://budabot.aodevs.com/index.php?page=14");
		}
		if ($links != null) {
			foreach ($links as $key => $value) {
				$header .= "$color4:$color3:$color2:";
				$header .= "<a style='text-decoration:none' href='$value'>$color$key</font></a>";
				$header .= ":</font>:</font>:</font>";
			}
		}

		$header .= Settings::get("default_window_color")."\n\n";

		return $header;
	}
	
/*===============================
** Name: makeBlob
** Make click link reference.
*/	public static function makeBlob($name, $content, $links = null) {
		$content = str_replace('"', '&quot;', $content);
		$content = explode("\n", $content);
		$page = 1;
		forEach ($content as $line) {
			$result[$page] .= $line."\n";
			if (strlen($result[$page]) >= Settings::get("max_blob_size")) {
				$page++;
			}
		}
		$pages = count($result);
		if ($pages == 1) {
			$result[$page] = "<a href=\"text://".Text::makeHeader($name, $links).Settings::get("default_window_color"].$result[$page)."\">$name</a>";
		} else {
			forEach ($result as $page => $content) {
				$result[$page] = "<a href=\"text://".Text::makeHeader("$name Page $page / $pages", $links).Settings::get("default_window_color"].$result[$page)."\">$name</a> (Page <highlight>$page / $pages<end>)";
			}
		}
		return $result;
	}

/*===============================
** Name: makeLink
** Make click link reference.
*/	public static function makeLink($name, $content, $type) {
		// escape double quotes
		if ($type == 'blob' || $type == '') {
			return Text::makeBlob($name, $content);
		} else {
			$content = str_replace('"', '&quote;', $content);
			$content = str_replace("'", '&#39;', $content);
		}

		if ($type == "text") { // Majic link.
			return "<a href='text://$content'>$name</a>";
		} else if ($type == "chatcmd") { // Chat command.
			return "<a href='chatcmd://$content'>$name</a>";
		} else if ($type == "user") { // Adds support for right clicking usernames in chat, providing you with a menu of options (ignore etc.) (see 18.1 AO patchnotes)
			return "<a href='user://$content'>$name</a>";
		} else {
			echo "Invalid type: '$type' \n";
		}
	}
	
/*===============================
** Name: makeItem
** Make item link reference.
*/	public static function makeItem($lowID, $hiID,  $ql, $name) {
		return "<a href='itemref://$lowID/$hiID/$ql'>$name</a>";
	}
}

?>
