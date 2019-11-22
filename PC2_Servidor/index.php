<?php 
    class BROKER {

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

        private function get (string $nomeTopico) {
            $json = file_get_contents('topicos.json');
            $jsonObjeto = json_decode($json);
            echo json_encode(
                array(
                    $nomeTopico => $jsonObjeto->$nomeTopico
                )
            );            
        }

        private function registrarNoTopico (string $nomeTopico, $valor) {
            try{
                $json = file_get_contents('topicos.json');
                $jsonObjeto = json_decode($json);
                $jsonObjeto->$nomeTopico = $valor;
                file_put_contents('topicos.json',
                    json_encode($jsonObjeto)
                );

                $this->enviarAosInscritos($nomeTopico, $valor);
            }
            catch(Exception $e){
                $this->retornarMensagemErro();
            }
        }

        private function enviarAosInscritos(string $nomeTopico, $valor){
            $inscritos = array();
            $json = file_get_contents('inscritos.json');
            $jsonObjeto = json_decode($json);
            $inscritosDoTopico = $jsonObjeto->$nomeTopico;

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

            echo json_encode(
                array(
                    'success' => true
                )
            );
        }
        
        private function retornarMensagemErro () {
            echo (json_encode(
                array(
                    'status'=>'falha na requisicao'
                )
            ));
        }
    }

    header("Access-Control-Allow-Origin: *");
    if (isset($_REQUEST)){
        $broker = new BROKER();
        $broker->callBroker($_REQUEST);
    }

?>