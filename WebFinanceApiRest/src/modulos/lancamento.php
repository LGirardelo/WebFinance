<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->post('/lancamentos', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    $sql = "    select l.lan_codigo,
                       c.con_codigo,
                       case 
                        when c.con_tipomovimentacao = 1 then 
                          'C'
                        when c.con_tipomovimentacao = 2 then    
                          'D'
                        when c.con_tipomovimentacao = 3 then   
                          'D'
                       end movimento,
                       l.lan_valor,
                       l.lan_datavencimento,
                       l.lan_dataquitacao,
                       l.lan_datalancamento,
                       l.lan_observacao,
                       l.usu_codigo,
                       u.usu_nome,
                       c.con_codigo || ' - ' || c.con_descricao as con_descricao,
                       case 
                        when c.con_tipomovimentacao = 1 then 
                          'Entrada'
                        when c.con_tipomovimentacao = 2 then    
                          'Saída'
                        when c.con_tipomovimentacao = 3 then   
                          'Entrada/Saída'
                       end con_movimentacao, 
                       (select sum(l.lan_valor)
                          from lancamento l
                          join conta c on c.con_codigo = l.con_codigodebito 
                          where l.con_codigodebito = :con_codigo 
                            and l.lan_dataquitacao = current_date 
                       ) as total    
                  from lancamento l 
                  join conta c 
                    on c.con_codigo = l.con_codigodebito 
                  left join usuarios u on l.usu_codigo = u.usu_codigo
                 where l.con_codigodebito = :con_codigo 
                   and l.lan_dataquitacao = current_date  

                union 

                select l.lan_codigo,
                       c.con_codigo,
                       case 
                        when c.con_tipomovimentacao = 1 then 
                          'C'
                        when c.con_tipomovimentacao = 2 then    
                          'D'
                        when c.con_tipomovimentacao = 3 then   
                          'C'
                       end movimento,
                       l.lan_valor,
                       l.lan_datavencimento,
                       l.lan_dataquitacao,
                       l.lan_datalancamento,
                       l.lan_observacao,
                       l.usu_codigo,
                       u.usu_nome,
                       c.con_codigo || ' - ' || c.con_descricao as con_descricao,
                       case 
                        when c.con_tipomovimentacao = 1 then 
                          'Entrada'
                        when c.con_tipomovimentacao = 2 then    
                          'Saída'
                        when c.con_tipomovimentacao = 3 then   
                          'Entrada/Saída'
                       end con_movimentacao,
                       (select sum(l.lan_valor)
                          from lancamento l
                          join conta c on c.con_codigo = l.con_codigocredito
                          where l.con_codigocredito = :con_codigo 
                            and l.lan_dataquitacao = current_date 
                       ) as total          
                  from lancamento l 
                  join conta c 
                    on c.con_codigo = l.con_codigocredito
                  left join usuarios u on l.usu_codigo = u.usu_codigo
                 where l.con_codigocredito = :con_codigo 
                   and l.lan_dataquitacao = current_date ";        
    
    $qry = $this->db->prepare($sql);   
    
    $qry->bindParam(':con_codigo', $dados->con_codigo, PDO::PARAM_STR);    
    
    $qry->execute();
    
    $dados = $qry->fetchAll(PDO::FETCH_OBJ);    
    
    echo json_encode($dados);
});



/*

$app->get('/lancamentos', function () {
    
    autentication();    
    
    $sql = "select c.con_codigo,
                   c.con_descricao,        
                   c.con_observacao,       
                   c.con_tipomovimentacao,
                   case 
                    when c.con_tipomovimentacao = 1 then 
                      'Entrada'
                    when c.con_tipomovimentacao = 2 then    
                      'Saída'
                    when c.con_tipomovimentacao = 3 then   
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

      $qry->bindValue(':con_descricao',        $dados->con_descricao       , PDO::PARAM_STR);
      $qry->bindValue(':con_observacao',       $dados->con_observacao      , PDO::PARAM_STR);
      $qry->bindValue(':con_tipomovimentacao', $dados->con_tipomovimentacao, PDO::PARAM_INT);
      $qry->bindValue(':con_banco',            $dados->con_banco           , PDO::PARAM_STR);
      $qry->bindValue(':con_agcontabancaria',  $dados->con_agcontabancaria , PDO::PARAM_STR);
      $qry->bindValue(':con_nrcontabancaria',  $dados->con_nrcontabancaria , PDO::PARAM_STR);
      $qry->bindValue(':con_ativa',            $dados->con_ativa           , PDO::PARAM_BOOL);
      if ($dados->usu_codigo == '')
        $qry->bindValue(':usu_codigo',           $dados->usu_codigo          , PDO::PARAM_NULL);
      else
        $qry->bindValue(':usu_codigo',           $dados->usu_codigo          , PDO::PARAM_INT);
      $qry->bindValue(':tco_codigo',           $dados->tco_codigo          , PDO::PARAM_INT);
      $qry->bindParam(':con_codigo',           $dados->con_codigo          , PDO::PARAM_INT);
     
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

      $qry->bindValue(':con_descricao',        $dados->con_descricao       , PDO::PARAM_STR);
      $qry->bindValue(':con_observacao',       $dados->con_observacao      , PDO::PARAM_STR);
      $qry->bindValue(':con_tipomovimentacao', $dados->con_tipomovimentacao, PDO::PARAM_INT);
      $qry->bindValue(':con_banco',            $dados->con_banco           , PDO::PARAM_STR);
      $qry->bindValue(':con_agcontabancaria',  $dados->con_agcontabancaria , PDO::PARAM_STR);
      $qry->bindValue(':con_nrcontabancaria',  $dados->con_nrcontabancaria , PDO::PARAM_STR);
      $qry->bindValue(':con_ativa',            $dados->con_ativa           , PDO::PARAM_BOOL);
      if ($dados->usu_codigo == '')
        $qry->bindValue(':usu_codigo',           $dados->usu_codigo          , PDO::PARAM_NULL);
      else
        $qry->bindValue(':usu_codigo',           $dados->usu_codigo          , PDO::PARAM_INT);
      $qry->bindValue(':tco_codigo',           $dados->tco_codigo          , PDO::PARAM_INT);      
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
});*/