var module=angular.module('starter.base', ['ionic']);

module.run(['$rootScope','$state'
  ,function($rootScope,$state){
    $rootScope.go=function(page,params){
      $state.go(page,params);
    };
}]);