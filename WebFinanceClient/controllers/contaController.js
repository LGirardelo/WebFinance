
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

	$scope.tiposMovimentacoes = [
 		{
 			"codigo": 1,
 			"descricao": "Entrada"
 		},
 		{
			"codigo": 2,
 			"descricao": "Saída"
 		},
 		{
 			"codigo": 3,
 			"descricao": "Entrada/Saída"
 		}
	];	
		
	$scope.contas = {};

	$scope.tiposContas = {};

	$scope.usuarios = {};

	$scope.lancamentos = [
  		totaldebito = 0,
  		totalcredito = 0,
  		dados = {}
	];
	
	$scope.carregarUsuarios = function() {
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/usuarios")
			.success(function(retorno) {
		       console.log(retorno);
		       $scope.usuarios = retorno;			     
			});
	};

	$scope.carregarTiposContas = function() {
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/tiposContas")
			.success(function(retorno) {
		       console.log(retorno);
		       $scope.tiposContas = retorno;			     
			});
	};
	
	$scope.carregarContas = function() {		
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/contas")
			.success(function(retorno) {
		       console.log(retorno);
		       $scope.contas = retorno;			     
			});
	};

	$scope.carregarLancamentos = function($conta){		
		$scope.limparLancamentos();	
		$http
			.post(getServerAddress()+"/WebFinanceApiRest/public/lancamentos", $conta)
			.success(function(retorno){
		       
		       console.log(retorno);
		       
		       $scope.lancamentos.dados = retorno;

		       let lstDebitos = $scope.lancamentos.dados.filter(function(item){ 
                                    return (item.movimento == 'D')
                                });

		       let lstCreditos = $scope.lancamentos.dados.filter(function(item){ 
                                    return (item.movimento == 'C')
                                });
		       if (!isEmpty(lstDebitos)) {
		         $scope.lancamentos.totaldebito = lstDebitos[0].total;			     
		       }

		       if (!isEmpty(lstCreditos)) {		       
		         $scope.lancamentos.totalcredito = lstCreditos[0].total;
		       }
			});
	};

	$scope.editarConta = function($conta) {
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

	$scope.limparConta = function() {
 		$scope.conta.con_codigo           = 0;
 		$scope.conta.con_descricao        = '';
 		$scope.conta.con_observacao       = '';
 		$scope.conta.con_tipomovimentacao = '';
		$scope.conta.con_banco            = '';
 		$scope.conta.con_agcontabancaria  = '';
 		$scope.conta.con_nrcontabancaria  = '';
 		$scope.conta.con_ativa            = '';
 		$scope.conta.usu_codigo           = '';
 		$scope.conta.usu_nome             = '';
 		$scope.conta.tco_codigo           = '';
 		$scope.conta.tco_descricao        = '';
	};

 	$scope.limparLancamentos = function() {
        $scope.lancamentos.totaldebito = 0;
	    $scope.lancamentos.totalcredito = 0;
	};

	$scope.novaConta = function() {
		$scope.limparConta();
		document.getElementById("nova-conta").style.display = 'block';
	};

	$scope.salvarConta = function() {
		$http.post(getServerAddress()+"/WebFinanceApiRest/public/conta/salvar", $scope.conta)
		   	 .success(function(retorno) {		   	              
                if(retorno == 1) {
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
		 .success(function(retorno) {           
            if(retorno == 1) {
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

	$scope.limparCamposCtaBnk = function() {
		$scope.conta.con_banco            = '';
 		$scope.conta.con_agcontabancaria  = '';
 		$scope.conta.con_nrcontabancaria  = ''; 		
	};

	$scope.limparUsuario = function() {
		$scope.conta.usu_codigo = '';
		$scope.conta.usu_nome   = '';
	};

	$scope.validarTipoConta = function() {
		let flag = false;
		for (let tipoConta of $scope.tiposContas.values()) {
			if (tipoConta.tco_codigo == $scope.conta.tco_codigo) {
				flag = true;
			}
		}		
		if (!flag) {$scope.conta.tco_codigo = ''};
	};

	$scope.listarLancamentos = function($conta) { 	     	
	    $scope.carregarLancamentos($conta);  	     	    
 	    document.getElementById("openModalLancamentos").click();	     	    
	};
    
    $scope.carregarTiposContas();
    $scope.carregarContas(); 
    $scope.carregarUsuarios();   
};
