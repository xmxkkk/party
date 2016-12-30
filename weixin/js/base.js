var module=angular.module('starter.base', ['ionic']);

module.factory('ObjectFactory',[function(){
    var _obj=[];
    return {
        set:function(key,obj){
            _obj[key]=obj;
        },
        get:function(key){
            return _obj[key];
        }
    }
}])

module.run(['$rootScope','$state','$stateParams'
  ,function($rootScope,$state,$stateParams){
    $rootScope.$state = $state;  
    $rootScope.$stateParams = $stateParams;  
    $rootScope.$on("$stateChangeSuccess",  function(event, toState, toParams, fromState, fromParams) {  
        // to be used for back button //won't work when page is reloaded.  
        $rootScope.previousState_name = fromState.name;  
        $rootScope.previousState_params = fromParams;  
    });  
    //back button function called from back button's ng-click="back()"  
    $rootScope.back = function() {//实现返回的函数  
        $state.go($rootScope.previousState_name,$rootScope.previousState_params);  
    };  

    $rootScope.go=function(page,params){
        $state.go(page,params);
    };
    if(document.domain=="localhost"||document.domain=="127.0.0.1"||document.domain=="192.168.31.150"){
        $rootScope.baseUrl="http://"+document.domain+"/index.php?s=";
        $rootScope.staticBaseUrl="http://"+document.domain;
    }else{
        $rootScope.baseUrl="http://iparty.fuhaidev.com/index.php?s=";
        $rootScope.staticBaseUrl="http://iparty.fuhaidev.com/";
    }
}]);