<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/usuarios', function () {
    
    autentication();    
        
    $sql = 'select usuarios.*, grupofamiliar.gfa_descricao from usuarios left join grupofamiliar on grupofamiliar.gfa_codigo = usuarios.gfa_codigo';        		
    
    $qry = $this->db->prepare($sql);  
    		
    $qry->execute();
	  $dados = $qry->fetchAll(PDO::FETCH_OBJ);
		
    echo json_encode($dados);
});

$app->post('/usuario/salvar', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    $passwdEncript = hash('sha256', $dados->usu_senha);
    
    if ($dados->usu_codigo > 0) {

   		$sql = 'update usuarios set
   		        	usu_login  = :usu_login, 
   		        	usu_nome   = :usu_nome, 
   		        	usu_email  = :usu_email, 
   		        	usu_ativo  = :usu_ativo, 
   		        	usu_senha  = :usu_senha,
                gfa_codigo = :gfa_codigo
   		        where usu_codigo = :usu_codigo';		    

   		$qry = $this->db->prepare($sql);  		
    
      $qry->bindParam(':usu_login', $dados->usu_login, PDO::PARAM_STR);
      $qry->bindParam(':usu_nome', $dados->usu_nome, PDO::PARAM_STR);
      $qry->bindParam(':usu_email', $dados->usu_email, PDO::PARAM_STR);
      $qry->bindParam(':usu_ativo', $dados->usu_ativo, PDO::PARAM_BOOL);       
      $qry->bindParam(':usu_senha', $passwdEncript, PDO::PARAM_STR);       
      $qry->bindParam(':usu_codigo', $dados->usu_codigo, PDO::PARAM_STR);       
      $qry->bindParam(':gfa_codigo', $dados->gfa_codigo, PDO::PARAM_STR);       

    } else {
              
    	$sql = 'insert into usuarios (usu_codigo, usu_login, usu_senha, usu_nome, usu_email, usu_ativo, gfa_codigo)
                values (coalesce((select max(usu_codigo)+1 from usuarios), 1), :usu_login, :usu_senha, :usu_nome, :usu_email, :usu_ativo, :gfa_codigo)';		    
        
      $qry = $this->db->prepare($sql);  		
    
      $qry->bindParam(':usu_login', $dados->usu_login, PDO::PARAM_STR);
      $qry->bindParam(':usu_nome', $dados->usu_nome, PDO::PARAM_STR);
      $qry->bindParam(':usu_email', $dados->usu_email, PDO::PARAM_STR);
      $qry->bindParam(':usu_ativo', $dados->usu_ativo, PDO::PARAM_BOOL);       
      $qry->bindParam(':usu_senha', $passwdEncript, PDO::PARAM_STR);               
      $qry->bindParam(':gfa_codigo', $dados->gfa_codigo, PDO::PARAM_STR);               
    }
    
    if ($qry->execute()){
        echo 1;
    } else echo 0;                          

});

$app->post('/usuario/excluir', function (Request $request, Response $response, array $args) {
    
    autentication(); 

    $dados = $request->getBody();
    $dados = json_decode($dados);

    if ($dados->usu_codigo > 0) {

      $sql = 'delete from usuarios where usu_codigo = :usu_codigo';        

      $qry = $this->db->prepare($sql);      

      $qry->bindParam(':usu_codigo', $dados->usu_codigo, PDO::PARAM_STR);       

      if ($qry->execute()){
        echo 1;
      } else echo 0;                          
    } else echo 0;
});