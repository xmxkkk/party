var module=angular.module('starter.base', ['ionic']);

module.run(['$rootScope','$state'
  ,function($rootScope,$state){
    $rootScope.go=function(page,params){
        $state.go(page,params);
    };
    if(document.domain=="localhost"||document.domain=="127.0.0.1"||document.domain=="192.168.31.150"){
        $rootScope.baseUrl="http://"+document.domain+"/index.php";
        $rootScope.staticBaseUrl="http://"+document.domain;
    }else{
        $rootScope.baseUrl="http://iparty.fuhaidev.com/index.php";
        $rootScope.staticBaseUrl="http://iparty.fuhaidev.com/";
    }
}]);