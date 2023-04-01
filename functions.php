<?php
function controlla($posizioni, $numero, $quanti) {
    $posizione = key($numero);
    $valore = $numero[$posizione];
    $posizione_precedente = $posizione - 1;
    while ($posizione_precedente >= 0 && $posizioni[$posizione_precedente] === null) {
        $posizione_precedente--;
    }
    if ($posizione_precedente >= 0 && $posizioni[$posizione_precedente] !== null) {
        if ($valore <= $posizioni[$posizione_precedente]) {
            return false;
        }
    }
    $posizione_successiva = $posizione + 1;
    while ($posizione_successiva < $quanti && $posizioni[$posizione_successiva] === null) {
        $posizione_successiva++;
    }
    if ($posizione_successiva < $quanti && $posizioni[$posizione_successiva] !== null) {
        if ($valore >= $posizioni[$posizione_successiva]) {
            return false;
        }
    }
    $posizioni[$posizione] = $valore;
    for ($i = 0; $i < $quanti; $i++) {
        if ($posizioni[$i] === null || $posizioni[$i] !== $i) {
            return true;
        }
    }
}

function generaNumeri($min, $max, $num) {
    $estratti = [];
    while (count($estratti) != $num) {
        $numero = rand($min, $max);
        if (!in_array($numero, $estratti)) {
            $estratti[] = $numero;
        }
    }
    return $estratti;
}

function formatSeconds($seconds) {
    $ss = str_pad(floor($seconds), 2, "0", STR_PAD_LEFT);
    $xx = str_pad(round(($seconds - floor($seconds)) * 100), 2, "0", STR_PAD_LEFT);
    return $ss . ":" . $xx;
}

function mostramenu(){

    echo '    <div class="menu">';
    echo '    <form method="post" style="display:inline-block">';
    echo '    <input type="submit" class="nav"';
    if (!isset($_SESSION["start"])) {
        echo ' value="START" id="start" name="start">';
        echo '<select class="scelta" name="scelta">';
        for ($x = 3; $x <= 10; $x++) {
            if (isset($_SESSION["username"])) {
                if ($x == $_SESSION["scelta"]) {
                    echo '<option value="' . $x . '" selected>' . $x . "</option>";
                } else {
                    echo '<option value="' . $x . '">' . $x . "</option>";
                }
            } else {
                echo '<option value="' . $x . '">' . $x . "</option>";
            }
        }
        echo "</select>";
        echo '<input value="';
        if (isset($_SESSION["username"])) {
            echo $_SESSION["username"];
        } else {
            echo "Anonimo";
        }
        echo '" class="nome" name="username" onclick="this.select()">';
    } else {
        echo ' value="RESET" id="reset" name="reset">';

    }

    echo "</form>";

    echo '<label class="theme-switch" for="checkbox">';
    echo '<input type="checkbox" id="checkbox" />';
    echo '<div class="slider round"></div>';
    echo '</label>';
    echo '</div>';
}

function mostraBottoni($quanti){

    echo '    <div class="button-container">';
    echo '        <form method="post" onsubmit="handleButtonPress(event)">';
    for ($i = 0; $i < $quanti; $i++) {
        $class = "";
        if ($i == 0) {
            $class .= "first ";
        }
        if ($i == $quanti - 1) {
            $class .= "last ";
        }
        if (
            isset($_SESSION["posizionati"][$i]) &&
            $_SESSION["posizionati"][$i] != null
        ) {
            echo '<div class="posizionato">' .
                $_SESSION["posizionati"][$i] .
                "</div>";
        } else {
            echo '<input type="submit" class="button ' .
                $class .
                '" name="button-' .
                $i .
                '" value="" data-audio="click.mp3">';
        }
    }

    echo "</form>";
    echo "</div>";
}

function eseguiTurnoDiGioco($numeroDiTurno,$quanti,$urlPagina,$db){
    foreach ($_POST as $key => $value) {
        if (strpos($key, "button-") === 0) {
            $posizione = substr($key, strlen("button-"));
            $numero = [$posizione => $numeroDiTurno];
            $posizionati = $_SESSION["posizionati"];
            if (controlla($posizionati, $numero, $quanti)) {
                $_SESSION["posizionati"][key($numero)] =
                    $numero[key($numero)];
                if (!in_array(null, $_SESSION["posizionati"])) {
                    $_SESSION["vittoria"] = true;
                }
                if (
                    isset($_SESSION["vittoria"]) &&
                    $_SESSION["vittoria"] == true
                ) {
                    $tempo_impiegato = round(
                        microtime(true) - $_SESSION["inizio"],
                        2
                    );
                    $isPersonalBest = $db->checkPersonalBest(
                        $quanti,
                        $_SESSION["username"],
                        $tempo_impiegato
                    );

                    if ($isPersonalBest) {
                        echo '<div class="vittoria">Complimenti, hai ottenuto un nuovo record personale!<br><a href="' .
                            $urlPagina .
                            '">Vai alla classifica</a></div>';
                    } else {
                        echo '<div class="vittoria">Hai vinto!<br>in ' .
                            formatSeconds($tempo_impiegato) .
                            ' secondi!<br><a href="' .
                            $urlPagina .
                            '">Guarda la classifica!</a></div>';
                    }
                    $db->saveTime(
                        $quanti,
                        $_SESSION["username"],
                        $tempo_impiegato
                    );
                    $username = $_SESSION["username"];
                    $scelta = $_SESSION["scelta"];
                    session_destroy();
                    session_start();
                    $_SESSION["username"] = $username;
                    $_SESSION["scelta"] = $scelta;

                    include("scripts.js");
                    echo "</body>";
                    echo "</html>";
                    die();
                }

                // Imposta la variabile $tempo_impiegato con il valore del tempo impiegato durante il gioco
                $tempo_impiegato = round(microtime(true) - $_SESSION["inizio"], 2);

                // Imposta il tempo di partenza nella sessionStorage
                echo '<script>';
                echo 'sessionStorage.setItem("startTime", "' . $tempo_impiegato . '");';
                echo '</script>';
                $_SESSION["fase"]++;
                header("location: $urlPagina");
                die();
            } else {
                $_SESSION["messaggio"] = "Errore!";
                header("location: $urlPagina");
                die();
            }
        }
    }
    echo '<div class="estratto">' . $numeroDiTurno . "</div>";
}

function mostraMessaggioFinale($numero_valido, $numeroDiTurno){
    if (!$numero_valido) {
        echo '<div class="errore">' .
            $numeroDiTurno .
            " non posizionabile.<br>";

        if (isset($_SESSION["posizionati"])) {
            $mostra = $_SESSION["posizionati"];
            sort($mostra);
            foreach ($mostra as $key => $value) {
                if ($value === null) {
                    echo " - ";
                } else {
                    echo $value . " ";
                }
            }
            echo "<br><br>";
        }
        echo "Numeri estratti:<br>";
        foreach ($_SESSION["estratti"] as $key => $value) {
            echo $value . " ";
        }
        echo "<br><br>";
        echo "La sequenza giusta era:<br>";
        sort($_SESSION["estratti"]);
        foreach ($_SESSION["estratti"] as $key => $value) {
            if ($numeroDiTurno == $value) {
                echo '<span style="color:white">' . $value . " </span>";
            } else {
                echo $value . " ";
            }
        }
        echo "</div>";
        include("scripts.js");
        echo "</body>";
        echo "</html>";
        die();
    }
}

function mostraClassifica($quanti, $db){

    echo '<form id="topTutto" method="post" action="' .
        $_SERVER["PHP_SELF"] .
        '">';
    echo '    <select class="selectClassifica" name="quanti" id="quanti" onchange="submitForm()">';
    for ($i = 3; $i <= 15; $i++) {
        echo '<option value="' . $i . '"';
        if ($i == $quanti) {
            echo "selected";
        }
        echo ">~ Top10 a " . $i . " numeri ~</option>";
    }
    echo "    </select>";
    echo '    <input type="hidden" name="rnd" value="' . rand() . '">';
    echo "    </form>";
    echo "</div>";
    if (isset($_POST["quanti"]) || isset($_SESSION["scelta"]) || true) {
        if (isset($_SESSION["scelta"])) {
            $quanti = $_SESSION["scelta"];
        }
        if (isset($_POST["quanti"])) {
            $quanti = $_POST["quanti"];
        }
        if (!isset($quanti)) {
            $quanti = 5;
        }
        $results = $db->getTop10($quanti);
        echo '<div class="top10">';
        echo '<table style="margin: 0 auto;">';
        $pos = 1;
        foreach ($results as $result) {
            echo '<tr><td style="width:40px;">';
            echo $pos .
                '</td><td style="width:40px">' .
                $result["numeri"] .
                "</td><td ";
            if ($pos == 1) {
                echo 'style="color:green; font-weight:bold"';
            }
            echo ">" .
                $result["username"] .
                '</td><td style="width:40px"></td><td>' .
                $result["tempo"] .
                '</td><td style="width:40px"></td><td>' .
                date("m/d/y H:i", strtotime($result["data"]));
            echo "</td></tr>";
            $pos++;
        }
        echo "</table></div>";
    }
}
?>