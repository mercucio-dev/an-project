<?php
	session_start();
	require_once "polaczenie.php";
	require_once "funkcje.php";
	if( !(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']==true ) )
	{
		header('Location: index.php');
		exit();
	}

$gdzie_przeniesc = "../posts";
$co_przeniesc = explode('/',$_SESSION['sciezka']);
$miescje_do_miniaturek = '../posts/all/';
$nazwa_nowej_miniaturki = $miescje_do_miniaturek."post".(count(scandir($miescje_do_miniaturek))-1).".jpg";
$sciezka_do_listy_postow = "../posts/posts.json";
$tymczasowa_lista_postow = "temp/posts.json";
$sciezka_do_podsumowania = $_SESSION['sciezka']."/txt.html";
try{
	$plik = fopen($_SESSION['sciezka'].'/data.json', 'a');
	$item = array();
	fwrite($plik,'{"gallery_items": [');
	foreach($_SESSION['zdjecia'] as $key => $value){
		$item['url'] = str_replace("./temp","posts",$value);
		$item['title'] = $_SESSION['nazwa_posta'];
		$item['type'] = "image";
        ++$key==count($_SESSION['zdjecia']) ? fwrite($plik,"\n".json_encode($item)) : fwrite($plik,"\n".json_encode($item));
        if($key==count($_SESSION['zdjecia'])){
            if(isset($_SESSION['podsumowanie'])) fwrite($plik,",");
        }else{
            fwrite($plik,",");
        }
	};
	fwrite($plik,"\n");
	if(isset($_SESSION['podsumowanie'])){
        $podsumowanie = '{"url" : "'.str_replace("temp/","posts/",$sciezka_do_podsumowania).'"'.",\n".'"title" : "'.$_SESSION['nazwa_posta'].'"'.",\n".'"type" : "ajax"}';
        fwrite($plik,$podsumowanie);
        $podsumowanie=null;
    };
	fwrite($plik,']}');
	fclose($plik);
	$sciezka_do_utworzenia = $gdzie_przeniesc.'/'.$co_przeniesc[1].'/'.$co_przeniesc[2].'/'.$co_przeniesc[3];
	$oldmask = umask(0);
	mkdir($sciezka_do_utworzenia,0777,true);
	umask($oldmask);
	foreach ($_SESSION['zdjecia'] as $key => $value) {
		$plik_tymczasowy = explode('/',$value);
		copy($value,$sciezka_do_utworzenia.'/'.$plik_tymczasowy[5]) ? unlink($value) : print("Nie udało się skopiowac pliku");
	}
	if(isset($_SESSION['podsumowanie'])){
        copy($sciezka_do_podsumowania,$sciezka_do_utworzenia.'/txt.html') ? unlink($sciezka_do_podsumowania) :  print("Nie udało się skopiowac pliku podsumowania");
    };
	unset($_SESSION['zdjecia']);
    unset($_SESSION['liczba_zdjec']);
	unset($plik_tymczasowy);
	if(copy($_SESSION['sciezka'].'/data.json',$sciezka_do_utworzenia.'/data.json')){
        unlink($_SESSION['sciezka'].'/data.json');
        rmdir($_SESSION['sciezka']);
        rmdir($co_przeniesc[0]."/".$co_przeniesc[1]."/".$co_przeniesc[2]);
        rmdir($co_przeniesc[0]."/".$co_przeniesc[1]);
        unset($_SESSION['sciezka']);
        unset($_SESSION['log_sciezki']);
        unset($_SESSION['czy_wyslano_plik']);
    }else{
        print("Nie udało się skopiowac pliku data.json");
    }
	// kopiowanie miniaturki
    //if(copy($_SESSION['miniaturka'], $miescje_do_miniaturek.basename($_SESSION['miniaturka']))){
    if(copy($_SESSION['miniaturka'],$nazwa_nowej_miniaturki)){
        unlink($_SESSION['miniaturka']);
        rmdir(dirname($_SESSION['miniaturka']));
        unset($_SESSION['log_sciezkaAll']);
        unset($_SESSION['czy_wyslano_plik_mini']);
    }else{
        echo "<br/>Nie udalo sie usunąć ścieżki tymczasowej dla miniaturki";
    }
    // przygotowanie wpisu nowego posta do listy postów
    $nowyPost = array();
    $nowyPost["link"] = substr($sciezka_do_utworzenia.'/data.json',3);
    $nowyPost["img"] = substr($nazwa_nowej_miniaturki,3);
    $nowyPost["alt"] = $_SESSION['nazwa_posta'];
    unset($_SESSION['nazwa_posta']);
    unset($_SESSION['log_nazwa_posta']);
    unset($_SESSION['miniaturka']);
    if(DodajPostaDoListy($nowyPost)){
        unset($nowyPost);
        unlink($sciezka_do_listy_postow);
        copy($tymczasowa_lista_postow,$sciezka_do_listy_postow);
        unlink($tymczasowa_lista_postow);
    };
    unset($_SESSION['data_posta']);
    unset($_SESSION['log_daty']);
    unset($_SESSION['sciezka_do_wszystkich_postow']);
    unset($_SESSION['podsumowanie']);
	header('Location: index.php');
}catch(Exception $e){
	echo $e->getMessage();
}

