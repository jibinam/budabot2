<?php
$martialartists_txt = "<header>::::: Guide to Martial Artists :::::<end>\n\n"; 
$martialartists_txt = "Please see the following website for an excellent, yet somewhat outdated, Martial Artist Guide

'http://users.adelphia.net/~chronita/index.htm'

This guide is quite excellent in giving you information all about the MA class and how to play them
 ";

$martialartists_txt = Text::makeLink("Guide to Martialartists", $martialartists_txt); 
if($type == "msg") 
$chatBot->send($martialartists_txt, $sender); 
elseif($type == "all") 
$chatBot->send($martialartists_txt); 
else 
$chatBot->send($martialartists_txt, "guild"); 
?>