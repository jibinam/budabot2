<?

dl("php_sockets.dll"); 

function EchoAndLog($texte)
{
	global $flog;
	fputs($flog,$texte);	
	$texte = eregi_replace('<font([^>]+)>([^<]+)</font>',"\\2",$texte);
	$texte = eregi_replace('<a href="([^"]+)">([^<]+)</a>',"\\2",$texte);
	$texte = eregi_replace('<a href=\'([^"]+)\'>([^<]+)</a>',"\\2",$texte);
	echo $texte;
}

	$rk = $_SERVER["argv"][1];
	if ( $rk!="rk1" && $rk!="rk2" ) 
	{
		print_r($_SERVER);
		die("RK mal defini");
	}

	/**********************/
	include("recipebotParam".$rk.".php");
	/**********************/
  $lastMessageFromGuilde="";
  $char_AO = ucfirst($char_AO);
	$action_buffer=array();
	$lastTellTime=0;
	$flog = fopen("logs/log-".$rk."-".date("d-m-Y").".txt","a");
	EchoAndLog("Launching the bot...".date("d/m/Y H:i:s")."\n");
	require_once "chatao".$rk.".php";
	EchoAndLog("Connecting to the database ...");
	$iddb=@mysql_pconnect ($SQLHost,$SQLLogin,$SQLPassword) or die("Connection impossible :".mysql_error());
	EchoAndLog("OK\n");
	@mysql_selectdb($SQLDatabase,$iddb);
	$perso = array();
	$result = SQL_request("LOCK TABLES membre".$rk." WRITE");
	//$result = SQL_request("DELETE FROM membre".$rk." WHERE TypeMbr=0");
	$result = SQL_request("OPTIMIZE TABLE `membre".$rk."`");
	$result = SQL_request("UNLOCK TABLES");
	$et = 0;
	EchoAndLog("Connecting to server ...");
	EchoAndLog($char_AO);
	$aoc = new AOChat("callback",0,$et);
	if ($et==1)
	{
		EchoAndLog( "OK\nLogging in to server ...");
		$chars = $aoc -> authenticate($login_AO, $passwd_AO);
		EchoAndLog(  "OK\nSelecting Character ...");
		if (gettype ($nb_perso)!="integer") die("Perso $char_AO introuvable !!");
		$aoc -> login($chars[$nb_perso]["name"]);
		EchoAndLog(  "OK\nReady...\n");
		$deb=chr(17);
		$fin=chr(18);
		socket_clear_error( $aoc -> socket);
		$lastTellTime=getmicrotime();
		$test_socket=0;
		while($test_socket==0)
		{
			set_time_limit(60);
			$aoc -> wait_for_packet();
			$test_socket = socket_last_error($aoc -> socket);
			if ($test_socket!=0) EchoAndLog( "$test_socket:".socket_strerror($test_socket));
			parseTell();
		}
		socket_close($aoc -> socket);
	}
	$a=60;
	while ($a==0)
	{
		EchoAndLog(  "Left the loop, reconnection in $a seconds\n");
		$a--;
		pause(1000);
	}
	die("Fin !");

function googleSearch( $ch )
{
	//echo "*** ".$ch." ***\n";
	$url = "http://www.google.com/search?hl=en&q=".urlencode('"anarchy online" "'.str_replace("'","\'",$ch).'"')."&meta=";
	//echo "*** ".$url." ***\n";
	$fgoogle = fopen($url,"r");
	$content="";
	$content.=fread($fgoogle,1024);
	while ( !feof($fgoogle))
			$content.=fread($fgoogle,1024);
	$text = "";
	if ( eregi("environ <b>([0-9]+)</b> pour",$content,$r))
		if ( $r[1]!=0 )
			$text = "\nAnyway, <a href=\"charref://1/".rand(1,10000)."/".colorize("#L \"".$url."\" \"/start ".$url."\"")."\">Google</a> know ".$r[1]." page(s) about this";
	return $text;
}

function giveMeRecipeNum ( $num )
{
	$text="";
	$result = SQL_request("SELECT TitreRecette, TextRecette FROM recette WHERE NumRecette=".$num);
	if ( $line = mysql_fetch_row($result) )
	{
		$line[1].="\n";
		$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
		$foot = mysql_fetch_row($r2);
		$foot = trim($foot[0]);
		$r2 = SQL_request("SELECT Link FROM link WHERE NumRecette=".$num);
		if ( mysql_num_rows($r2)!=0 )
		{
			$line[1].="[16,20]-----------------------------------------------------\nOnline Link :\n";
			while ( $l = mysql_fetch_row($r2) )
				$line[1].="- #L \"$l[0]\" \"/start $l[0]\"\n";
		}
		$text .= "[16,18] $line[0]  [16,20]\n$line[1]".$foot;
	}
	return $text;
}

function clump($hid)
{
	switch ($hid) 
	{
		case 247103:$text=giveMeRecipeNum(5);$type = "Pristine Kyr'Ozch Bio-Material (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>).";break;
		case 247105:$text=giveMeRecipeNum(5);$type = "Mutated Kyr'Ozch Bio-Material (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>).";break;
		case 247698:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 76 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Brawl and Fast Attack";break;
		case 247700:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 112 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Brawl, Dimach and Fast Attack";break;
		case 247702:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 240 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Brawl, Dimach, Fast Attack and Sneak Attack";break;
		case 247704:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 880 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Dimach, Fast Attack, Parry and Riposte";break;
		case 247706:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 992 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Dimach, Fast Attack, Sneak Attack, Parry and Riposte";break;
		case 247708:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 1 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Fling shot";break;
		case 247710:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 2 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Aimed Shot";break;
		case 247712:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 4 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Burst";break;
		case 247714:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 5 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Fling Shot and Burst";break;
		case 247716:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 12 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Burst and Full Auto";break;
		case 247718:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 3 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Fling Shot and Aimed Shot";break;
		case 247720:$text=giveMeRecipeNum(6);$type = "Kyr'Ozch Bio-Material - Type 13 (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>) / Burst, Fling Shot and Full Auto";break;
		case 254804:$text=giveMeRecipeNum(28);$type = "Kyr'Ozch Viral Serum (used in this <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">recipe</a>)";break;
		default:$type = "";
	}
	return $type;
}
	
function colorize($string, $recipenum = "\0")
{
	global $char_AO;
	$string = str_replace ("recipebot",$char_AO,$string);
	$string = str_replace ("recipenum",$recipenum,$string);
	$string = ereg_replace('#L "([^"]+)" "([0-9]+)"','#L "\\1" "/tell '.$char_AO.' show me item \\2"',$string);
	$string = str_replace ("'","`",$string);
	$string = ereg_replace("#C([0-9]+)","[16,\\1]",$string);
	//$string = str_replace("/dedicace","/tell $char_AO show me",$string);
	$string = str_replace("/showfile","/tell $char_AO show me",$string);
	$string = str_replace("/showchar","/tell helpbot whois",$string);
	$string = str_replace("/showsite","/start http://www.aode.fr.st/",$string);
	$string = str_replace(".txt","",$string);
	$string = str_replace("rec_","",$string);
	$string = str_replace(">","",$string);
	$string = str_replace("cd_image/text/help/","",$string);
	$string = str_replace("[16,1]", "<font color=#FFFFFF>",
	str_replace("[16,2]", "</font><font color=#FFFFFF>",
	str_replace("[16,3]", "</font><font color=#FFFFFF>",
	str_replace("[16,4]", "</font><font color=#FFFFFF>",
	str_replace("[16,5]", "</font><font color=#FFFFFF>",
	str_replace("[16,6]", "</font><font color=#FFFFFF>",
	str_replace("[16,7]", "</font><font color=#FFFFFF>",
	str_replace("[16,8]", "</font><font color=#FFFFFF>",
	str_replace("[16,9]", "</font><font color=#FFFFFF>",
	str_replace("[16,10]","</font><font color=#FFFFFF>",
	str_replace("[16,11]","</font><font color=#FFFFFF>",
	str_replace("[16,12]","</font><font color=#FF0000>",
	str_replace("[16,13]","</font><font color=#FFFFFF>",
	str_replace("[16,14]","</font><font color=#FFFFFF>",
	str_replace("[16,15]","</font><font color=#FFFFFF>",
	str_replace("[16,16]","</font><font color=#FFFF00>",
	str_replace("[16,17]","</font><font color=#FFFFFF>",
	str_replace("[16,18]","</font><font color=#AAFF00>",
	str_replace("[16,19]","</font><font color=#FFFFFF>",
	str_replace("[16,20]","</font><font color=#009B00>",
	str_replace("[16,21]","</font><font color=#FFFFFF>",
	str_replace("[16,22]","</font><font color=#FFFFFF>",
	str_replace("[16,23]","</font><font color=#FFFFFF>",
	str_replace("[16,24]","</font><font color=#FFFFFF>",
	str_replace("[16,25]","</font><font color=#FFFFFF>",
	str_replace("[16,26]","</font><font color=#FFFFFF>",
	str_replace("[16,27]","</font><font color=#FFFFFF>",
	str_replace("[16,28]","</font><font color=#FFFFFF>",
	str_replace("[16,29]","</font><font color=#FFFFFF>",
	str_replace("[16,30]","</font><font color=#FFFFFF>",
	str_replace("[16,31]","</font><font color=#FFFFFF>",
	str_replace("[17]",chr(17),
	str_replace("[18]",chr(18),$string)))))))))))))))))))))))))))))))));
// MODIF NOUVAU CHAT
	$string = ereg_replace('#L "([^"]+)" "([^"]+)"',"<a href='chatcmd://\\2'>\\1</a>",$string);
	$string = ereg_replace('"',"&quot;",$string);
	return $string;
}

function MakeTextBlob($s)
{
	if (strlen($s)%2==0) $s.=" "; // message must be an odd number of bytes

	return	( chr(0) . chr(0) .   //null bytes
		chr(0) . chr(0) . chr(195) . chr(80) .	// indicates a character reference (as opposed to an item ref)
		chr(0) . chr(0) . chr(rand(0,255)) . chr(rand(0,255)) .  // character number (or window number)
		chr(0) . chr(0) . chr(0) . chr(0) . chr(0) .chr(0) . // unknown
		chr(((strlen($s) + 2) >> 8) & hexdec("ff")) . chr(((strlen($s))) & hexdec("ff")) . // length of message
		$s ); //message
}

	
	function sendTell($user, $msg, $blob = "\0")
	{
		global $action_buffer;
		array_push($action_buffer,"tell",$user,$msg,$blob);
	}
	
	function parseTell()
	{
		global $rk,$aoc,$lastTellTime,$action_buffer,$nomchan,$priv_grp;
		// freebox
		if( getmicrotime() - $lastTellTime > 600 ) die("No response for 1 minute....   ");
		// !freebox
		if( count($action_buffer)==0 ) return;
		if( getmicrotime() - $lastTellTime < 2 ) return;
		$type=array_shift($action_buffer);
		switch ( $type )
		{
			case "tell":
				$v1=array_shift($action_buffer);
				$v2=array_shift($action_buffer);
				$v3=array_shift($action_buffer);
				EchoAndLog( "[".date("H:i:s")."][To $v1]: ".$v2."\n");
				$aoc -> send_tell($v1, $v2, $v3);
				
				$result = SQL_request("SELECT Pub FROM membre".$rk." WHERE NomMbr LIKE \"".$v1."\"");
				$ok=0;
				if ($pub = mysql_fetch_row($result))
					$ok=1;
				if ( $pub[0] == 0 && $ok==1 )
				{
					//met pub a 1
					$result = SQL_request("LOCK TABLES membre".$rk." WRITE");
					$result = SQL_request("UPDATE membre".$rk." SET Pub=1 WHERE NomMbr LIKE \"".$v1."\"");
					$result = SQL_request("UNLOCK TABLES");
					// selectionne le pied de page
					$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
					$foot = mysql_fetch_row($r2);
					$foot = trim($foot[0])."\n";
					// selectionne le text et le titre de la pub
					$r2 = SQL_request("SELECT Texte,Titre FROM texte WHERE NumText=6");
					$text = mysql_fetch_row($r2);
					$titre = $text[1];
					$text = trim($text[0])."\n".$foot."\n";
					// envoie un tell
					sendTell($v1,"<a href=\"charref://1/".rand(1,10000)."/".colorize($text)."\">".$titre."</a>");
				}				
				break;
			case "guilde_chan":
				$v1=array_shift($action_buffer);
				$v2=array_shift($action_buffer);
				$aoc -> send_group($nomchan,$v1,$v2);
				break;
			case "private_chan":
				$v1=array_shift($action_buffer);
				$v2=array_shift($action_buffer);
				$aoc -> send_privgroup($priv_grp,$v1,$v2);
				break;
			case "addbuddy":
				$v1=array_shift($action_buffer);
				EchoAndLog( "Ajout de $v1 en buddy liste\n");
				$aoc -> addbuddy($v1);
				break;
			case "rembuddy":
				$v1=array_shift($action_buffer);
				EchoAndLog( "effacement de $v1 de la buddy liste\n");
				$aoc -> rembuddy($v1);
				break;
			default: EchoAndLog( "hmmm hmmm\n");break;
		}
		$lastTellTime=getmicrotime();
	}
	
	function SQL_request($r, $err = "\0")
	{
		$resu = mysql_query($r) or aff_err("$err, requete: $r, reponce de la base: ".mysql_error());
		return $resu;
	}
		
	function affblob($s)
	{
  		EchoAndLog(  "blob=");
  		for ($a=0;$a< strlen($s);$a++)
  		{
  			EchoAndLog(  ord(substr($s,$a,1))." ");
  		}
  		EchoAndLog(  "\n");
	}
	
	function getQlFrom($data)
	{
		if (strlen($data)!=18) return -1;
		$b= ord(substr($data,13,1));
		$b+=ord(substr($data,12,1))*256;
		$b+=ord(substr($data,11,1))*256*256;
		$b+=ord(substr($data,10,1))*256*256*256;
		return $b;
	}
	
	function getHKFrom($data)
	{
		if (strlen($data)!=18) return -1;
		$b= ord(substr($data,9,1));
		$b+=ord(substr($data,8,1))*256;
		$b+=ord(substr($data,7,1))*256*256;
		$b+=ord(substr($data,6,1))*256*256*256;
		return $b;
	}
	
	function getLKFrom($data)
	{
		if (strlen($data)!=18) return -1;
		$b= ord(substr($data,5,1));
		$b+=ord(substr($data,4,1))*256;
		$b+=ord(substr($data,3,1))*256*256;
		$b+=ord(substr($data,2,1))*256*256*256;
		return $b;
	}
	
	function makeBlob($id1,$id2,$ql)
	{
		$blob="                  ";
		$blob=substr_replace ($blob,chr(0),0,1);
		$blob=substr_replace ($blob,chr(0),1,1);
		$n1=intval($id1/16777216);
		$n2=intval($id1/65536)- $n1;
		$n3=intval($id1/256)- $n2*256 - $n1;
		$n4=$id1 - $n3*256 - $n2*65536 - $n1*16777216;
		$blob=substr_replace ($blob,chr($n1),2,1);
		$blob=substr_replace ($blob,chr($n2),3,1);
		$blob=substr_replace ($blob,chr($n3),4,1);
		$blob=substr_replace ($blob,chr($n4),5,1);
		$n1=intval($id2/16777216);
		$n2=intval($id2/65536)- $n1;
		$n3=intval($id2/256)- $n2*256 - $n1;
		$n4=intval($id2 - $n3*256 - $n2*65536 - $n1*16777216);
		$blob=substr_replace ($blob,chr($n1),6,1);
		$blob=substr_replace ($blob,chr($n2),7,1);
		$blob=substr_replace ($blob,chr($n3),8,1);
		$blob=substr_replace ($blob,chr($n4),9,1);
		$n1=intval($ql/16777216);
		$n2=intval($ql/65536)- $n1;
		$n3=intval($ql/256)- $n2*256 - $n1;
		$n4=intval($ql - $n3*256 - $n2*65536 - $n1*16777216);
		$blob=substr_replace ($blob,chr($n1),10,1);
		$blob=substr_replace ($blob,chr($n2),11,1);
		$blob=substr_replace ($blob,chr($n3),12,1);
		$blob=substr_replace ($blob,chr($n4),13,1);
		$blob=substr_replace ($blob,chr(0),14,1);
		$blob=substr_replace ($blob,chr(0),15,1);
		$blob=substr_replace ($blob,chr(0),16,1);
		$blob=substr_replace ($blob,chr(0),17,1);
		return $blob;
	}
	
	function fileContent ( $name )
	{
		global $char_AO;
		$f = fopen($name,"r");
		$text = fread ($f, filesize($name));
		fclose($f);

		
		
		return $text;
		
	}
	
	function getTitleOf( $name )
	{
		$f = fopen($name,"r");
		$text = fgets ($f);
		fclose($f);
		//$text= str_replace("[16,20]","",$text);
		//echo "\n\n***********\n".$text."\n\n***********\n";
		if ( !eregi("(.*) [16,18](.*) [16,20]",$text,$res))
			eregi("(.*) [16,18](.*)",$text,$res);
		$text = trim($res[2]);
		return $text;
	}
	
	function aff_err($text)
	{
		EchoAndLog(  "$text\n");
		$ferr = fopen("Err.txt","a");
		fputs($ferr,"[".date("d/m/Y H:i:s")."]:$text\n");
		fflush($ferr);
		fclose($ferr);
	}
	
	
	
	function getmicrotime()
	{
    	list($usec, $sec) = explode(" ",microtime());
    	return ((float)$usec + (float)$sec);
  	}
  	
  	function afffffff($s)
  	{
  		for ($a=0;$a< strlen($s);$a++)
  		{
  			EchoAndLog(  substr($s,$a,1).":".ord(substr($s,$a,1))."\n");
  		}
  	}
	
	function pause ($temp)
	{
		$temp=$temp/1000;
		$t1=getmicrotime();
		while ($t2-$t1<$temp ) $t2=getmicrotime();
	}
	
	function afff($data)
	{
		EchoAndLog(  "****************\n");
		for($a=0;$a<strlen($data);$a++)
		{
			EchoAndLog(  dechex(ord(substr($data,$a,1)))." ");
		}
		EchoAndLog(  "\n****************\n");
		
	}
	
	function giveMePrice( $lowID, $highID, $level, $IdPlayer )
	{
		global $rk, $char_AO;
		// recuperation du computeur literacy
		$result = SQL_request("SELECT Cl FROM membre".$rk." WHERE NumMbr=$IdPlayer");
		$CL = mysql_fetch_row($result);
		$CL = $CL[0];
		// recuperation des valeur haute et basse puis extrapol avec le level
		$result = SQL_request("SELECT Value,QlObjet,Nodrop FROM objets WHERE NumObjet=$lowID");
		$Lv = mysql_fetch_row($result);
		$nodropLv = $Lv[2];
		$Lql = $Lv[1];
		$Lv = $Lv[0];
		$result = SQL_request("SELECT Value,QlObjet,Nodrop FROM objets WHERE NumObjet=$highID");
		$Hv = mysql_fetch_row($result);
		$nodropHv = $Hv[2];
		$Hql = $Hv[1];
		$Hv = $Hv[0];
		if (($level-$Lql)<($Hql-$level)) 
			$nodrop=$nodropLv;
		else
			$nodrop=$nodropHv;
		// calcul des prix de revente
		if ( $Hql==$Lql) 
			$Dv=1; 
		else 
			$Dv = (($Hv-$Lv)/($Hql-$Lql));
		$P0 = $Lv - ( $Dv * $Lql );
		$value = intval(($level * $Dv)+$P0);
		$PrixTrader = number_format($value*(0.0007)*(100+intval($CL/40)), 0, '.', ' ');
		$PrixOmni = number_format($value*(0.0006)*(100+intval($CL/40)), 0, '.', ' ');
		$PrixClan = number_format($value*(0.0004)*(100+intval($CL/40)), 0, '.', ' ');
		$text = "With your CL of <font color=#FF0000>".$CL."</font>,This item can be sell <font color=#FF0000>$PrixTrader</font> in a Trader Shop, <font color=#FF0000>$PrixOmni</font> in an Omni Shop and <font color=#FF0000>$PrixClan</font> in a Clan or Neutral Shop.<font color=#FF0000> Price can be wrong</font>.\n";
		if ( $CL==0 ) $text.="If your CL is not ".$CL." you can change it this way : /tell ".$char_AO." cl [Number].\n";
		if ( $nodrop!=0 ) $text = "this item is NODROP. you can't sell it in a shop\n";
		//$text.="This function is not perfect, pricing on some items (ie Weapons and Armor ) is not correct.";
		return $text;
	}
	
	function callback($type, $args)
	{
		//print_r($args);
		global $rk,$tabDE,$aoc,$nb_perso,$char_AO,$perso,$deb,$fin,$flog,$log,$banktext,$shoptext,$nomchan,$clanID,$priv_grp,$maitenance,$action_buffer,$lastMessageFromGuilde;
		switch($type)
		{
			// nouveau personnage
			case 20:
			case 21:
				$result = SQL_request("LOCK TABLES membre".$rk." WRITE");
				$result = SQL_request("DELETE FROM membre".$rk." WHERE NumMbr!=$args[0] AND NomMbr LIKE \"".addslashes($args[1])."\"");
				$result = SQL_request("DELETE FROM membre".$rk." WHERE NumMbr=$args[0] AND NomMbr NOT LIKE \"".addslashes($args[1])."\"");
				$result = SQL_request("SELECT * FROM membre".$rk." WHERE NumMbr=$args[0]");
				if (mysql_num_rows($result)==0)
					$result = SQL_request("INSERT INTO membre".$rk." VALUES($args[0],\"".addslashes($args[1])."\",0,0,0) ON DUPLICATE KEY UPDATE NomMbr=\"".addslashes($args[1])."\"");
				$result = SQL_request("UNLOCK TABLES");
				//echo "Inscription du perso : $args[1]\n";
				break;
			// connecte / deco
			case 40:
				break;
			case 7:
				//print_r($args);
				$nb_perso=array_search("$char_AO",$args[1]);
				break;
			case 5:
			case 0:
				break;
			// rejoin le chan privé
			case 55:
				break;
			// quite le chan privé
			case 56:
				break;
			// parle su le chan privé
			case 57:
				break;
			// nouveau chan
			case 60:
				break;
			// parle sur le CG
			case 65:
				break;
			// recois un tell
			case 30:
				$result = SQL_request("SELECT NomMbr,TypeMbr FROM membre".$rk." WHERE NumMbr=$args[0]");
				$l=mysql_fetch_row($result);
				$char=$l[0];
				$type=$l[1];
				//if ($char!="Nehva" && $char!="Prist" && $char!="Oldchap") break;
				if (eregi("(.*)AFK(.*)",$args[1])) break;
				EchoAndLog(  "[".date("H:i:s")."][".$char."]: ".$args[1]."\n");
				$mode="help";
				$args[1]=eregi_replace(" +", " ", trim($args[1])); // retire les espaces inutiles
				if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">(.*)</a>"."$", $args[1],$res))$mode="search"; 
				if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Instruction Disc (.*)</a>"."$", $args[1],$res)) $mode="nano";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Aban Pattern of '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Aban-Bhotar Assembly of '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Aban-Bhotar-Chi Assembly '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Abhan Pattern '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Bhotaar Pattern '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Chi Pattern of '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Complete Blueptrint Pattern of '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Dom Pattern of '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Notum Crystal with Etched '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
		        if (eregi("^<a href=\"itemref://([0-9]+)/([0-9]+)/([0-9]+)\">Novictalized Notum Crystal with '(.*)'</a>"."$", $args[1],$res)) $mode="aopocket";
				if (eregi("^recipe"."$", $args[1],$res)) $mode="recipe"; 
				if (eregi("^screwdriver"."$", $args[1],$res)) $mode="screw"; 
				if (eregi("^show me recipe"."$", $args[1],$res)) $mode="recipe"; 
				if (eregi("^cl ([0-9-]+)"."$", $args[1],$res)) $mode="change_cl"; 
				if (eregi("^show me menu ([0-9-]+)"."$", $args[1],$res)) $mode="menu"; 
				if (eregi("^show me text ([0-9-]+)"."$", $args[1],$res)) $mode="text"; 
				if (eregi("^show me ([0-9-]+)"."$", $args[1],$res)) $mode="page"; 
				if (eregi("^show me item ([0-9]+)"."$", $args[1],$res)) $mode="item"; 
				if (eregi("^show me stdwea ([0-9]+)"."$", $args[1],$res)) $mode="stdwea"; 
				if (eregi("^show me stdarmalpha ([0-9a-zA-Z])"."$", $args[1],$res)) $mode="stdarm"; 
				if (eregi("^search (.*)"."$", $args[1],$res)) $mode="textsearch"; 
				if (eregi("^report (.*)"."$", $args[1],$res)) $mode="report"; 
				switch ($mode)
				{
				case "report": // NEW
					$result = SQL_request("LOCK TABLES rapport WRITE");
					$result = SQL_request("INSERT INTO rapport VALUES('',\"[".$char."][".date("d/m/Y")."][".$rk."]".str_replace("\"","\\\"",$res[1])."\",0)");					$result = SQL_request("UNLOCK TABLES");
					//$res[1]=eregi_replace("<a href=\\\"itemref://([0-9]+)//([0-9]+)//([0-9]+)\\\">(.*)</a>","http://aodb.info/showitem.asp?LowID=\\1&HiID=\\2&QL=\\3 (\\4)",$res[1]);
					mail("admin@infuweb.com.au","Recipenet report by ".$char." on ".$rk,"[".$char."][".date("d/m/Y")."][".$rk."]".str_replace("\"","\\\"",$res[1]));
					sendTell($char,"thank you ".$char.". Someone will try to read this as soon as possible");
					if ($rk == "rk1") {
					sendTell("Captainzero","[".$char."][".date("d/m/Y")."][".$rk."]".str_replace("\"","\\\"",$res[1]));
					} else if ($rk == "rk2") {
					sendTell("Captainzer0","[".$char."][".date("d/m/Y")."][".$rk."]".str_replace("\"","\\\"",$res[1]));
					}
					break;
				case "change_cl": // NEW
					if ( $res[1]>3000 ) 
						$text = "WOW !!! Oo.. I don't think you're CL is bigger than 3000...";
					else 
						if ( $res[1]<0 ) 
							$text = "??? what ?? are you dead ? how you CL can be less than 0 ? look at you're NCU, you must have a debuff.";
						else
						{
							$result = SQL_request("LOCK TABLES membre".$rk." WRITE");
							$result = SQL_request("UPDATE membre".$rk." SET Cl=$res[1] WHERE NumMbr=$args[0]");
							$result = SQL_request("UNLOCK TABLES");
							$text ="Done ! I have set your CL to $res[1].";
						}
					sendTell($char,$text);
					break;
				case "screw": // NEW
					sendTell($char,"When I say tell me a screwdriver, I mean tell me the item \"Screwdriver\", like the one you can find in shop... That's to illustrate my function ...");
					break;
				case "textsearch": // NEW
						$text = str_replace('"','\"',$res[1]);
						if ( strlen($text)>=4 )
						{
							$result = SQL_request("SELECT NumRecette, TitreRecette FROM recette, type WHERE TypeRecette=NumType AND ( Visible=1 OR TypeRecette=6 ) AND TextRecette LIKE \"%".$text."%\" ORDER BY titreRecette");
							if ( mysql_num_rows($result) )
							{
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
								$head = "[16,18] Recipes  [16,20]\n";
								$text=$head."\n";
								while ($l=mysql_fetch_row($result))
								{
									$text .= "    - #L \"$l[1]\" \"/tell $char_AO show me $l[0]\"\n";
								}
								$text .= "\n".$foot;
								sendTell($char,"Here is the page for recipe with text <a href=\"charref://1/".rand(1,10000)."/".colorize($text,"search $res[1]")."\">$res[1]</a> in it.");
							}
							else
								sendTell($char,"I don't find recipe with this text in it");
						}
						else
							sendTell($char,"The text to search have to be at least 4 chars long.");
						break;
				case "help": // NEW
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
						$r2 = SQL_request("SELECT Titre, Texte FROM texte WHERE NumText=5");
						$text = mysql_fetch_row($r2);
						$head = trim($text[0]);
						$text = trim($text[1]);
						$text = "[16,18] ".$head."  [16,20]\n\n".$text."\n\n".$foot;
						//                                       '<a href="([^"]+)">([^<]+)</a>'  
						sendTell($char,"Huumm ? what ? this is my <a href=\"charref://1/".rand(1,10000)."/".colorize($text,"help")."\">commands</a>.");
						break;
				case "aopocket": // NEW
						$boss = $res[4];
			            $boss = str_replace(" ","%20",$boss);
			            $f = fopen("http://www.tngk.com/xml-rpc/search_id.php?boss=".$boss,"r");
			    		$aopocket_reponse = "" ;
			    		while ($l = fgets($f,1024))
			    		{
			    			$aopocket_reponse .= $l;
			    		}
			            fclose($f);
			            $boss = str_replace("%20"," ",$boss);
						$text1 = "";
						$text1 = ". This is used to summon $boss. <a href=\"charref://1/1/".colorize($aopocket_reponse)."\">$boss drops</a>.";
						
						$result = SQL_request("SELECT TitreRecette, a.NumRecette, TypeRecette FROM estutilisedans a, recette b WHERE NumObjet=$res[1] AND a.NumRecette=b.NumRecette");
						$nb = mysql_num_rows($result);
						if ($nb > 0)
						{
							$text = "[16,18] Recipes that use $res[4]  [16,20]\n\n";
							$nbArmeStd=0;
							while ( $line = mysql_fetch_row($result))
							{
								if ( $line[2]!=7 )
									$text = $text.'    - #L "'.$line[0].'" "/tell '.$char_AO.' show me '.$line[1].'"'."\n";
								else
									$nbArmeStd++;
							}
							if ( $nbArmeStd!=0 ) 
								$text = $text.'    - #L "Standard Weapons ('.$nbArmeStd.')" "/tell '.$char_AO.' show me stdwea '.$res[1].'"'."\n";
							$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
							$dedi = "\n";
							if ( $tmp = mysql_fetch_row($r2))
								$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
							$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
							$foot = mysql_fetch_row($r2);
							$foot = trim($foot[0])."\n".$dedi;
							$text = $text."\n".$foot;
							sendTell($char,"This $res[0] is used in <a href=\"charref://1/".rand(1,10000)."/".colorize($text." \n","nothing")."\">".$nb." recipe(s)</a>".$text1);
						}
						else
						{
							sendTell($char,"This $res[0] is not used in any recipe I know, sorry".googleSearch($res[4]));
						}
					break;
				
				case "recipe": // NEW
					$result = SQL_request("SELECT Texte FROM texte WHERE NumText=1");
					$head = mysql_fetch_row($result);
					$head = trim($head[0])."\n";
					$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
					$dedi = "\n";
					if ( $tmp = mysql_fetch_row($r2))
						$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
					$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
					$foot = mysql_fetch_row($r2);
					$foot = trim($foot[0])."\n".$dedi;
					$result = SQL_request("SELECT COUNT(*),TypeRecette,TitreType FROM recette, type WHERE Visible=1 AND TypeRecette=NumType GROUP BY TypeRecette ORDER BY TitreType");
					$text=$head."\n";
					$nb=0;
					while ($l=mysql_fetch_row($result))
					{
						$text .= "    - #L \"$l[2] ($l[0])\" \"/tell $char_AO show me menu $l[1]\"\n";
						$nb+=$l[0];
					}
					$text .= "\n".$foot;
					sendTell($char,"<a href=\"charref://1/".rand(1,10000)."/".colorize($text,"recipe")."\">The good old recipes of Mother Bea ($nb recipes)</a>");
					break;
				case "menu": // NEW
					$result = SQL_request("SELECT NumRecette, TitreRecette FROM recette WHERE TypeRecette=$res[1] ORDER BY titreRecette");
					if ( mysql_num_rows($result) )
					{
												$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
						$r = SQL_request("SELECT TitreType FROM type WHERE NumType=$res[1]");
						$t = mysql_fetch_row($r);
						$head = "[16,18] $t[0] Recipes  [16,20]\n";
						$text=$head."\n";
						while ($l=mysql_fetch_row($result))
						{
							$text .= "    - #L \"$l[1]\" \"/tell $char_AO show me $l[0]\"\n";
						}
						$text .= "\n".$foot;
						sendTell($char,"Here is the menu page for <a href=\"charref://1/".rand(1,10000)."/".colorize($text,"menu $res[1]")."\">$t[0]</a>.");
					}
					else
						sendTell($char,"I don't know this menu number ".$res[1]);
					break;
				case "text": // NEW
					$result = SQL_request("SELECT Texte, Titre FROM texte WHERE NumText=$res[1] AND Visible=1");
					if ( $line = mysql_fetch_row($result) )
					{
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
						$text=$line[0]."\n".$foot;
						sendTell($char,"Here is the recipe page for <a href=\"charref://1/".rand(1,10000)."/".colorize($text,"text $res[1]")."\">$line[1]</a>.");
					}
					else
						sendTell($char,"I don't know this text number ".$res[1]);
					break;
				case "page": // NEW
					$text="";
					$result = SQL_request("SELECT TitreRecette, TextRecette FROM recette WHERE NumRecette=$res[1]");
					if ( $line = mysql_fetch_row($result) )
					{
						$line[1].="\n";
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
						$r2 = SQL_request("SELECT Link FROM link WHERE NumRecette=$res[1]");
						if ( mysql_num_rows($r2)!=0 )
						{
							$line[1].="[16,20]-----------------------------------------------------\nOnline Link :\n";
							while ( $l = mysql_fetch_row($r2) )
								$line[1].="- #L \"$l[0]\" \"/start $l[0]\"\n";
						}
						$text .= "[16,18] $line[0]  [16,20]\n$line[1]".$foot;
						sendTell($char,"Here is the recipe page for <a href=\"charref://1/".rand(1,10000)."/".colorize($text,$res[1])."\">$line[0]</a>.");
					}
					else
						sendTell($char,"I don't know this recipe number ".$res[1]);
					break;
				case "item": // NEW
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
					$result = SQL_request("SELECT QlObjet, NomObjet, seTrouve FROM objets WHERE NumObjet=$res[1]");
					if ( $line = mysql_fetch_row($result))
					{
						$text = "[16,18] Online Link For ".trim($line[1]).":\n\n    - #L \"Link on Auno DB\" \"/start http://auno.org/ao/db.php?id=$res[1]\"\n    - #L \"Link on MainFrame DB\" \"/start http://www.aomainframe.info/showitem.aspx?AOID=$res[1]\"\n\n".$foot;
						if ($line[2] == "bbb") {
							$foundwhere = "This item can only be made.. it cannot be looted";
						} else {
							$foundwhere = "This item can be found $line[2]";
						}
						sendTell($char,"<a href='itemref://$res[1]/$res[1]/$line[0]'>".trim($line[1])."</a> - <a href=\"charref://1/1/".colorize($text,"item ".$res[1])."\">Link on online databases</a> - ".$foundwhere);
					}
					else
						sendTell($char,"??? What is that item ?? may be it's a new one and I am not patched yet...");
					break;
				case "nano": // new
						if (substr($res[4],0,1)=="(") $res[4]= substr($res[4],1,strlen($res[4])-2);
						$name=$res[4];
						//$name=addslashes($name);
						$result = SQL_request("SELECT NumObjet, QlObjet, NomObjet FROM objets WHERE NomObjet LIKE \"%Nano%".$name."%\"");
						if ($line=mysql_fetch_row($result))
							$text1 = " - used to make <a href=\"itemref://$line[0]/$line[0]/$line[1]\">$line[2]</a>\n".giveMePrice($res[1],$res[2],$res[3],$args[0]);
						else
							$text1 = "\n".giveMePrice($res[1],$res[2],$res[3],$args[0]);
							
						$result = SQL_request("SELECT TitreRecette, a.NumRecette, TypeRecette FROM estutilisedans a, recette b WHERE NumObjet=$res[1] AND a.NumRecette=b.NumRecette");
						$nb = mysql_num_rows($result);
						if ($nb>0)
						{
							$text = "[16,18] Recipes that use: $res[4]  [16,20]\n\n";
							$nbArmeStd=0;
							while ( $line = mysql_fetch_row($result))
							{
								if ( $line[2]!=7 )
									$text = $text.'    - #L "'.$line[0].'" "/tell '.$char_AO.' show me '.$line[1].'"'."\n";
								else
									$nbArmeStd++;
							}
							if ( $nbArmeStd!=0 ) 
								$text = $text.'    - #L "Standard Weapons ('.$nbArmeStd.')" "/tell '.$char_AO.' show me stdwea '.$res[1].'"'."\n";
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
							$text = $text."\n".$foot;
							sendTell($char,"This $res[0] is used in <a href=\"charref://1/".rand(1,10000)."/".colorize($text." \n","nothing")."\">".$nb." recipe(s)</a>".$text1);
						}
						else
							sendTell($char,"This $res[0] is not used in any recipe I know, sorry".googleSearch($res[4]));
					break;
				case "search": // new
						$result = SQL_request("SELECT TitreRecette, a.NumRecette, TypeRecette FROM estutilisedans a, recette b WHERE NumObjet=$res[1] AND a.NumRecette=b.NumRecette");
						$nb = mysql_num_rows($result);
						if ($nb>0)
						{
							$text = "[16,18] Recipes that use: $res[4]  [16,20]\n\n";
							$nbArmeStd=0;
							while ( $line = mysql_fetch_row($result))
							{
								if ( $line[2]!=7 )
									$text = $text.'    - #L "'.$line[0].'" "/tell '.$char_AO.' show me '.$line[1].'"'."\n";
								else
									$nbArmeStd++;
							}
							if ( $nbArmeStd!=0 ) 
								$text = $text.'    - #L "Standard Weapons ('.$nbArmeStd.')" "/tell '.$char_AO.' show me stdwea '.$res[1].'"'."\n";
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
						$text = $text."\n".$foot;
							sendTell($char,"This $res[0] is used in <a href=\"charref://1/".rand(1,10000)."/".colorize($text." \n","nothing")."\">".$nb." recipe(s)</a>\n".giveMePrice($res[1],$res[2],$res[3],$args[0]));
						}
						else
							sendTell($char,"This $res[0] is not used in any recipe I know, sorry".googleSearch($res[4])."\n".giveMePrice($res[1],$res[2],$res[3],$args[0]));
						// clump
						$text = clump($res[2]);
						if ( $text!="") sendTell($char,"This $res[0] will give you a ".$text);
					break;
				case "stdwea": // new
						$text="";
						$result = SQL_request("SELECT TitreRecette, a.NumRecette FROM estutilisedans a, recette b WHERE NumObjet=$res[1] AND a.NumRecette=b.NumRecette AND TypeRecette=7");
						$nb = mysql_num_rows($result);
						if ($nb>0)
						{
							while ( $line = mysql_fetch_row($result))
								$text = $text.'    - #L "'.$line[0].'" "/tell '.$char_AO.' show me '.$line[1].'"'."\n";
							$r2 = SQL_request("SELECT NomObjet FROM objets WHERE NumObjet=$res[1]");
							$title = mysql_fetch_row($r2);
							$title = trim($title[0]);
							$text = "[16,18] recipe of Standard Weapons That Use this $title  [16,20]\n\n".$text;
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
							$text = $text."\n".$foot;
							sendTell($char,"This $title is used in <a href=\"charref://1/".rand(1,10000)."/".colorize($text." \n","nothing")."\">".$nb." recipe(s) of Standard Weapons</a>".$text1);
						}
						else
							sendTell($char,"There is no Standard Weapon that use this item, sorry");
					break;
				case "stdarm": // new
						$result = SQL_request("SELECT TitreRecette, NumRecette FROM recette WHERE TitreRecette LIKE \"".$res[1]."%\" AND TypeRecette=7 ORDER BY TitreRecette");
						$nb = mysql_num_rows($result);
						if ($nb>0)
						{
							while ( $line = mysql_fetch_row($result))
								$text = $text.'    - #L "'.$line[0].'" "/tell '.$char_AO.' show me '.$line[1].'"'."\n";
							$text = "[16,18] recipe of Standard Weapons  [16,20]\n\n".$text;
						$r2 = SQL_request("SELECT Texte FROM dedicace WHERE NumChar=$args[0] AND Rk='$rk'");
						$dedi = "\n";
						if ( $tmp = mysql_fetch_row($r2))
							$dedi = "[16,18]On the cover of the recipe book you can read: ".trim($tmp[0])."\n";
						$r2 = SQL_request("SELECT Texte FROM texte WHERE NumText=3");
						$foot = mysql_fetch_row($r2);
						$foot = trim($foot[0])."\n".$dedi;
							$text = $text."\n".$foot;
							sendTell($char,"Here is the <a href=\"charref://1/".rand(1,10000)."/".colorize($text." \n","stdarmalpha $res[1]")."\">".$nb." recipe(s) of Standard Weapons</a>".$text1);
						}
						else
							sendTell($char,"There is no Standard Weapon with this letter");
					break;
				default:
					EchoAndLog(  "Type inconu :$type : data=");print_r($args);EchoAndLog(  "\n");
				}
		}
	}
?>