<?php
$prof_txt = "<header>::::: Guide to The AO Professions  :::::<end>\n\n"; 
$prof_txt = " Listing of Character Classes as follows:

Please note: all this information was taken from the official AO forums and should not be taken as a absolute rulebook. These are people's opinions,and you should play your character however you want. Just have fun. :P
<green>
<a href='chatcmd:///tell <myname> advys>Guide to Adventurers</a>
<a href='chatcmd:///tell <myname> agents>Guide to Agents</a>
<a href='chatcmd:///tell <myname> bureaucrats>Guide to Bureaucrats</a>
<a href='chatcmd:///tell <myname> doctors>Guide to Doctors</a>
<a href='chatcmd:///tell <myname> enforcers>Guide to Enforcers</a>
<a href='chatcmd:///tell <myname> engineers>Guide to Engineers</a>
<a href='chatcmd:///tell <myname> fixers>Guide to Fixers</a>
<a href='chatcmd:///tell <myname> martialartists>Guide to Martial Artists</a>
<a href='chatcmd:///tell <myname> metaphysicists>Guide to Meta Physicists</a>
<a href='chatcmd:///tell <myname> nanotechs>Guide to Nanotechs</a>
<a href='chatcmd:///tell <myname> soldiers>Guide to Soldiers</a>
<a href='chatcmd:///tell <myname> traders>Guide to Traders</a>
</end>"
;
$prof_txt = Text::makeLink("Guide to Professions", $prof_txt); 
if($type == "msg") 
$chatBot->send($prof_txt, $sender); 
elseif($type == "all") 
$chatBot->send($prof_txt); 
else 
$chatBot->send($prof_txt, "guild"); 
?>