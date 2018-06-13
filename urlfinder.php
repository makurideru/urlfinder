<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="_styles.css" media="screen">
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.8.0.min.js"></script>
</head>
<body>
<h1>URL-Finder</h1><br/>
<?php
if(browser() == 1)
{
	echo '<div id="baumIE">';
}
else
{
	echo '<div id="baumFF">';
}

include_once('db_connect.php');
$connection=connect();
 
//  Erstmal alle Infos aus der DB holen um sie später zu verarbeiten
$resource = mysql_query('select * from kategorien'); 
 
/*  Das Ergebnis der Query in das $kategorie Array schreiben.  
Die Datensatz ID wird als Array Key genutzt.*/
$kategorie = array();
while($row = mysql_fetch_assoc($resource)){
	$kategorie[$row['id']] = $row;
} 
 
//Ermittlung der maximalen Anzahl Ebenen 
$selektion_maxEbenen = mysql_query("SELECT MAX(ebene) FROM kategorien");
list($selektierterWert_maxEbenen) = mysql_fetch_row($selektion_maxEbenen);

//Ermittlung der maximalen ID 
$selektion_maxID = mysql_query("SELECT MAX(id) FROM kategorien");
list($anzahl_spalten_ges) = mysql_fetch_row($selektion_maxID);


 
//  Die Funktion zum erzeugen der Baumstruktur aufrufen, für jede Ebene einzeln
for($ebene=0; $ebene <= $selektierterWert_maxEbenen; $ebene++)
{
	gib_baumstruktur($kategorie, $ebene); 

	//abstand der ebenen setzen
	echo '<div style="margin:40px;"></div>';
}

 
//  Die Funktion gib_baumstruktur ruft sich rekursiv auf 
//und verteilt die parent_ids und gibt die gewünschten Ebenen aus. WICHTIGSTE FUNKTION IM GANZEN PROJEKT!*/
function gib_baumstruktur($kategorie, $ebene, $parent_id=0){

  foreach($kategorie as $key=>$value){	
    if($value['parent_id']==$parent_id){
	
	 if($value['ebene']==$ebene)
	 {
		  echo '<div class="klickbuttons"><div id="div'.$value['id'].'" class="'.$value['ebene'].'">','<input type="button" id="'.$value['id'].'" class="button" onclick="button_wurde_geklickt('.$value['id'].')" value="'.$value['name'].'"/>';
		  echo '</div></div>'; 
		  //die Button ID besteht deshalb nur aus einer Zahl, damit ich sie über Ajax vergleichen kann -> Dubletten sind erlaubt!
	  }	  
	  gib_baumstruktur($kategorie, $ebene ,$value['id']);	  	  
    }
  }
} 

echo '</div>';


//  alle Ebenen außer Ebene 0 ausblenden (Starteinstellung)
$resource = mysql_query("select DISTINCT id from kategorien WHERE ebene!=0"); 
echo "<script language='javascript'>";
 while($ebe = mysql_fetch_object($resource))
   {
		echo '$("#'.$ebe_ar[] = $ebe->id.'").css("display","none");';
   }
echo "</script>";


mysql_close($connection);
?>


<script type="text/javascript">
var anzahl_spalten_ges = <?php echo $anzahl_spalten_ges; ?> 

//all diese Variablen sind dazu da, die richtigen Buttons blau zu färben
var zuletzt_gedrueckt_ebene = new Array();
var zuletzt_gedrueckt_id = new Array();
zuletzt_gedrueckt_ebene[0] = -1;
var zuletzt_gedrueckt_counter = 0;
var zuletzt_gedrueckt_id_counter = -1;

//diese Funktion wird aufgerufen, sobald ein Button angeklickt wurde
function button_wurde_geklickt(id)
{
//------------Blaufärbung START - noch leicht buggy!!
zuletzt_gedrueckt_id_counter++;
zuletzt_gedrueckt_id[zuletzt_gedrueckt_id_counter] = document.getElementById(""+id+"").id;

	if(zuletzt_gedrueckt_ebene[zuletzt_gedrueckt_counter] >= parseInt(document.getElementById("div"+id+"").className))
	{
	for(var a = 1; a<=anzahl_spalten_ges; a++)
	{	
		$("#"+a+"").css('background-color', '#B2B2B2'); 
	}

	for(var b = 0; b<zuletzt_gedrueckt_id_counter-2; b++)
	{	
		$("#"+zuletzt_gedrueckt_id[b]).css('background-color', '#55A4F2'); 
	}
	zuletzt_gedrueckt_id_counter = parseInt(document.getElementById("div"+id+"").className)+1;
	for(var i = zuletzt_gedrueckt_id.length; i >= zuletzt_gedrueckt_id_counter; i--)
	{
	zuletzt_gedrueckt_id.pop();
	}
	}
//------------Blaufärbung ENDE - noch leicht buggy!!

ermittleChilds(id);
ermittleURL(id);

//------------Blaufärbung START (Teil2)- noch leicht buggy!!
zuletzt_gedrueckt_counter++;
zuletzt_gedrueckt_ebene[zuletzt_gedrueckt_counter] = parseInt(document.getElementById("div"+id+"").className);
//------------Blaufärbung ENDE - noch leicht buggy!!
}


function ermittleChilds(id)
{
if (id=="")
  {
  //das kann eig. gar nicht passieren
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {	
	var kinder = new Array();
	var anzeige = new Array();
	temp = xmlhttp.responseText;  //der responsetext ist ungefähr so aufgebaut: "7, 3, 9, "
	temp = temp.split(",");		//deshalb wird er hier am Komma gesplitet, damit ein funktionierendes Array entsteht
	
		for(var i = 0; i <= temp.length-1; i++)
		{
		kinder[i] = temp[i];
			for(var a = 1; a<=anzahl_spalten_ges; a++)
			{
				if(document.getElementById(""+a+"") != null) //diese if-bedingung sorgt dafür, dass auch bei fehlenden button-ids in der db alles funzt
				{
					if(document.getElementById(""+a+"").id==kinder[i])
					{
						anzeige[i] = a;			//damit die Buttons die angezeigt werden nicht im nächsten Schritt gleich wieder ausgeblendet werden speichere ich sie hier zwischen			
					}	
					else
						{
							if(parseInt(document.getElementById("div"+a+"").className) >= parseInt(document.getElementById("div"+id+"").className)+1) //alle Ebenen kleiner als, die mit dem zuletzt angeklickten Button bleiben bestehen, der Rest wird ausgeblendet
							{
								$("#"+a+"").css("display","none");							
							}
						}
				}
			}			
		}
	
	//hier blende ich alle Buttons, die in das Feld anzeige[] sortiert wurden wieder ein	
	for(var i = 0; i < temp.length-1; i++)
	{
		$("#"+anzeige[i]+"").css("display","inline");
	}
		
		 //hier wird geschaut, welche Farbe der Button hat und dementsprechend eine andere gesetzt, sowie die URL ein und ausgeblendet
		if(jQuery("#"+id+"").css('background-color') == "rgb(85, 164, 242)")
		{
		//document.getElementById("url").value=" ";
		}
		else
		{
			$("#"+id+"").css('background-color', '#55A4F2'); 
		} 
	
	}
		
  }
xmlhttp.open("GET","select_childs.php?id="+id,true);
xmlhttp.send();
	
}

//diese Funktion holt sich die URL zur gerade aktiven ID aus der DB und blendet sie unten ein
function ermittleURL(id)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
		document.getElementById("url").value=xmlhttp2.responseText;
	}
  }
xmlhttp2.open("GET","select_url.php?id="+id,true);
xmlhttp2.send();
}


//Diese Funktion sorgt dafür, dass beim Klick auf den Öffnen-Button ein neuer Tab mit der ausgewählten URL geöffnet wird
function neuerTab()
{
	if(document.getElementById("url").value != " ")
	{
		window.open(document.getElementById("url").value, '_blank');
	}
	else
	{
		alert("Bitte eine Kategorie mit URL wählen!");
	}
}

</script>


<div id="auswahl">
Url:<input id="url" name="url"  readonly="readonly" value="" type="text"/>
<input id="weiter" name="weiter" onclick="neuerTab();"  value="&Ouml;ffnen" type="submit"/>
<a href="bearbeitungsmodus.php" ><img class="editimg" src="img/bearbeiten.jpg" alt="bearbeiten"></img> Edit</a>
</div>


</body>
</html>

<?php   

function browser()
{
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';   
    if(strpos($user_agent, 'Firefox') !== false)   
    { 		
		return 0;
    }   
    elseif(strpos($user_agent, 'MSIE 7.0') or strpos($user_agent, 'MSIE 8.0')!== false)   
    {   
		return 1;
    } 
}	
  
?>