<?php
	session_start();
	if( !(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']==true ) )
	{
		header('Location: index.php');
		exit();
	}
	//$_SESSION['log'];
	require_once "funkcje.php";
	if(!isset($_SESSION['liczba_zdjec']))
		$_SESSION['liczba_zdjec']=0;
	else
	{
		if($_SESSION['liczba_zdjec']>0)
		{
			for($i = 0; $i < $_SESSION['liczba_zdjec']; ++$i)
			{
				if(isset($_POST['usun'.$i]))
				{
					if(unlink($_SESSION['zdjecia'][$i]))
					{
						array_splice($_SESSION['zdjecia'], $i, 1);
						$_SESSION['liczba_zdjec']--;
					}
				}
			}
		}
	}
    if(isset($_POST['usun_mini'])) {
        unlink($_SESSION['miniaturka']);
        unset($_SESSION['miniaturka']);
        unset($_SESSION['czy_wyslano_plik_mini']);
    }

	if(!isset($_SESSION['zdjecia'])) $_SESSION['zdjecia'] = array();
	if(!isset($_SESSION['sciezka_do_wszystkich_postow'])) $_SESSION['sciezka_do_wszystkich_postow'] = 'temp/all';
	if(isset($_POST['data_posta']))
	{
		if($walidacja_daty = WalidacjaDaty($_POST['data_posta']))
		{
			$_SESSION['log_daty'] = '<div class="klasa_powodzenia">Wpisana data jest OK</div>';
			$_SESSION['data_posta'] = $_POST['data_posta'];
			$_SESSION['sciezka'] = PobierzSciezke($_POST['data_posta']);
			if(SprwadzNaLisciePostow($_SESSION['sciezka'])){
                $_SESSION['log_sciezki'] = '<div class="klasa_bledu">Istnieje już post z taką datą (scieżką): '.$_SESSION['sciezka'].'</div>';
                unset($_SESSION['sciezka']);
                unset($_SESSION['data_posta']);
                unset($_SESSION['log_daty']);
            }else{
                if(UtworzSciezke($_SESSION['sciezka']))
                    $_SESSION['log_sciezki'] = '<div class="klasa_powodzenia">Utworzone nowa sciezke: '.$_SESSION['sciezka']."</div>";
                else
                    $_SESSION['log_sciezki'] = '<div class="klasa_bledu">Nie udalo sie utworzyc sciezki: '.$_SESSION['sciezka'].'</div>';
                UtworzAll($_SESSION['sciezka_do_wszystkich_postow']) ? $_SESSION['log_sciezkaAll'] = '<div class="klasa_powodzenia">Utworzono ścieżkę dla miniaturki</div>' : '<div class="klasa_bledu">Nie udalo sie utworzyc sciezki dla miniaturki</div>';
            }
		}
		else
			$_SESSION['log_daty'] = '<div class="klasa_bledu">Błędna data</div>';
		unset($_POST['data_posta']);
	};

	if(isset($_POST['nazwa_posta']))
	{
		if($_POST['nazwa_posta'] ==='')	$_SESSION['log_nazwa_posta'] = '<div class="klasa_bledu">Pusta nazwa posta !</div>';
		else
		{
			$_SESSION['log_nazwa_posta'] = '<div class="klasa_powodzenia">Nazwa tworzonego posta: '.$_POST['nazwa_posta'].'</div>';
			$_SESSION['nazwa_posta'] = $_POST['nazwa_posta'];
		}
		unset($_POST['nazwa_posta']);
	};

    if(isset($_POST['przeslij'])) PrzeslijPlik($_SESSION['sciezka'],1) ? $_SESSION['czy_wyslano_plik'] = true : "";
    if(isset($_POST['miniaturka'])) PrzeslijPlik($_SESSION['sciezka_do_wszystkich_postow'],2) ? $_SESSION['czy_wyslano_plik_mini'] = true : "";
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Panel zarządzania postami</title>
	<link href="../css/animate.css" rel="stylesheet">
	<link href="panel_styl.css" rel="stylesheet">
</head>
<body>
<div>
	<?php echo "Witaj ".$_SESSION['user']; ?>
	<br/><b><a href="wyloguj.php">wyloguj</a>&nbsp;&nbsp;<a href="#" id="zmien_haslo">zmień hasło</a></b>

</div>
<div id="zmiana_hasla" class="">
	    	<h3>Zmiana hasła dla <?php echo $_SESSION['user'] ?></h3>
	    	<form action="zmien_haslo.php" id="formularz_zmiany_hasla" method="post">
	      		<fieldset>
			Podaj nowe hasło:&nbsp;&nbsp;<input type="password" name="haslo1" id="haslo1" class="form-control"  /><br/><br/>
			Podaj je ponownie:&nbsp;<input type="password" name="haslo2" id="haslo2" class="form-control" /><br/><br/>
			<center><input type="submit" value="zmień hasło" name="zmiana" id="zmiana"/>
			<input type="submit" value="anuluj" name="anulowanie" class="cancel" /></center>
	      		</fieldset>
	    	</form>
</div>
<div class="kolumna" id="kolumna1">
	<br/><br/><center>
	<form method="post">
		<div class="etykieta">Podaj date: </div><input type="date" name="data_posta" min="1900-01-01" max="2099-12-31" value="<?php echo (isset($_SESSION['data_posta'])) ? $_SESSION['data_posta'] : date('Y-m-d');?>" />
		<br/><br/>
		<div class="etykieta">Wpisz nazwę: </div><input type="text" name="nazwa_posta" value="<?php echo (isset($_SESSION['nazwa_posta'])) ? $_SESSION['nazwa_posta'] : "" ?>" />
		<br/><br/><input type="submit" value="utwórz post"/>
	</form></center>
	<br/>
	<p>Zdjecia do posta</p>
	<form method="post" enctype="multipart/form-data">
            <label>Wybierz plik:</label>
    		<input type="file" name="fileToUpload" id="fileToUpload">
    		<input type="submit" value="Prześlij plik" name="przeslij" <?php echo (isset($_SESSION['sciezka'])) ? " " : 'disabled="disabled"' ?>>
	</form>
    <br/><p>Miniaturka do listy postów (wymiary 655 x 665 pixeli)</p>
    <form method="post" enctype="multipart/form-data">
        <label>Wybierz plik:</label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Prześlij miniaturkę" name="miniaturka" <?php echo (isset($_SESSION['sciezka'])) ? " " : 'disabled="disabled"' ?>>
    </form><br/><br/>
    <?php if(!isset($_SESSION['sciezka'])) echo '<div class="disabled do_srodka">';
    else echo '<div class="enabled do_srodka">';?>
        <a href="#" id="podsumowanie_link"><button>Podsumowanie posta</button></a>
    </div>
</div>
<div id = "podsumowanie" class="" >
    <h3><?php echo $_SESSION['nazwa_posta'] ?></h3>
    <form action="podsumowanie.php" id="formularz_podsumowania" method="post">
            Opis: <textarea rows="4" cols="70" name="podsumowanie0" id="podsumowanie0"></textarea><br/><br/>
            Dodatkowe pola:<br/>
            <input type="text" name="podsumowanie1" id="podsumowanie1"/><br/>
            <input type="text" name="podsumowanie2" id="podsumowanie2"/><br/>
            <input type="text" name="podsumowanie3" id="podsumowanie3"/><br/>
            <input type="text" name="podsumowanie4" id="podsumowanie4"/><br/>
            <input type="text" name="podsumowanie5" id="podsumowanie5"/><br/>
            <input type="text" name="podsumowanie6" id="podsumowanie6"/><br/>
            <input type="text" name="podsumowanie7" id="podsumowanie7"/><br/>
            <input type="text" name="podsumowanie8" id="podsumowanie8"/><br/>
            <input type="text" name="podsumowanie9" id="podsumowanie9"/><br/><br/>
            <input type="submit" value="Zapisz" name="podsumowanie_posta" id="podsumowanie_posta"/>
            <input type="submit" value="anuluj" name="anulowanie" class="cancel" />
    </form>
</div>
<div class="kolumna" id="kolumna2">
	<div style="width:80%; margin: auto; margin-top:1%; text-align: center;">
		<img src="../images/logotyp_red.png"/>
		<h1>Dodawanie posta</h1>
	</div>
	<div style="width: 100%; margin: auto;">
		 <center>
			 <form method="post" action="utworz.php">
				 	<input type="submit" value="Utwórz post" class="duzy_przycisk" name="przeslij" <?php echo (isset($_SESSION['czy_wyslano_plik'])&&isset($_SESSION['czy_wyslano_plik_mini'])) ? " " : 'disabled="disabled"' ?>>
			 </form>
	 	 </center>
	</div>
</div>
<div class="kolumna" id="kolumna3">
	<br/><br/>
		<?php
			if(isset($_SESSION['log_daty'])) echo $_SESSION['log_daty'];
			if(isset($_SESSION['log_nazwa_posta'])) echo $_SESSION['log_nazwa_posta'];
			if(isset($_SESSION['log_sciezki'])) echo $_SESSION['log_sciezki'];
            if(isset($_SESSION['log_sciezkaAll'])) echo $_SESSION['log_sciezkaAll'];
            if(isset($_SESSION['czy_wyslano_plik_mini'])){
                echo '<form method="post">';
                echo '<div class="klasa_powodzenia"><input type="submit" value="X" name="usun_mini"> Przesłano miniaturkę: '.basename($_SESSION['miniaturka']).'</div>';
                echo '</form>';
            }
			if($_SESSION['liczba_zdjec']>0){
				$lokalny_index = 0;
				echo '<form method="post">';
				foreach($_SESSION['zdjecia'] as $zdjecie)
				{
					echo '<div class="klasa_powodzenia"><input type="submit" value="X" name="usun'.$lokalny_index.'"> Przesłano zdjęcie: '.basename($zdjecie).'</div>';
					$lokalny_index++;
				}
				echo '</form>';
			}

			if(isset($_SESSION['log_zmiany_hasla'])){
				echo $_SESSION['log_zmiany_hasla'];
				unset($_SESSION['log_zmiany_hasla']);
			}
			// diagnotyka
//            echo "<br/>to co jest w session: <pre>";
//                var_dump($_SESSION);
//            echo "</pre>";
		?>
</div>
	<script src="../js/jquery-3.1.1.min.js"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.js"></script>
	<script src="script.js"></script>

</body>
</html>
