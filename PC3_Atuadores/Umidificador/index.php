<?php 
    class Umidificador {
        /**
         * Essa função simula o que acontece com o umidificador ao receber uma ordem.
         * No caso, ela apenas imprime "o umidifcador foi desligado", "o umidificador
         * foi ligado", apenas para simular uma situação real.
         */
        public static function post(){
            $value = $_POST['umidificador'];
            $mensagem = $value == "true" ? "ligado" : "desligado";
            error_log("O umidificador foi ".$mensagem);
        }
    }

    /*A informação abaixo é adicionada no header para, caso seja necessário testar as requisições no mesmo domínio do servidor,
    não vai dar um bloqueio no acesso. */
    header("Access-Control-Allow-Origin: *");
    
    //Se tiver algum POST sendo recebido, chama a função responsável para tratar esse post
    if (isset($_POST['umidificador'])){
        Umidificador::post();
    }

?>