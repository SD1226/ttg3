<?php
if(!defined('INDEX'))
	header('Location: index.php');
require 'connect.php';
try
	{
		$list = array();
		$i = 0;
		$id = $_SESSION['id'];
		$query = 'SELECT username FROM users WHERE id = :id';
        $query_run = $conn->prepare($query);
	    $query_run->bindParam(':id',$id);
	    $query_run->execute();
	    $data = $query_run->fetch(PDO::FETCH_OBJ);
		$name = $data->username;
		$query = 'SELECT note_id, title, text, edate, pin FROM notes WHERE user_id = :id';
		$query_run = $conn->prepare($query);
	    $query_run->bindParam(':id',$id);
	    $query_run->execute();
	    while($data = $query_run->fetch(PDO::FETCH_OBJ))
		{
			$list[$i] = 
			array('pin'=>$data->pin, 'note_id'=>$data->note_id, 'title'=>$data->title, 'text'=>$data->text, 
			'edate'=>$data->edate);
		    $i++;
		}
		$conn = null;
	}
catch(PDOException $e)
	{
	    die('<h1>Connection failed!</h1>');
	}	
?>
<!DOCTYPE html>
<html>
<head>
<title>HOME
</title>
<link rel="stylesheet" type="text/css" href="style2.css" />
<link rel="icon" href="notebook.png" sizes="any">
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
</head>
<body>
    <div id="header">
		<img src="dd.png" id="dd" />
        My Notes
	</div>
<div id="content">
<?php 
$j = 0;
while($j < $i)
{
	echo '<div class="note">'.
	'<div class="ntitle">'. $list[$j]['title'].'</div><button class="pin"></button>'.
	'<div class="ncontent">'.$list[$j]['text'].'</div><div class="date">Last Edited: '.$list[$j]['edate'].'</div>'.
	'<button class="edit">EDIT</button><button class="delete">DELETE</button></div>';
	$j++;
}
?>
</div>
<div id="dBar">
    <span id="user"><?php echo $name; ?></span><br />
    <a href="edit_profile.php">Edit Profile</a><br />
    <a href='log_out.php'>Log out</a>
</div>
<button id="add">
 + Add Note
</button>
</body>
<script>
var ids = [];
var pins = [];
<?php
$j = 0;
while($j < $i)
{
	echo 'ids['.$j.'] = '.$list[$j]['note_id'].';';
	echo 'pins['.$j.'] = '.$list[$j]['pin'].';';
	$j++;
}
?>
</script>
<script src="jquery-3.3.1.min.js"></script>
<script src="script.js"></script>
</html>