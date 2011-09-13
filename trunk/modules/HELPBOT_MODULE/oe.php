<?php

if (preg_match("/^oe ([0-9]+)$/i", $message, $arr)) {
    $oe = $arr[1];
	$oe100 = (int)floor($oe / 0.8);
	$lowoe100 = (int)floor($oe * 0.8);
	$oe75 = (int)floor($oe / 0.6);
	$lowoe75 = (int)floor($oe * 0.6);
	$oe50 = (int)floor($oe / 0.4);
	$lowoe50 = (int)floor($oe * 0.4);
	$oe25 = (int)floor($oe / 0.2);
	$lowoe25 = (int)floor($oe * 0.2);

	$blob = "<header> :::::: Over-equipped Calculation :::::: <end>\n\n".
		"With a weapons skill requirement of <highlight>$oe<end>, you will OE at:\n". 
		"Out of OE: <highlight>$lowoe100<end> or higher\n".
		"75%: <highlight>$lowoe75<end> - <highlight>" .($lowoe100 - 1). "<end>\n".
		"50%: <highlight>" .($lowoe50 + 1). "<end> - <highlight>" .($lowoe75 - 1). "<end>\n".
		"25%: <highlight>" .($lowoe25 + 1). "<end> - <highlight>$lowoe50<end>\n".
		"0%: <highlight>$lowoe25<end> or lower\n\n".
		"With a personal skill of <highlight>$oe<end>, you can use up to and be:\n".
		"Out of OE: <highlight>$oe100<end> or lower\n".
		"75%: <highlight>" .($oe100 + 1). "<end> - <highlight>$oe75<end>\n".
		"50%: <highlight>" .($oe75 + 1). "<end> - <highlight>" .($oe50 - 1). "<end>\n".
		"25%: <highlight>$oe50<end> - <highlight>" .($oe25 - 1). "<end>\n".
		"0%: <highlight>$oe25<end> or higher\n\n".
		"WARNING: May be plus/minus 1 point!";
	
	$msg = "<orange>{$lowoe100}<end> - <yellow>{$oe}<end> - <orange>{$oe100}<end> " . Text::make_blob('More info', $blob);
    
    $chatBot->send($msg, $sendto);
/*
} else if (preg_match('/^oe \<a href\=\"itemref\:\/\/([0-9]+)\/([0-9]+)\/([0-9]+)\"\>/i', $message, $arr)) {
	$url = "http://itemxml.xyphos.com/?";
	$url .= "id={$arr[1]}&";  // use low id for id
	//$url .= "id={$arr[2]}&";  // use high id for id
	$url .= "ql={$arr[3]}&";

	$data = file_get_contents($url, 0);
	if (empty($data) || '<error>' == substr($data, 0, 7)) {
		$msg = "Unable to query Items XML Database.";
		$chatBot->send($msg, $sendto);
		return;
	}
*/
} else {
	$syntax_error = true;
}

?>
