<?php

	//debug
	if (isset($_SESSION["fase"])) {
        echo '<div class="fase">fase: ' . $_SESSION["fase"] . '</div>';
    } else {
        echo '<div class="fase"></div>';
    }
    if(isset($_SESSION["estratti"])){
        $mostra = $_SESSION["estratti"];
        echo '<div style="padding:10px; width:400px; margin:0 auto; text-align:center; position:relative;top:100px">';
        sort($mostra);
        foreach ($mostra as $key => $value) {
            echo $value . " ";
        }
        echo '</div>';
    }
    foreach ($_SESSION as $name => $value) {
        echo $name . " -> ";
        if (is_array($value)) {
            foreach ($value as $subvalue) {
                if($subvalue === null){
                    echo "nn, ";
                }else{
                        echo $subvalue . ", ";
                }
            }
        } else {
            echo $value;
        }
        echo "<br>";
    }
?>