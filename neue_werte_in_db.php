
<?php
//diese PHP-Datei wird über die Bearbeitungsmaske aufgerufen
//und sorgt für das hinzufügen/löschen/ändern von URLs und Kategorien

include_once('db_connect.php');
$connection=connect();
$table = "kategorien";


//wird durch den 1ten Hinzufügenbutton aufgerufen (Kategorie einfügen)
if(isset($_GET["neueKat"]))
{
	$resource = mysql_query("select id, name from kategorien ORDER BY id DESC LIMIT 1");  //letzten neuen Eintrag ermitteln
	while($lastEntry = mysql_fetch_object($resource))
   {
		$letzterEintrag[] = $lastEntry->name;
   }
   
   if($_GET["neueKategorie1_name"] != $letzterEintrag[0])  //mit dieser Abfrage wird erneutes Eintrage dürch F5 verhindert
   {
	if($_GET["parentID_choice_1"] == "default" || $_GET["neueKategorie1_name"] == "") //Überprüfung ob Dropdownmenü und Textfelder korrekt ausgefüllt wurden
	{
		echo '<div class="error">Fehler: Bitte Parent_ID mit angeben und Kategorienname vergeben!</div><br/>';
	}
	else{
	$parent_id = $_GET["parentID_choice_1"];
	$name = $_GET["neueKategorie1_name"];
	$url = str_replace(' ','',$_GET["neueURL1_name"]);
	
	
	htmlentities($name);
	$name = umlaute($name);  //Umlaute im Namen ändern
	
	if(!preg_match('(http://)i', $url) && !preg_match('(https://)i', $url)) //falls http:// vergessen wurde, wird es hier angefügt
	{
		$url = "http://".$url."";
	}

	$selektion_Ebene = mysql_query("SELECT ebene FROM kategorien WHERE '$parent_id'=id"); //die Ebene der parent-kategorie holen
	list($selektierterWert_Ebene) = mysql_fetch_row($selektion_Ebene);

	$ebene = $selektierterWert_Ebene +1;
		
	if($parent_id == "keine") //falls die neue Kategorie in Eben 0 soll
	{
		$parent_id = 0;
		$ebene = 0;
	}

	$sql = "INSERT INTO $table VALUES ( '', '$parent_id', '$name', '$ebene', '$url')";
	 if($sql != "" )
     $res = mysql_query($sql) or die("SQL-Fehler: " . mysql_error());
	 
	 echo "<center>Erfolgreich eingetragen!</center><br/>";
	 echo '<input type="button" onclick="baum_zeigen();" value="jetzige Struktur anzeigen" class="button"/><br/>';
	 gib_baumstruktur_aufruf($name);
	 } 
	 }
}


//wird durch den 2ten Hinzufügenbutton aufgerufen (URL eintragen/ändern)
if(isset($_GET["neueURL"]))
{
if($_GET["parentID_choice_2"] == "default" || $_GET["neueURL2_name"] == "")
	{
		echo '<div class="error">Fehler: Bitte Parent_ID w&auml;hlen und URL eintragen</div><br/>';
	}
	else{
	
	$id = $_GET["parentID_choice_2"];
	$url = str_replace(' ','',$_GET["neueURL2_name"]);
	
	if(!preg_match('(http://)i', $url) && !preg_match('(https://)i', $url))
	{
		$url = "http://".$url."";
	}
	
	 $sql = "UPDATE $table SET url='$url' WHERE id=$id"; 
	
	
	if($sql != "" )
     $res = mysql_query($sql) or die("SQL-Fehler: " . mysql_error());
	
	echo "<center>Erfolgreich eingetragen!</center><br/>";
	echo '<input type="button" onclick="baum_zeigen();" value="jetzige Struktur anzeigen" class="button"/><br/>';
	gib_baumstruktur_aufruf("");
	}

}

//wird durch den 3ten Hinzufügenbutton aufgerufen (Kategorienamen ändern)
if(isset($_GET["neuer_name"]))
{
if($_GET["parentID_choice_3"] == "default" || $_GET["neuerName3_name"] == "")
	{
		echo '<div class="error">Fehler: Bitte ID w&auml;hlen und Name eintragen</div><br/>';
	}
	else{
	
	$id = $_GET["parentID_choice_3"];
	$name = $_GET["neuerName3_name"];
	
	htmlentities($name);
	$name = umlaute($name);  //Umlaute ändern
	
	$sql = "UPDATE $table SET name='$name' WHERE id=$id"; 
	
	
	if($sql != "" )
     $res = mysql_query($sql) or die("SQL-Fehler: " . mysql_error());
	
	echo "<center>Erfolgreich eingetragen!</center><br/>";
	 echo '<input type="button" onclick="baum_zeigen();" value="jetzige Struktur anzeigen" class="button"/><br/>';
	 gib_baumstruktur_aufruf($name);
	}
}


//wird durch den Löschbutton aufgerufen
if(isset($_GET["delKat"]))
{
if($_GET["parentID_choice_4"] == "default")
	{
		echo '<div class="error"> Fehler: Bitte ID w&auml;hlen!</div><br/>';
	}
	else{
	
	$id = $_GET["parentID_choice_4"];
	
		
	ermittle_ganzen_Ast($id);
	
	foreach($alle_ids as $key=>$value){
	
	mysql_query("DELETE FROM $table WHERE id=$value");
	echo "<center>Erfolgreich gelöscht!</center><br/>";
	 echo '<input type="button" onclick="baum_zeigen();" value="jetzige Struktur anzeigen" class="button"/><br/>';
	 gib_baumstruktur_aufruf("");
	}
	
	}
}


mysql_close($connection);	


//mit Hilfer dieser beiden Funktionen wird zu einer Kategorie jede Unterkategorie ermittelt
function ermittle_ganzen_Ast($id)
{

ermittle_ganzen_Ast_hilf($id);

	$resource = mysql_query("select * from kategorien WHERE parent_id='$id'"); 
	while($childs = mysql_fetch_object($resource))
   {
		$childs_ar[] = $childs->id;
   }
  
  if(isset($childs_ar))
  {
	foreach($childs_ar as $key=>$value){
	ermittle_ganzen_Ast($value);
	}
  }
	
}
function ermittle_ganzen_Ast_hilf($id)  // diese Hilfsfkt. macht im Prinzip nichts anderes als RETURN $all_ids
{
global $alle_ids;
$alle_ids[] =  $id;	
}




//Funktion passt die Umlaute an, Umlaute können durch Häufiges hin- und herwechseln von PCs "kaputt" gehen
function umlaute($text)
{
$search = array("ä","ö","ü","Ä","Ö","Ü","ß","'");
$replace = array("ae","oe","ue","Ae","Oe","Ue","ss","\""); //äöüÄÖÜß
$str  = str_replace($search, $replace, strval($text));
//$str = mysql_real_escape_string ($str)
return $str;
}

?>
