<?php 
require_once("//matt/www/RedmileTest/libraries/meekrodb.2.3.class.php"); ?>

<?php
$user =         'root';
$pass =         'somepassword';
$dbName =       'RedmileHomes';
$host =         'localhost';
$port =         '3307';

$db = new MeekroDB($host, $user, $pass, $dbName, $port);

function pre($array){
	$return="";
	$return.="<pre>";
	$return.=(print_r($array,1));
	$return.=("</pre>\n");
	return $return;
}


?>