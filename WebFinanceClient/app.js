
var app = angular.module('AppFinance', ['ngRoute']);

app.config(function($routeProvider){
  $routeProvider
    .when('/gerusuario', {
      templateUrl : '/WebFinanceClient/pages/gerenciarusuario.html',
      controller  : 'usuarioController'
    })
    .when('/gerconta', {
      templateUrl : '/WebFinanceClient/pages/gerenciarconta.html',
      controller  : 'controlador'
    })
    .when('/gercategoria', {
      templateUrl : '/WebFinanceClient/pages/gerenciarcategoria.html',
      controller  : 'controlador'
    })
    .when('/dashmensal', {
      templateUrl : '/WebFinanceClient/pages/dashboardmensal.html',
      controller  : 'controlador'
    })
    .when('/', {
      templateUrl : '/WebFinanceClient/pages/dashboardinicial.html',
      controller  :	'controlador' 
    })
    .otherwise('/');    
});

app.controller('controlador', controlador);

function controlador($scope, $http) {
	    $scope.message = 'msg';

		$scope.login = {
		  user: '',
		  password: ''
		}		
	
		$scope.autenticacao = function(){
			$http
				.post("http://localhost:8080/WebFinanceApiRest/public/auth", $scope.login)
				.success(function(retorno){
			        if(retorno == '2'){
			          alert('Usuário autenticado com sucesso');
			          $scope.login.user='';
			          $scope.login.password=''; 
			          window.location.replace("/WebFinanceClient/");
			        }
			        else if(retorno == '1')
			          alert('Usuário ou senha inválidos');
			        else if(retorno == '0')
			          alert('Usuário ou senha não informados');			    
			    });
		};
};
