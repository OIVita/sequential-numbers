<?php
    session_start();
    $urlPagina = "index.php";
    $titoloGioco = "10 e lode!";
    $descrizioneGioco = "Vinci posizionando i numeri<br>in ordine crescente da 1 a 1000";
    require_once "db.php";
    require_once "functions.php";
    $da = 1;
    $al = 1000;
    $db = new Db();
    if (isset($_POST["reset"])) {
        $username = $_SESSION["username"];
        $scelta = $_SESSION["scelta"];
        session_destroy();
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["scelta"] = $scelta;
        header("location: $urlPagina");
        die();
    }
    if (isset($_POST["start"])) {
        $username = $_SESSION["username"] = $_POST["username"];
        $_SESSION["inizio"] = microtime(true);
        $_SESSION["start"] = "start";
        $_SESSION["fase"] = 0;
        $_SESSION["scelta"] = $quanti = $_POST["scelta"];
        $_SESSION["estratti"] = $estratti = generaNumeri($da, $al, $quanti);
        $_SESSION["posizionati"] = array_fill(0, $quanti, null);
    }
    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '    <title>' . $titoloGioco . '</title>';
    echo '    <link rel=stylesheet href="style.css?ver=' . time() . '">';
    echo '</head>';
    echo '<body>';
    echo '    <div class="titolo">' . $titoloGioco . "</div>";
    echo '    <div class="titolo2">' . $descrizioneGioco . "</div>";
    mostramenu();
    if (!isset($_SESSION["start"])) {
        if (isset($_SESSION["username"])) {
            $username = $_SESSION["username"];
        } else {
            $_SESSION["username"] = "Anonimo";
            $username = $_SESSION["username"];
        }
        echo '<div class="benvenuto">Benvenuto, ' . $username . "!</div>";
            echo '<div class="menuClassifica">';
        if (!isset($_POST["quanti"])) {
            if (isset($_SESSION["scelta"])) {
                $quanti = $_SESSION["scelta"];
            } else {
                $quanti = 3;
            }
        } else {
            $quanti = $_POST["quanti"];
        }
        mostraClassifica($quanti, $db);
    } else {
        if (isset($_SESSION["start"])) {
            if (isset($_SESSION["vittoria"])) {
                $_SESSION["fase"]--;
                header("location: $urlPagina");
                die();
            }
            $quanti = $_SESSION["scelta"];
            $posizioni_libere = array_keys($_SESSION["posizionati"], null);
            $numeroDiTurno = $_SESSION["estratti"][$_SESSION["fase"]];
            $numero_valido = false;
            foreach ($posizioni_libere as $posizione) {
                $numero = [$posizione => $numeroDiTurno];
                $posizionati = $_SESSION["posizionati"];
                if (controlla($posizionati, $numero, $quanti)) {
                    $numero_valido = true;
                    break;
                }
            }
            mostraMessaggioFinale($numero_valido, $numeroDiTurno);
            eseguiTurnoDiGioco($numeroDiTurno,$quanti,$urlPagina,$db);  
        }
        mostraBottoni($quanti);
    }
    if (isset($_SESSION["messaggio"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
        echo '<div class="errore">' . $_SESSION["messaggio"] . "</div>";
        unset($_SESSION["messaggio"]);
    }
    if(isset($_SESSION["start"])){
        echo '<div id="timer">x</div>';
    }
    //include("debug.php");//debug
    include("scripts.js"); 
?>
</body>
</html>
