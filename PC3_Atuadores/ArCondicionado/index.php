<?php 
    class ArCondicionado {

        /**
         * Essa função simula o que acontece com o ar-condicionado ao receber uma ordem.
         * No caso, ela apenas imprime "o ar condicionado foi desligado", "o ar condicionado
         * foi ligado", apenas para simular uma situação real.
         */
        public static function post(){
            $value = $_POST['ar_condicionado'];
            $mensagem = $value == "true" ? "ligado" : "desligado";
            error_log("O ar-condicionado foi ".$mensagem);
        }
    }

    /*A informação abaixo é adicionada no header para, caso seja necessário testar as requisições no mesmo domínio do servidor,
    não vai dar um bloqueio no acesso. */
    header("Access-Control-Allow-Origin: *");

    //Se tiver algum POST sendo recebido, chama a função responsável para tratar esse post
    if (isset($_POST['ar_condicionado'])){
        ArCondicionado::post();
    }

?>