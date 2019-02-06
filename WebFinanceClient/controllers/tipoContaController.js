
app.controller('tipoContaController', tipoContaController);

function tipoContaController($scope, $http) {
	    
	$scope.tipoconta = {
 		tco_codigo: 0,
 		tco_descricao  : ''
	}	
		
	$scope.tiposContas = {};
	
	$scope.carregarTiposContas = function(){
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/tiposContas")
			.success(function(retorno){
		       console.log(retorno);
		       $scope.tiposContas = retorno;			     
			});
	};

	$scope.editarTipoConta = function($tipo){
 		$scope.tipoconta.tco_codigo = $tipo.tco_codigo;
 		$scope.tipoconta.tco_descricao = $tipo.tco_descricao;
	};

	$scope.limparTipoConta = function(){
		$scope.tipoconta.tco_codigo = 0;
		$scope.tipoconta.tco_descricao = '';		
	};

	$scope.novoTipoConta = function(){
		$scope.limparTipoConta();
	};

	$scope.salvarTipoConta = function(){
		$http.post(getServerAddress()+"/WebFinanceApiRest/public/tipoConta/salvar", $scope.tipoconta)
		   	 .success(function(retorno){           
                if(retorno == 1){
                  $scope.limparTipoConta(); 	 
                  $scope.carregarTiposContas();
                  exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
                }
			  	else{			  	  	
			  	  exibirOcultarElementoMensagem('Erro ao salvar o tipo de conta!', true, true);
		        } 	
		    })
		    .error(function(erro){		    	
		    	exibirOcultarElementoMensagem('Erro ao salvar o tipo de conta!', true, true);
		    });
	};

	$scope.excluirTipoConta = function($grupo){
		$http
		 .post(getServerAddress()+"/WebFinanceApiRest/public/tipoConta/excluir", $grupo)
		 .success(function(retorno){           
            if(retorno == 1){
              $scope.limparTipoConta(); 	 
              $scope.carregarTiposContas();
              exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
            }
			else			  	  	
			  exibirOcultarElementoMensagem('Erro ao excluir tipo de conta!!', true, true);		    
		})
		.error(function(erro){		    	
		  exibirOcultarElementoMensagem('Erro ao excluir tipo de conta!', true, true);
		});
	};

    $scope.carregarTiposContas();    
};
