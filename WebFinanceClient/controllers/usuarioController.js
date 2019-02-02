
app.controller('usuarioController', usuarioController);

function usuarioController($scope, $http) {
	    
	$scope.usuario = {
 		usu_codigo: '',
 		usu_nome: '',
 		usu_senha: '',
 		usu_login: '',
 		usu_email: '',
 		usu_ativo: ''
	}	
		
	$scope.usuarios = {};
	
	$scope.carregarUsuarios = function(){
		$http
			.get("http://localhost:8080/WebFinanceApiRest/public/usuarios")
			.success(function(retorno){
		       console.log(retorno);
		       $scope.usuarios = retorno;			     
			});
	};

	$scope.editarUsuario = function($user){
 		$scope.usuario = $user;
	};

    $scope.carregarUsuarios();
};
