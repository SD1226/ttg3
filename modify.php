<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest'))
	die('<h1>Error: Direct Access Denied.</h1>');
define('BASE_C', TRUE);
define('CONN_C', TRUE);
require 'base.php';
require 'connect.php';
if(!loggedin())
	die('<h1>You are not logged in.</h1>');
$user_id = $_SESSION['id'];
$title = format($_POST['title']);
$text = format($_POST['text']);
$edate = $_POST['edate'];
$note_id = $_POST['note_id'];
try
	{    
		$query = "UPDATE notes SET title = '$title', text = '$text', edate = '$edate', user_id = '$user_id'
		        WHERE note_id = :id";
        $query_run = $conn->prepare($query);
		$query_run->bindParam(':id',$note_id);
	    if($query_run->execute())
		{
		    $data->note = '<div class="ntitle">'. $title.'</div><button class="pin"></button>'.
	        '<div class="ncontent">'.$text.'</div><div class="date">Last Edited: '.$edate.'</div>'.
	        '<button class="edit">EDIT</button><button class="delete">DELETE</button>';
			$conn = null;
			echo json_encode($data);
		}
		else
		{
			$conn = null;
			echo false;
		}
	}
catch(PDOException $e)
	{
		die(false);
	}
?>