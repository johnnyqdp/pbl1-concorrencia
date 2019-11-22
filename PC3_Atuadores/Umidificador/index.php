<?php 
    class Umidificador {

        public static function post(){
            $value = $_POST['umidificador'];
            $mensagem = $value == "true" ? "ligado" : "desligado";
            error_log("O umidificador foi ".$mensagem);
        }
    }

    header("Access-Control-Allow-Origin: *");
    if (isset($_POST['umidificador'])){
        Umidificador::post();
    }

?>