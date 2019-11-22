<?php 
    class ArCondicionado {

        public static function post(){
            $value = $_POST['ar_condicionado'];
            error_log("Ar-condicionado esta em: ".$value);
        }
    }

    header("Access-Control-Allow-Origin: *");
    if (isset($_POST['ar_condicionado'])){
        ArCondicionado::post();
    }

?>