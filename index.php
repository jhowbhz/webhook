<?php

    // vamos fazer os dois endpoints, / e /webhook
    $request = $_SERVER['REQUEST_URI'];
    $request = explode("/", $request);
    $metodo = $_SERVER['REQUEST_METHOD'];

    $data = file_get_contents("php://input");
    $log = fopen('logs/bateu-'.date('dm-his')."-".$metodo."-log.txt", "w") or die("Unable to open file!");
    fwrite($log, $data);
    fclose($log);

    fwrite($log, json_encode($headers));
    fclose($log);

    $posts = $_POST;
    $log = fopen('logs/post-'.date('dm-his')."-".$metodo."-log.txt", "w") or die("Unable to open file!");

    fwrite($log, json_encode($posts));
    fclose($log);

    // se existir o indice [1] a gente faz um switch para varias situaçoes
    if( isset($request[1]) ) {

        switch ( $request[1] ) {

            //aqui caimos na situacao da nossa api /webhook
            case 'webhook':
     
                // vamos pegar o conteudo do POST
                $data = file_get_contents("php://input");

                // vamos gravar o que esta dentro de $data
                // vamos fazer o nome ficar dinamico
                $log = fopen('logs/webhook-'.date('dm-his')."-".$metodo."-log.txt", "w") or die("Unable to open file!");
                fwrite($log, $data);
                fclose($log);

                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => '%7B%7BSERVIDOR%7D%7D/sendText',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "session": "{{session}}",
                    "number" : "553195360492",
                    "text" : "texto do caption para arquivo"
                }',
                CURLOPT_HTTPHEADER => array(
                    'sessionkey: {{sessionkey}}',
                    'Content-Type: application/json'
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                echo $response;

                break;
            
            // por padrao retornamos error;
            default:
                header('Content-Type: application/json');
                echo json_encode(['error' => true, 'message' => 'route not defined']);
                break;
        }

    }else{
        $log = fopen("logs/error.txt", "w") or die("Unable to open file!");

        header('Content-Type: application/json');
        echo json_encode(['error' => true, 'message' => 'route not defined']);

    }

?>