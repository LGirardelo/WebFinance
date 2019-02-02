<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/usuarios', function () {
    
    autentication();    
        
    $sql = 'select * from usuarios';        		
    $qry = $this->db->prepare($sql);  
    		
    $qry->execute();
	$dados = $qry->fetchAll(PDO::FETCH_OBJ);
		
    echo json_encode($dados);
});