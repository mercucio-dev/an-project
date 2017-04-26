<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Panel zarządzania postami</title>
</head>
<body>
<form method="post">
	<input type="text" name="haslo" />
	<input type="submit" name="haszuj"/>
</form>
<?php
	phpinfo();

/*	if(isset($_POST['haszuj']))
	{
		var_dump(password_hash($_POST['haslo'], PASSWORD_DEFAULT));
	} */

?>

</div>
</body>
</html>
