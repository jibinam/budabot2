<?
// *********************************************
// ** used to insert the db export to the bot **
// *********************************************
	include_once("recipebotparamrk1.php");

	function SQL_request($r, $err = "\0")
	{
		$resu = mysql_query($r) or die("$err, requete: $r, reponce de la base: ".mysql_error());
		return $resu;
	}
	


	$iddb=@mysql_pconnect ($SQLHost,$SQLLogin,$SQLPassword) or die("Connexion impossible :".mysql_error());
	@mysql_selectdb($SQLDatabase,$iddb);
// convertie l'extration XML de la base d'ao pour recipebot


$XMLInputFile = "15.09.06_EP1-xml.xml" ;
$XMLOutputFile = "monFichier.XML" ;



// ajoute les retours a la ligne

$finsize = filesize($XMLInputFile);
$fin = fopen( $XMLInputFile , "r" );
$fout = fopen( $XMLOutputFile , "w" );

$pos=0;
$lastPourcentage=-1;
while ( !feof($fin) )
{
	$buffer= fgets($fin, 4096);
	$pos+=strlen($buffer);
	$buffer=str_replace(">",">\r\n",$buffer);
	fputs($fout,$buffer);
	$pourcentage = intval($pos*100/$finsize);
	if ( $pourcentage > $lastPourcentage ) echo $pourcentage." %\n";
	$lastPourcentage=$pourcentage;
}

fclose($fin);
fclose($fout);

// recherche des objets et import dans la base
echo "remplissage de la base de donnée\n";


$f = fopen( $XMLOutputFile , "r" );
$fsize = filesize($XMLOutputFile);
$pos=0;
$lastPourcentage=-1;
while ( !feof($f) )
{
	$buffer=fgets($f, 4096);
	$pos+=strlen($buffer);
	if ( eregi("<Item AOID=\"([0-9]+)\" IsNano=\"(.*)\" Name=\"(.*)\" QL=\"([0-9]+)\" ItemType=\"(.*)\">",$buffer,$res) )
	{
		$id=$res[1];
		SQL_request("INSERT INTO objets VALUES('$id',\"".str_replace("\"","\\\"",$res[3])."\",$res[4],'',0,0) ON DUPLICATE KEY UPDATE NomObjet=\"".str_replace("\"","\\\"",$res[3])."\", QlObjet=$res[4], Nodrop=0");
	}

	if ( eregi("<Attribute Name=\"Value\" Value=\"([0-9]+)\" />",$buffer,$res) )
	{
		SQL_request("UPDATE objets SET Value=$res[1] WHERE NumObjet=$id");
	}
	
	if ( eregi("<Attribute Name=\"Flags\" Value=\"(.*)NoDrop(.*)\" />",$buffer,$res) )
	{
		SQL_request("UPDATE objets SET Nodrop=1 WHERE NumObjet=$id");
	}
		
	
	$pourcentage = intval($pos*100/$fsize);
	if ( $pourcentage > $lastPourcentage ) echo $pourcentage." %\n";
	$lastPourcentage=$pourcentage;
}
fclose($f);
?>

