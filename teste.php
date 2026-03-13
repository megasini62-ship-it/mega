<?php 

$url = 'http://134.65.231.151:5000/gerarpagamento';
    $access_token = 'APP_USR-2277378811674749-030400-e88853b4bd867b711bf60f6a17eb8b4a-423185893';  
    $data = array(
        'valor' => $valor,
        'email' => 'igorasdasd@gmail.com'
    );
    
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data)
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        return false;
    }
    
    $data = json_decode($result, true);