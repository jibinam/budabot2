<?phpif (preg_match("/^random (.+)$/i", $message, $arr)){	$grouptext = trim($arr[1]);	$text = split(" ", $grouptext);	$low = 0;	$high = count($text) - 1;	while (true) {		$random = rand($low, $high);		if (!isset($marked[$random])) {			$count++;			$newtext .= " $count: ".$text[$random];			$marked[$random] = 1;			if (count($marked) == count($text)) {				break;			}		}		$i = $low;		while (true) {			if ($marked[$i] != 1) {				$low = $i;				break;			} else {				$i++;			}		}		$i = $high;		while (true) {			if ($marked[$i] != 1) {				$high = $i;				break;			} else {				$i--;			}		}	}    	bot::send($newtext, $sendto);}?>