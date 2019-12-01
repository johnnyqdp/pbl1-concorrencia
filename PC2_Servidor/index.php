<?php 
    class BROKER {

        /**
         * Ao receber uma requisição (linha 115), essa função vai checar qual foi o tipo de requisição para chamar as funções
         * necessárias para tratá-la.
         */
        public function callBroker ($requisicao) {
            if (isset($_GET['termometro'])){
            	error_log("Recebido um GET no topico: termometro");
                $this->get('termometro');
            }
            else if (isset($_GET['arcondicionado'])){
            	error_log("Recebido um GET no topico: ar_condicionado");
                $this->get('ar_condicionado');
            }
            else if (isset($_GET['termohigrometro'])){
            	error_log("Recebido um GET no topico: termohigrometro");
                $this->get('termohigrometro');
            }
            else if (isset($_GET['umidificador'])){
            	error_log("Recebido um GET no topico: umidificador");
                $this->get('umidificador');
            }

            else if (isset($_POST['ar_condicionado'])){
            	error_log("Recebido um POST de valor: ".$_POST['ar_condicionado']." no topico: ar_condicionado");
                $this->registrarNoTopico('ar_condicionado', $_POST['ar_condicionado']);
            }
            else if (isset($_POST['termometro'])) {
            	error_log("Recebido um POST de valor: ".$_POST['termometro']." no topico: termometro");
                $this->registrarNoTopico('termometro', $_POST['termometro']);
            }
            else if (isset($_POST['umidificador'])) {
            	error_log("Recebido um POST de valor: ".$_POST['umidificador']." no topico: umidificador");
                $this->registrarNoTopico('umidificador', $_POST['umidificador']);
            }
            else if (isset($_POST['termohigrometro'])) {
            	error_log("Recebido um POST de valor: ".$_POST['termohigrometro']." no topico: termohigrometro");
                $this->registrarNoTopico('termohigrometro', $_POST['termohigrometro']);
            }
            else{
                $this->retornarMensagemErro();
            }
        }

        /**
         * Recebe o nome do topico e retorna o valor que está armazenado atualmente neste tópico, dentro de topicos.json
         */
        private function get (string $nomeTopico) {
            $json = file_get_contents('topicos.json');
            $jsonObjeto = json_decode($json);
            echo json_encode(
                array(
                    $nomeTopico => $jsonObjeto->$nomeTopico
                )
            );            
        }

        /** 
         * Recebe o nome do tópico e o valor a ser registrado nele, registra esse valor no tópico e, no final, chama uma função para
         * enviar essa informação registrada para todos os ips que estão inscritos nesse topico (inscritos.json)
         */
        private function registrarNoTopico (string $nomeTopico, $valor) {
            try{
                $json = file_get_contents('topicos.json');
                $jsonObjeto = json_decode($json, true);
                $jsonObjeto[$nomeTopico] = $valor;
                file_put_contents('topicos.json',
                    json_encode($jsonObjeto)
                );

                $this->enviarAosInscritos($nomeTopico, $valor);
            }
            catch(Exception $e){
                $this->retornarMensagemErro();
            }
        }

        /**
         * Recebe o nome de um tópico e envia para todos os inscritos deste tópico, o novo valor que foi atribuido a ele.
         */
        private function enviarAosInscritos(string $nomeTopico, $valor){
            $inscritos = array();
            $json = file_get_contents('inscritos.json');
            $jsonObjeto = json_decode($json, true);
            if (!empty($jsonObjeto[$nomeTopico])){
                $inscritosDoTopico = $jsonObjeto[$nomeTopico];

                foreach ($inscritosDoTopico as $i){
                    $curl = curl_init($i['ip']);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);

                    curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                        $nomeTopico => $valor,
                    ));

                    $resultado = curl_exec($curl);
                    curl_close($curl);
                }
            }
            echo json_encode(
                array(
                    'success' => true
                )
            );
        }
        
        /**
         * Essa função retorna um json informando que houve um problema ao completar a requisicao
         */
        private function retornarMensagemErro () {
            echo (json_encode(
                array(
                    'status'=>'falha na requisicao'
                )
            ));
        }
    }

    /*A informação abaixo é adicionada no header para, caso seja necessário testar as requisições no mesmo domínio do servidor,
    não vai dar um bloqueio no acesso. */
    header("Access-Control-Allow-Origin: *");

    //Se tiver alguma requisição sendo recebida, chama o Broker passando essa requisição a ele.
    if (isset($_REQUEST)){
        $broker = new BROKER();
        $broker->callBroker($_REQUEST);
    }

?>