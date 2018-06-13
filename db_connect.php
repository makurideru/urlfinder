<?php
function connect()
{
	$host = "localhost";
	$user = "root";
	$password = "";
	$db_con = mysql_connect($host,$user,$password);
	if (!$db_con)
	{
		die( "Es konnte keine Verbindung aufgebaut werden: " . mysql_error() );
	}
	else
	{
		mysql_select_db("urlfinder");
		return $db_con;
	}
}
?>