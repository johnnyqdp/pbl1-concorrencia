<?php 
    while(true){
        $valorTemperaturaRegistrada = rand(20,40);
        $curl = curl_init('http://172.16.103.6:2000');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'termometro' => $valorTemperaturaRegistrada,
        ));
	error_log("\n\nTemperatura enviada ao broker: ".$valorTemperaturaRegistrada."\n resposta:");
        curl_exec($curl);
        sleep(5);
    }
?>