<?php
//Dieses Skript wird im Editmode über Ajax aufegrufen, um den Namen des angeklickten Buttons zu ermitteln

include_once('db_connect.php');
$connection=connect();


//  $id ist in dem Fall die Parent-ID, zu der die URL gesucht werden
if(isset($_GET["id"]))
{
$id=$_GET["id"];
$resource = mysql_query("select name from kategorien WHERE id='$id'"); 
 while($row = mysql_fetch_object($resource))
   {
    echo $name_ar = $row->name;
   }	
}
 	   


mysql_close($connection);
?> 