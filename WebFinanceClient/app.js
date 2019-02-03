
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
    .when('/gergrupofamiliar', {
      templateUrl : '/WebFinanceClient/pages/gerenciargrupofamiliar.html',
      controller  : 'grupoFamiliarController'
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

function ocultarElementoMensagem(msg, option, ehErro) {
   document.getElementById("alert-user").style.display = 'none';
}

function exibirOcultarElementoMensagem(msg, option, ehErro) {

  //var elBody = document.getElementById("alert-user");
  var elType = document.getElementById("alert-type");
  
  var segundos = 4;
  
  if(option == true)
    elType.style.display = 'block';
  else
    elType.style.display = 'none';  

  document.getElementById("alert-msg-user").innerHTML = msg;
    
  if (ehErro == false){
    elType.classList.remove('alert-warning');     
    elType.classList.add('alert-success');
  } else {
    elType.classList.remove('alert-success');
    elType.classList.add('alert-warning');     
  }

  setTimeout(function () {  
     if(option == false)
      elType.style.display = 'block';
    else
      elType.style.display = 'none';       
  }, segundos * 1000);  
    
};