
app.controller('contaController', contaController);

function contaController($scope, $http) {
	    
	$scope.conta = {
 		con_codigo: 0,
 		con_descricao: '',
 		con_observacao: '',
 		con_tipomovimentacao: '',
 		con_movimentacao: '',
		con_banco: '',
 		con_agcontabancaria: '',
 		con_nrcontabancaria: '',
 		con_ativa: '',
 		usu_codigo: '',
 		usu_nome: '',
 		tco_codigo: '',
 		tco_descricao: ''
	}	
		
	$scope.contas = {};
	
	$scope.carregarContas = function(){		
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/contas")
			.success(function(retorno){
		       console.log(retorno);
		       $scope.contas = retorno;			     
			});
	};

	$scope.editarConta = function($conta){
 		$scope.conta.con_codigo           = $conta.con_codigo; 		 		
 		$scope.conta.con_descricao        = $conta.con_descricao;
 		$scope.conta.con_observacao       = $conta.con_observacao;
 		$scope.conta.con_tipomovimentacao = $conta.con_tipomovimentacao;
		$scope.conta.con_banco            = $conta.con_banco;
 		$scope.conta.con_agcontabancaria  = $conta.con_agcontabancaria;
 		$scope.conta.con_nrcontabancaria  = $conta.con_nrcontabancaria;
 		$scope.conta.con_ativa            = $conta.con_ativa;
 		$scope.conta.usu_codigo           = $conta.usu_codigo;
 		$scope.conta.usu_nome             = $conta.usu_nome;
 		$scope.conta.tco_codigo           = $conta.tco_codigo;
 		$scope.conta.tco_descricao        = $conta.tco_descricao;
 		
 		document.getElementById("nova-conta").style.display = 'block';
	};

	$scope.limparConta = function(){
 		$scope.conta.con_codigo           = 0;
 		$scope.conta.con_descricao        = '';
 		$scope.conta.con_observacao       = '';
 		$scope.conta.con_tipomovimentacao = '';
		$scope.conta.con_banco            = '';
 		$scope.conta.con_agcontabancaria  = '';
 		$scope.conta.con_nrcontabancaria  = '';
 		$scope.conta.con_ativa            = '';
 		$scope.conta.usu_codigo           = 0;
 		$scope.conta.usu_nome             = '';
 		$scope.conta.tco_codigo           = 0;
 		$scope.conta.tco_descricao        = '';
	};

	$scope.novaConta = function(){
		$scope.limparConta();
		document.getElementById("nova-conta").style.display = 'block';
	};

	$scope.salvarConta = function(){
		$http.post(getServerAddress()+"/WebFinanceApiRest/public/conta/salvar", $scope.conta)
		   	 .success(function(retorno){           
                if(retorno == 1){
                  $scope.limparConta(); 	 
                  $scope.carregarContas();
                  exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
                }
			  	else{			  	  	
			  	  exibirOcultarElementoMensagem('Erro ao salvar a conta!', true, true);
		        } 	
		    })
		    .error(function(erro){		    	
		    	exibirOcultarElementoMensagem('Erro ao salvar a conta!', true, true);
		    });
	};

	$scope.excluirConta = function($conta){
		$http
		 .post(getServerAddress()+"/WebFinanceApiRest/public/conta/excluir", $conta)
		 .success(function(retorno){           
            if(retorno == 1){
              $scope.limparConta(); 	 
              $scope.carregarContas();
              exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
            }
			else			  	  	
			  exibirOcultarElementoMensagem('Erro ao excluir conta!!', true, true);		    
		})
		.error(function(erro){		    	
		  exibirOcultarElementoMensagem('Erro ao excluir conta!', true, true);
		});
	};

    $scope.carregarContas();    
};
