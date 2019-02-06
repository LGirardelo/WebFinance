
app.controller('usuarioController', usuarioController);

function usuarioController($scope, $http) {
	    
	$scope.usuario = {
 		usu_codigo   : '',
 		usu_nome     : '',
 		usu_senha    : '',
 		usu_senha2   : '',
 		usu_login    : '',
 		usu_email    : '',
 		usu_ativo    : '',
 		gfa_codigo   : '',
 		gfa_descricao: ''
	}	

	$scope.gruposFamiliares = {};
		
	$scope.usuarios = {};


	$scope.carregarGruposFamiliares = function(){
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/gruposFamiliares")
			.success(function(retorno){
		       console.log(retorno);
		       $scope.gruposFamiliares = retorno;			     
			});
	};
	
	$scope.carregarUsuarios = function(){
		$http
			.get(getServerAddress()+"/WebFinanceApiRest/public/usuarios")
			.success(function(retorno){
		       console.log(retorno);
		       $scope.usuarios = retorno;			     
			});
	};

	$scope.editarUsuario = function($user){
 		$scope.usuario.usu_codigo = $user.usu_codigo;
 		$scope.usuario.usu_nome = $user.usu_nome;
 		$scope.usuario.usu_email = $user.usu_email;
 		$scope.usuario.usu_login = $user.usu_login;
 		$scope.usuario.usu_ativo = $user.usu_ativo; 
 		$scope.usuario.gfa_codigo = $user.gfa_codigo;
 		$scope.usuario.gfa_descricao = $user.gfa_descricao; 		
	};

	$scope.limparUsuario = function(){
		$scope.usuario.usu_codigo    = 0;
		$scope.usuario.usu_nome      = '';
		$scope.usuario.usu_login     = '';
		$scope.usuario.usu_senha     = '';
		$scope.usuario.usu_senha2    = '';
		$scope.usuario.usu_email     = '';
		$scope.usuario.usu_ativo     = '';
		$scope.usuario.gfa_codigo    = 0;
		$scope.usuario.gfa_descricao = '';		
	};

	$scope.novoUsuario = function(){
		$scope.limparUsuario();
	};

	$scope.salvarUsuario = function(){
		if (($scope.usuario.usu_senha2 != $scope.usuario.usu_senha) || ($scope.usuario.usu_senha == ''))
			exibirOcultarElementoMensagem('As senhas devem ser informadas e devem ser iguais!', true, true);
		else
  			$http
		   	 .post(getServerAddress()+"/WebFinanceApiRest/public/usuario/salvar", $scope.usuario)
		   	 .success(function(retorno){           
                if(retorno == 1){
                  $scope.limparUsuario(); 	 
                  $scope.carregarUsuarios();
                  exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
                }
			  	else{			  	  	
			  	  exibirOcultarElementoMensagem('Erro ao salvar o usuário!', true, true);
		        } 	
		    })
		    .error(function(erro){		    	
		    	exibirOcultarElementoMensagem('Erro ao salvar o usuário!', true, true);
		    });
	};

	$scope.excluirUsuario = function($user){
		$http
		 .post(getServerAddress()+"/WebFinanceApiRest/public/usuario/excluir", $user)
		 .success(function(retorno){           
            if(retorno == 1){
              $scope.limparUsuario(); 	 
              $scope.carregarUsuarios();
              exibirOcultarElementoMensagem('Operação executada com sucesso!', true, false);
            }
			else			  	  	
			  exibirOcultarElementoMensagem('Erro ao excluir usuário!', true, true);		    
		})
		.error(function(erro){		    	
		  exibirOcultarElementoMensagem('Erro ao excluir usuário!', true, true);
		});
	};

    $scope.carregarUsuarios();    
    $scope.carregarGruposFamiliares();
};
