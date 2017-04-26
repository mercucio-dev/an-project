<?php
	session_start();
	//require_once "polaczenie.php";
    if( !(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']==true ) )
    {
        header('Location: index.php');
        exit();
    }
	if(isset($_POST['anulowanie']))
	{
		unset($_POST['anulowanie']);
		header('Location: dodaj.php');
		exit();
	}
	if(isset($_POST['zmiana']))
	{
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		//sprawdzenie czy hasła są równe
		if( $haslo1 != $haslo2 )
		{
			unset($_POST['zmiana']);
			$_SESSION['log_zmiany_hasla'] = '<div class="klasa_bledu"> Wprowadzone hasła nie są identyczne </div>';
			header('Location: dodaj.php#');
			exit();
		}
		elseif($haslo1 =='')
		{
			unset($_POST['zmiana']);
			$_SESSION['log_zmiany_hasla'] = '<div class="klasa_bledu"> Wprowadzone puste hasła </div>';
			header('Location: dodaj.php#');
			exit();
		}

	}
// zmienić query na exec !!!
		try {
					$nowe_haslo = password_hash($haslo1, PASSWORD_DEFAULT);
					$login = $_SESSION['user'];
//				$dane_do_polaczenie = 'mysql:host='.$host.';dbname='.$db_name;
//		    $polaczenie = new PDO($dane_do_polaczenie, $db_user, $db_password);
						$polaczenie = new PDO('sqlite:../../../anproject.db');
						//$update = sprintf("UPDATE uzytkownik SET password = '%s' where login='%s'", $nowe_haslo, $login);
						$zapytanie = $polaczenie->prepare("UPDATE uzytkownik SET password = ? WHERE login= ?");
						$zapytanie->bindValue(1, $nowe_haslo, PDO::PARAM_STR);
						$zapytanie->bindValue(2, $login, PDO::PARAM_STR);
//						if($polaczenie->exec() == true) {
						if($zapytanie->execute()) {
								$_SESSION['log_zmiany_hasla'] = '<div class="klasa_powodzenia">Hasło zostało zmienione</div>';
								header('Location: index.php');
						}
						else {
								$_SESSION['log_zmiany_hasla'] = '<div class="klasa_bledu">Nie można było zmienić hasła</div>';
								header('Location: index.php');
							}
		}catch (PDOException $e) {
		    print "Error!: " . $e->getMessage() . "<br/>";
		    die();
		}finally{
			$polaczenie = null;
		}
