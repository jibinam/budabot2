<?php

class Links {
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
			$result[$page] = "<a href=\"text://".Links::makeHeader($name, $links).Settings::get("default_window_color"].$result[$page)."\">$name</a>";
		} else {
			forEach ($result as $page => $content) {
				$result[$page] = "<a href=\"text://".Links::makeHeader("$name Page $page / $pages", $links).Settings::get("default_window_color"].$result[$page)."\">$name</a> (Page <highlight>$page / $pages<end>)";
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
			return Links::makeBlob($name, $content);
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
