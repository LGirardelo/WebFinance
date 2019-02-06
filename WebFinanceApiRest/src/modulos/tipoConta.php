<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/tiposContas', function () {
    
    autentication();    
        
    $sql = 'select * from tipoconta order by tco_codigo';        		
    $qry = $this->db->prepare($sql);  
    		
    $qry->execute();
	  $dados = $qry->fetchAll(PDO::FETCH_OBJ);
		
    echo json_encode($dados);
});

$app->post('/tipoConta/salvar', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->tco_codigo > 0) {

   		$sql = 'update tipoconta set tco_descricao = :tco_descricao
   		        where tco_codigo = :tco_codigo';		    

   		$qry = $this->db->prepare($sql);  		
    
      $qry->bindParam(':tco_descricao', $dados->tco_descricao, PDO::PARAM_STR);
      $qry->bindParam(':tco_codigo', $dados->tco_codigo, PDO::PARAM_STR);
     
    } else {
              
    	$sql = 'insert into tipoconta (tco_codigo, tco_descricao)
                values (coalesce((select max(tco_codigo)+1 from tipoconta), 1), :tco_descricao)';		    
        
      $qry = $this->db->prepare($sql);  		
      $qry->bindParam(':tco_descricao', $dados->tco_descricao, PDO::PARAM_STR);      
    }
    
    if ($qry->execute()){
        echo 1;
    } else echo 0;                          

});

$app->post('/tipoConta/excluir', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->tco_codigo > 0) {

      $sql = 'delete from tipoconta where tco_codigo = :tco_codigo';        

      $qry = $this->db->prepare($sql);      

      $qry->bindParam(':tco_codigo', $dados->tco_codigo, PDO::PARAM_STR);       

      if ($qry->execute()){
        echo 1;
      } else echo 0;                          
    } else echo 0;
});