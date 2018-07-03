<?php
if(!defined('BASE_C'))
	die('<h1>Direct Access Denied.</h1>');
session_start();
ob_start();
function loggedin()
{
	if(isset($_SESSION['id']) && !empty($_SESSION['id']))
		return true;
	else
		return false;
}
function format($data)
{
	return htmlspecialchars(stripslashes(trim($data)));
}
?>