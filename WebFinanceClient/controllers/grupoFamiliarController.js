
app.controller('grupoFamiliarController', grupoFamiliarController);

function grupoFamiliarController($scope, $http) {
	    
	$scope.grupofamiliar = {
 		gfa_codigo: 0,
 		gfa_descricao  : ''
	}	
		
	$scope.gruposFamiliares = {};
	
	$scope.carregarGruposFamiliares = function(){
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/gruposFamiliares")
			.success(function(retorno){
		       console.log(retorno);
		       $scope.gruposFamiliares = retorno;			     
			});
	};

	$scope.editarGrupoFamiliar = function($grupo){
 		$scope.grupofamiliar.gfa_codigo = $grupo.gfa_codigo;
 		$scope.grupofamiliar.gfa_descricao = $grupo.gfa_descricao;
	};

	$scope.limparGrupoFamiliar = function(){
		$scope.grupofamiliar.gfa_codigo = 0;
		$scope.grupofamiliar.gfa_descricao = '';		
	};

	$scope.novoGrupoFamiliar = function(){
		$scope.limparGrupoFamiliar();
	};

	$scope.salvarGrupoFamiliar = function(){
		$http.post(getServerAddress()+"/WebFinanceApiRest/public/grupoFamiliar/salvar", $scope.grupofamiliar)
		   	 .success(function(retorno){           
                if(retorno == 1){
                  $scope.limparGrupoFamiliar(); 	 
                  $scope.carregarGruposFamiliares();
                  exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
                }
			  	else{			  	  	
			  	  exibirOcultarElementoMensagem('Erro ao salvar o grupo familiar!', true, true);
		        } 	
		    })
		    .error(function(erro){		    	
		    	exibirOcultarElementoMensagem('Erro ao salvar o grupo familiar!', true, true);
		    });
	};

	$scope.excluirGrupoFamiliar = function($grupo){
		$http
		 .post(getServerAddress()+"/WebFinanceApiRest/public/grupoFamiliar/excluir", $grupo)
		 .success(function(retorno){           
            if(retorno == 1){
              $scope.limparGrupoFamiliar(); 	 
              $scope.carregarGruposFamiliares();
              exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
            }
			else			  	  	
			  exibirOcultarElementoMensagem('Erro ao excluir grupo familiar!', true, true);		    
		})
		.error(function(erro){		    	
		  exibirOcultarElementoMensagem('Erro ao excluir grupo familiar!', true, true);
		});
	};

    $scope.carregarGruposFamiliares();    
};
