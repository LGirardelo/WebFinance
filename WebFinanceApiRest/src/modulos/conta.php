<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/contas', function () {
    
    autentication();    
    
    $sql = "select c.con_codigo,
                   c.con_descricao,        
                   c.con_observacao,       
                   c.con_tipomovimentacao,
                   case 
                    when c.con_tipomovimentacao = 0 then 
                      'Entrada'
                    when c.con_tipomovimentacao = 1 then    
                      'Saída'
                    when c.con_tipomovimentacao = 2 then   
                      'Entrada/Saída'
                   end con_movimentacao,    
                   c.con_banco,            
                   c.con_agcontabancaria,
                   c.con_nrcontabancaria, 
                   c.con_ativa,            
                   c.usu_codigo,
                   u.usu_nome,           
                   c.tco_codigo,
                   t.tco_descricao                    
             from conta c
             left join usuarios u on c.usu_codigo = u.usu_codigo
             left join tipoconta t on c.tco_codigo = t.tco_codigo 
             order by c.con_codigo";
           
    $qry = $this->db->prepare($sql);  
    		
    $qry->execute();

	  $dados = $qry->fetchAll(PDO::FETCH_OBJ);
		
    echo json_encode($dados);
});

$app->post('/conta/salvar', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->con_codigo > 0) {

   		$sql = 'update conta set                   
                  con_descricao        = :con_descricao        ,
                  con_observacao       = :con_observacao       ,
                  con_tipomovimentacao = :con_tipomovimentacao ,
                  con_banco            = :con_banco            ,
                  con_agcontabancaria  = :con_agcontabancaria  ,
                  con_nrcontabancaria  = :con_nrcontabancaria  ,
                  con_ativa            = :con_ativa            ,
                  usu_codigo           = :usu_codigo           ,
                  tco_codigo           = :tco_codigo           
   		        where con_codigo = :con_codigo';		    

   		$qry = $this->db->prepare($sql);  		

      $qry->bindParam(':con_descricao',        $dados->con_descricao       , PDO::PARAM_STR);
      $qry->bindParam(':con_observacao',       $dados->con_observacao      , PDO::PARAM_STR);
      $qry->bindParam(':con_tipomovimentacao', $dados->con_tipomovimentacao, PDO::PARAM_STR);
      $qry->bindParam(':con_banco',            $dados->con_banco           , PDO::PARAM_STR);
      $qry->bindParam(':con_agcontabancaria',  $dados->con_agcontabancaria , PDO::PARAM_STR);
      $qry->bindParam(':con_nrcontabancaria',  $dados->con_nrcontabancaria , PDO::PARAM_STR);
      $qry->bindParam(':con_ativa',            $dados->con_ativa           , PDO::PARAM_STR);
      $qry->bindParam(':usu_codigo',           $dados->usu_codigo          , PDO::PARAM_STR);
      $qry->bindParam(':tco_codigo',           $dados->tco_codigo          , PDO::PARAM_STR);
      $qry->bindParam(':con_codigo',           $dados->con_codigo          , PDO::PARAM_STR);
     
    } else {
              
    	$sql = 'insert into conta ( con_codigo          ,
                                  con_descricao       ,
                                  con_observacao      ,
                                  con_tipomovimentacao,
                                  con_banco           ,
                                  con_agcontabancaria ,
                                  con_nrcontabancaria ,
                                  con_ativa           ,
                                  usu_codigo          ,
                                  tco_codigo          )      
                values ( coalesce((select max(con_codigo)+1 from conta), 1) , 
                                              :con_descricao       ,
                                              :con_observacao      ,
                                              :con_tipomovimentacao,
                                              :con_banco           ,
                                              :con_agcontabancaria ,
                                              :con_nrcontabancaria ,
                                              :con_ativa           ,
                                              :usu_codigo          ,
                                              :tco_codigo          )';		    
        
      $qry = $this->db->prepare($sql);  

      $qry->bindParam(':con_descricao',        $dados->con_descricao       , PDO::PARAM_STR);
      $qry->bindParam(':con_observacao',       $dados->con_observacao      , PDO::PARAM_STR);
      $qry->bindParam(':con_tipomovimentacao', $dados->con_tipomovimentacao, PDO::PARAM_STR);
      $qry->bindParam(':con_banco',            $dados->con_banco           , PDO::PARAM_STR);
      $qry->bindParam(':con_agcontabancaria',  $dados->con_agcontabancaria , PDO::PARAM_STR);
      $qry->bindParam(':con_nrcontabancaria',  $dados->con_nrcontabancaria , PDO::PARAM_STR);
      $qry->bindParam(':con_ativa',            $dados->con_ativa           , PDO::PARAM_STR);
      $qry->bindParam(':usu_codigo',           $dados->usu_codigo          , PDO::PARAM_STR);
      $qry->bindParam(':tco_codigo',           $dados->tco_codigo          , PDO::PARAM_STR);
      $qry->bindParam(':con_codigo',           $dados->con_codigo          , PDO::PARAM_STR);
    }
    
    if ($qry->execute()){
        echo 1;
    } else echo 0;                          

});

$app->post('/conta/excluir', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->con_codigo > 0) {

      $sql = 'delete from conta where con_codigo = :con_codigo';        

      $qry = $this->db->prepare($sql);      

      $qry->bindParam(':con_codigo', $dados->con_codigo, PDO::PARAM_STR);       

      if ($qry->execute()){
        echo 1;
      } else echo 0;                          
    } else echo 0;
});