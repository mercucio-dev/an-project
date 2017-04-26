<?php 
	session_start();
	//phpinfo();
	if( isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']==true )
	{
//		header('Location: dodaj.php');
		header('Location: przygotuj.php');
		exit();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Panel zarządzania postami</title>
</head>
<body>
<div style="width:200px; margin: auto; margin-top:10%; text-align: center;">	
	<form action="zaloguj.php" method="post">
		Login : <input type="text" name="login" /><br/>
		Hasło :	<input type="password" name="haslo" /><br/><br/>
		<input type="submit" value="zaloguj się" />
	</form>
<?php if(isset($_SESSION['blad'])) echo $_SESSION['blad'] ?>
</div>
</body>
</html>
