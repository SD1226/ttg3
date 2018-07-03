<?php
define('BASE_C', TRUE);
require 'base.php';
session_destroy();
header('Location: index.php');
?>