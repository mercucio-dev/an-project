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
		header('Location: index.php');
		exit();
	}
    if(isset($_POST['podsumowanie_posta'])) {
//	    echo "ok jestes w pliku : ".$_SERVER['PHP_SELF'];
//	    echo "<pre>";
//	        print_r($_POST);
//	    echo "</pre>";
	    try{
            //$plik = fopen('./template.txt.html', 'w+');
            $plik = fopen('./template.txt.html', 'r') or die("Unable to open file!");

//            echo "<br/>before";
            $stary_plik=fread($plik,filesize("./template.txt.html"));
//            echo "<br/>after";
            $plik2 = fopen($_SESSION['sciezka'].'/txt.html', 'w+');
            fwrite($plik2,$stary_plik.'<p>'.$_SESSION['nazwa_posta']."</p>\n");
            fwrite($plik2,'<p class="header">'.$_POST['podsumowanie0']."</p><br>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie1']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie2']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie3']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie4']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie5']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie6']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie7']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie8']."</p>\n");
            fwrite($plik2,'<p class="details">'.$_POST['podsumowanie9']."</p>\n");
            fwrite($plik2,"</center></div>\n</div>\n</body>");
            $stary_plik=null;
            $_SESSION['podsumowanie'] = "/pos";
        }catch (Exception $e){
            echo $e->getMessage();
            die();
        }finally{
            fclose($plik);
            fclose($plik2);
            header('Location: index.php');
        }
    }

