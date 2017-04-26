<?php
	function UtworzSciezke($path)
	{
		if(!is_dir($path))
		{
			$oldmask = umask(0);
			if(!mkdir($path,0777,true))
			{
				umask($oldmask);
				return false;
			}
			umask($oldmask);
			return true;
		}
		else
			return false;
	};

	function UtworzAll($sciezka)
	{
		if(!is_dir($sciezka)){
			$stara_maska = umask(0);
			if(!mkdir($sciezka,0777,true)){
				umask($stara_maska);
				return false;
			}
            umask($stara_maska);
			return true;
		}
	};

	function WalidacjaDaty($date)
	{
		$rezultat = true;
		$test_arr  = explode('-', $date);
		if (count($test_arr) == 3)
		{
		    	if (checkdate($test_arr[1], $test_arr[2], $test_arr[0]) && ($test_arr[0]>1900) && ($test_arr[0]<2100) )
			{
        			// valid date ...
				unset($_SESSION['blad_daty']);
    			}
			else
			{
        			// problem with dates ...
				$_SESSION['blad_daty'] = "Niepoprawny format daty";
				$rezultat = false;
    			}
		} else
		{
			$_SESSION['blad_daty'] = "Niepoprawny format daty za duzo albo za malo wpisanych pol";
			$rezultat = false;
		}
		return $rezultat;
	};

	function PobierzSciezke($data)
	{
		$tablica = explode('-',$data);
		if( count($tablica) == 3)
		{
			//$sciezka = "../posts/".$tablica[0]."/".$tablica[1]."/".$tablica[2]; // prawdziwa sciezka
			$sciezka = "temp/".$tablica[0]."/".$tablica[1]."/".$tablica[2];
		}
		return $sciezka;
	};

	function PrzeslijPlik($sciezka, $typ)
	{
		// typ 1 - zdjecie do galerii
		// typ 2 - miniaturka do listy postow
		// typ 3 - diament do slidera
		$opcje = array(1,2,3);
		if($_FILES["fileToUpload"]["name"] == ""){
			echo '<div class="klasa_bledu">Nie wskazałeś pliku</div>';
			return false;
		}
		if(!in_array($typ,$opcje)){
            echo '<div class="klasa_bledu">Błedna opcja typu pliku</div>';
            return false;
		}
		$target_dir = "./".$sciezka."/";
		$index = $_SESSION['liczba_zdjec'];
		//$target_file = $target_dir . ($_SESSION['liczba_zdjec']+1).;
		//echo "<br/>".print_r($_FILES);
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = true;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// sprawdzenie czy plik jest obrazem
		if(isset($_POST["przeslij"])) {
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== 0) {
			//echo '<div class="klasa_powodzenia">Plik jest obrazem - ' . $check["mime"] . ".</div>";
			$uploadOk = true;
		    } else {
			echo '<div class="klasa_bledu">Plik nie jest obrazem.</div>';
			$uploadOk = false;
			return $uploadOk;
		    }
		}

		// sprawdzenie czy plik już istnieje
		if (file_exists($target_file)) {
		    echo '<div class="klasa_bledu">Przykro mi, plik już istnieje.</div>';
		    $uploadOk = false;
			return $uploadOk;
		}
		// sprawdzenie rozmiaru pliku
		if ($_FILES["fileToUpload"]["size"] > 5500000) {
		    echo '<div class="klasa_bledu">Przykro mi, wybrany plik jest za duży.</div>';
		    $uploadOk = false;
			return $uploadOk;
		}
		// dopuszczenie wybranych typów obrazów
		if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo '<div class="klasa_bledu">Przykro mi, tylko pliki JPG, JPEG, PNG & GIF są możliwe.</div>';
		    $uploadOk = false;
			return $uploadOk;
		}

		if ($uploadOk == false) {
		    echo '<div class="klasa_bledu">Przykro mi, Twój plik nie został przesłany.</div>';
		// jeśli wszystko ok, to wrzuć plik
		} else {
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo '<div class="klasa_powodzenia">Plik '. basename( $_FILES["fileToUpload"]["name"]). ' został przesłany.</div>';
			if($typ==1) {
                array_push($_SESSION['zdjecia'], $target_file);
                $_SESSION['liczba_zdjec'] = $index + 1;
                //echo '<div class="klasa_powodzenia">Aktualna liczba zdjęć '.$_SESSION['liczba_zdjec'].'</div>';
            }
            elseif ($typ==2) {
				$_SESSION['miniaturka'] = $target_file;
                //echo '<div class="klasa_powodzenia">Przesłano miniaturkę : '.$_SESSION['miniaturka'].'</div>';
			}
			return $uploadOk;
		    } else {
			echo '<div class="klasa_bledu">Przykro mi, pojawił się jakiś błąd podczas przesyłania.</div>';
		    }
		}
		return $uploadOk;
	};

	function ZaczytajWszystkiePosty(){
		$sciezka = '../posts/posts.json';

        $string = file_get_contents($sciezka);
        $posty = json_decode($string, true);

/*        foreach ($posty as $key => $value) {
            //echo $person_a['status'];
			echo "<pre>".$key;
			echo "<br/>";
			print_r($value);
            echo "</pre>";
        }
*/
        return $posty;
	};

	function NadpiszWszystkiePosty(){
        $sciezka = '../posts/posts.json';
        $temp = './temp/';
        $random = substr( md5(rand()), 0, 8);	// random string do tworzenia katalogu
        try{
        	$OK = true;
        	// tworzenie katalogu dla backup'u;
            if(!$OK=UtworzSciezke($temp.$random)) return;
        	// backup pliku
			$OK=copy($sciezka,$temp."/".$random."/".basename($sciezka)) ? "" : print("Nie udało się zrobić kopi zapasowej pliku listy postów");
			//
            echo "<br/>Koniec pliku nadpisawynia wszystkich postów";
		}catch(Exception $e){
            echo $e->getMessage();
        };
	};

    function DodajPostaDoListy($post_tablica){
        $lista_postow = ZaczytajWszystkiePosty();
        array_unshift($lista_postow, $post_tablica);
        // tworzenie pliku json
        try{
            $plik = fopen('./temp/posts.json', 'w');
            //fwrite($plik,'['."\n");
            fwrite($plik, json_encode($lista_postow));
            //fwrite($plik,"\n".']');
            fclose($plik);
            return true;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        };
    };

    function SprwadzNaLisciePostow($sciezka){
        $posty=ZaczytajWszystkiePosty();
        $sciezka=str_replace("temp","posts",$sciezka)."/data.json";
        foreach($posty as $key => $value){
            if($sciezka==$value['link']) return true;
        }
        return false;
    }


