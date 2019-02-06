<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/gruposFamiliares', function () {
    
    autentication();    
        
    $sql = 'select * from grupofamiliar order by gfa_codigo';        		
    $qry = $this->db->prepare($sql);  
    		
    $qry->execute();
	  $dados = $qry->fetchAll(PDO::FETCH_OBJ);
		
    echo json_encode($dados);
});

$app->post('/grupoFamiliar/salvar', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->gfa_codigo > 0) {

   		$sql = 'update grupofamiliar set gfa_descricao = :gfa_descricao
   		        where gfa_codigo = :gfa_codigo';		    

   		$qry = $this->db->prepare($sql);  		
    
      $qry->bindParam(':gfa_descricao', $dados->gfa_descricao, PDO::PARAM_STR);
      $qry->bindParam(':gfa_codigo', $dados->gfa_codigo, PDO::PARAM_STR);
     
    } else {
              
    	$sql = 'insert into grupofamiliar (gfa_codigo, gfa_descricao)
                values (coalesce((select max(gfa_codigo)+1 from grupofamiliar), 1), :gfa_descricao)';		    
        
      $qry = $this->db->prepare($sql);  		
      $qry->bindParam(':gfa_descricao', $dados->gfa_descricao, PDO::PARAM_STR);      
    }
    
    if ($qry->execute()){
        echo 1;
    } else echo 0;                          

});

$app->post('/grupoFamiliar/excluir', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->gfa_codigo > 0) {

      $sql = 'delete from grupofamiliar where gfa_codigo = :gfa_codigo';        

      $qry = $this->db->prepare($sql);      

      $qry->bindParam(':gfa_codigo', $dados->gfa_codigo, PDO::PARAM_STR);       

      if ($qry->execute()){
        echo 1;
      } else echo 0;                          
    } else echo 0;
});