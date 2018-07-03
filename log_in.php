<?php
if(!defined('INDEX'))
	header('Location: index.php');
require 'connect.php';
$name = $pword = $nameErr = $pwordErr = '';
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name = format($_POST["name"]);
	$pword = format($_POST["pword"]);
    if(empty($name))
    {
		$nameErr = 'Userame is required.';
	}
    if(empty($pword))
	{
		$pwordErr = 'Password is required.';
	}	
	if(!empty($name) && !empty($pword))
	{
		try
		{
			$query = 'SELECT id, password FROM users WHERE username = :name';
            $query_run = $conn->prepare($query);
	        $query_run->bindParam(':name',$name);
	        $query_run->execute();
			if($query_run->rowCount() == 0)
			{
				$nameErr = 'Username does not exist.';
			}
			else if(($data = $query_run->fetch(PDO::FETCH_OBJ))&&(password_verify($pword, $data->password)))
			{
				$_SESSION['id'] = $data->id;
				$conn = null;
				header('Location: index.php');
			}
			else 
			{
			     $pwordErr = 'Incorrect password.';
			}
		}
	    catch(PDOException $e)
		{
	        die('<h1>Connection failed!</h1>');
		}	
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title> Log in
</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="icon" href="notebook.png" sizes="any">
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
</head>
<body>
<div class="formBox">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
Username:<br />
<input type="text" name="name" maxlength=30 value="<?php echo $name; ?>" /><br />
<?php
if(!empty($nameErr))
echo '<div class="err">'.$nameErr.'</div>';	
?>
<br />Password:<br />
<input type="password" name="pword" /><br />
<?php
if(!empty($pwordErr))
echo '<div class="err">'.$pwordErr.'</div>';	
?>
<br /><button class="fButton" type="submit" >Log in</button><br /><br />
<small>
Don't have a account? <a href="sign_up.php" class="fLink">Sign up</a>
</small>
</form>
</div>
</body>
</html>