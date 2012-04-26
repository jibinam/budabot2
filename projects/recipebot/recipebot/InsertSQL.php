<?
// ***********************************
// ** used to insert a big SQL file **
// ***********************************
	include_once("recipebotparamrk1.php");


	$iddb=@mysql_pconnect ($SQLHost,$SQLLogin,$SQLPassword) or die("Connection impossible :".mysql_error());
	@mysql_selectdb($SQLDatabase,$iddb);
	$filename = "db_objets.sql";
	if (isset($filename))
	{
		$f = fopen($filename,"r");
		$requete = "";
		
		while ( ! feof($f) )
		{
			$line = trim(fgets($f,10240));
			if ( ! eregi("^--(.*)",$line) )
			{
				if ( ! eregi("^#(.*)",$line) )
				{
					$requete .= $line;
					if ( eregi('(.*);$',$line) )
					
					{
						mysql_query($requete) or die($requete."  ".mysql_error());
						//echo $requete."<br>\n";
						$requete="";
					}
				}
			}
		}
	}
?>
</body>
</html>