<?php
define('INDEX', TRUE);
define('BASE_C', TRUE);
define('CONN_C', TRUE);
require 'base.php';
if(loggedin())
	include 'home.php';
else
	include 'log_in.php';
?>