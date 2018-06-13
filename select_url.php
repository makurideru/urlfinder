<?php
//Dieses Skript wird über Ajax aufegrufen, um die Url des angeklickten Buttons zu ermitteln

include_once('db_connect.php');
$connection=connect();


//  $id ist in dem Fall die Parent-ID, zu der die URL gesucht werden
if(isset($_GET["id"]))
{
$id=$_GET["id"];
$resource = mysql_query("select url from kategorien WHERE id='$id'"); 
 while($row = mysql_fetch_object($resource))
   {
    echo $url_ar = $row->url;
   }	
}
 	   


mysql_close($connection);
?> 