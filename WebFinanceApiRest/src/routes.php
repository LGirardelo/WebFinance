<?php
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) {        
    return $this->renderer->render($response, 'index.phtml', $args);
});

/*$app->get('/hello', function (Request $request, Response $response, array $args) {    

    autentication();

    $sql = 'select * from usuarios';
    $qry = $this->db->prepare($sql);  
   
    if ($qry->execute()){
        if($qry->rowCount() > 0){ //Valida se retornou 
           $dados = $qry->fetch(PDO::FETCH_OBJ);		          
        }
    }    
    
    return json_encode($dados->usu_nome);    
});

$app->post('/freight_calculate', 'autentication', function() use ($banco, $app){
        
        $dados = $app->request()->getBody();
        $dados = json_decode($dados);

        $sql = 'select *, (valor_por_kilo * :produtopeso) as valor'.
               ' from price_freight where uf_origem = :uforigem and uf_destino = :ufdestino;';

        $qry = $banco->conexao()->prepare($sql);
        $qry->bindParam(':uforigem', $dados->uforigem, PDO::PARAM_STR);
        $qry->bindParam(':ufdestino', $dados->ufdestino, PDO::PARAM_STR);
        $qry->bindParam(':produtopeso', $dados->produtopeso, PDO::PARAM_STR);
        
        $qry->execute();

        $dados = $qry->fetch(PDO::FETCH_OBJ);
        

        echo json_encode($dados);

});

*/