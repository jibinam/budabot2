<?php
$tradenpc_txt = "<header>::::: Guide to Unique NPCs :::::<end>\n\n"; 
$tradenpc_txt = "These NPCs may not be the easiest to find, but their wares are out of the ordinary and certainly worth the trip,if at least to get a refreshing change of scenery.

<a href='chatcmd:///tell <myname> zoftig'>'Zoftig Blimp'</a> 
<a href='chatcmd:///tell <myname> meleesmith'>'The Tsunayoshi Smith'</a> 
<a href='chatcmd:///tell <myname> fshop'>'The Fixer Shop'</a> 
<a href='chatcmd:///tell <myname> bernice'>'Thin Bernice'</a> 
<a href='chatcmd:///tell <myname> tshop'>'Trader Shops'</a>  
";
$tradenpc_txt = Text::makeLink("Guide to Unique NPCs", $tradenpc_txt); 
if($type == "msg") 
$chatBot->send($tradenpc_txt, $sender); 
elseif($type == "all") 
$chatBot->send($tradenpc_txt); 
else 
$chatBot->send($tradenpc_txt, "guild"); 
?>