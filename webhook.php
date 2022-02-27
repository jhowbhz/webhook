<?php

    // vamos fazer os dois endpoints, / e /webhook
    $request = $_SERVER['REQUEST_URI'];
    $request = explode("/", $request);
    $metodo = $_SERVER['REQUEST_METHOD'];

    $data = file_get_contents("php://input");
    $log = fopen('logs/bateu-'.date('dm-his')."-".$metodo."-log.txt", "w") or die("Unable to open file!");
    fwrite($log, $data);
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