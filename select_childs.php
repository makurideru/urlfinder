<?php
//Dieses Skript wird über Ajax aufegrufen, um die Kinder-Elemente zu ermitteln

include_once('db_connect.php');
$connection=connect();

//  $id ist in dem Fall die Parent-ID, zu der die "Childs" gesucht werden
if(isset($_GET["id"]))
{
$id=$_GET["id"];
$resource = mysql_query("select * from kategorien WHERE parent_id='$id'"); 
 while($childs = mysql_fetch_object($resource))
   {
    echo $childs_ar[] = $childs->id;
	echo",";
   }
}
 	   


mysql_close($connection);
?> 