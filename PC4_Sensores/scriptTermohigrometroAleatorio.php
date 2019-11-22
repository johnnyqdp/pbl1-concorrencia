<?php 
    while(true){
        $valorUmidadeRegistrada = rand(0,100);
        $curl = curl_init('http://172.16.103.6:2000');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'termohigrometro' => $valorUmidadeRegistrada,
        ));
	    error_log("\n\nUmidade enviada ao broker: ".$valorUmidadeRegistrada."\n resposta:");
        curl_exec($curl);
        sleep(5);
    }
?>