<?php 
    class ArCondicionado {

        public static function post(){
            $value = $_POST['ar_condicionado'];
            $mensagem = $value == "true" ? "ligado" : "desligado";
            error_log("O ar-condicionado foi ".$mensagem);
        }
    }

    header("Access-Control-Allow-Origin: *");
    if (isset($_POST['ar_condicionado'])){
        ArCondicionado::post();
    }

?>