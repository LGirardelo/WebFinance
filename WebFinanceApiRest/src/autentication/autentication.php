<?php
use Slim\Http\Request;
use Slim\Http\Response;

function autentication() {
   
    session_start();
   
    if (!(isset($_SESSION['user'])) or !(isset($_SESSION['password']))) {
   
        header('Location: /WebFinanceApiRest/public/naoautenticado');   
        exit;

    } else {
        return true;
    }
};

$app->get('/naoautenticado', function () {
    echo "Usuário não autenticado!";
});

$app->post('/auth', function (Request $request, Response $response, array $args) {
        
    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->user && $dados->password){ 

        $passCript = hash('sha256', $dados->password);
        
        $sql = 'select * from usuarios where usu_login = :user and usu_senha = :password';        
		
        $qry = $this->db->prepare($sql);  
		
        $qry->bindParam(':user', $dados->user, PDO::PARAM_STR);
        $qry->bindParam(':password', $passCript, PDO::PARAM_STR);                

        if ($qry->execute()){

            if($qry->rowCount() > 0){                
                   
                session_start();

                $result = $qry->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $row => $user) {
                  $_SESSION['name'] = $user['usu_nome'];
                }

                $_SESSION['user'] = $dados->user;
                $_SESSION['password'] = $passCript;                
        
                echo 2;                    
        
            } else{                    
                echo 1;
            }           
        } else
            echo 'Erro';       
    } else{
        echo 0;       
    }            
});