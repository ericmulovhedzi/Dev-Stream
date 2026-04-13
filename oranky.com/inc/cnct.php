<?php

require_once('cnfg.php');
require('fnc.php');
require_once('init.php');
include('adodb/adodb.inc.php'); 
include('adodb/tohtml.inc.php');

$db = ADONewConnection('mysql'); 
$db->PConnect($dbhost, $dbuser, $dbpass, $dbname);
?>