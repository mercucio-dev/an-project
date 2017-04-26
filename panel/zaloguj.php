<?php
	session_start();
	//require_once "polaczenie.php";

	if( !(isset($_POST['login']) && isset($_POST['haslo'])) )
	{
		header('Location: index.php');
		exit();
	}

	try {
			$login = $_POST['login'];
			$login = htmlentities($login,ENT_QUOTES,"utf-8");
			$haslo = $_POST['haslo'];
//			$dane_do_polaczenie = 'mysql:host='.$host.';dbname='.$db_name;
//			$polaczenie = new PDO($dane_do_polaczenie, $db_user, $db_password);
			$polaczenie = new PDO('sqlite:../../../anproject.db');
			$zapytanie = $polaczenie->prepare("SELECT * FROM uzytkownik WHERE login=?");
//			$zapytanie = $polaczenie->prepare("SELECT * FROM uzytkownik");
			$zapytanie->bindValue(1, $login, PDO::PARAM_STR);
			$zapytanie->execute();
			$polaczenie = null;
			if(count($zapytanie)>0){
				$wiersz=$zapytanie->fetchAll(PDO::FETCH_ASSOC);
				if(password_verify($haslo,$wiersz[0]['password']))
				{
					$_SESSION['zalogowany'] = true;
					$_SESSION['user'] = $wiersz[0]['login'];
					unset($_SESSION['blad']);
					header('Location: dodaj.php');
					die();
				}
				else{
					$_SESSION['blad'] = '<span style="color:red;">Niepoprawne hasło</span>';
					unset($_SESSION['zalogowany']);
					header('Location: index.php');
					die();
				}
			}else{
				$_SESSION['blad'] = '<span style="color:red;">Niepoprawny login lub hasło</span>';
				unset($_SESSION['zalogowany']);
				header('Location: index.php');
				die();
			}
	}catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			$polaczenie = null;
	}
