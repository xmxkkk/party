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

module.run(['$rootScope','$state','$stateParams','$ionicPopup'
  ,function($rootScope,$state,$stateParams,$ionicPopup){
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
    $rootScope.alert=function(message,cb,isred){
        var okType;
        if(isred){
            okType="button-assertive";
        }
        $ionicPopup.alert({
            title: '提示',
            template: message,
            okType:okType,
            okText:'确定'
        }).then(function(res){
            if(cb)cb(res);
        });
    }
    $rootScope.confirm=function(message,cb){
        $ionicPopup.confirm({
            title:'提示',
            template: message,
            cancelText:'取消',
            okText:'确定',
            okType: 'button-positive'
        }).then(function(res){
            console.log(res);
            if(cb)cb(res);
        });
    }
    $rootScope.confirm=function(message,cb){
        $ionicPopup.confirm({
            title:'提示',
            template: message,
            cancelText:'取消',
            okText:'确定',
            okType: 'button-positive'
        }).then(function(res){
            console.log(res);
            if(cb)cb(res);
        });
    }
    

    $rootScope.showInput=function(title,cb){
        $ionicPopup.show({
            template: '<input type="text" ng-model="data.text">',
            title: title,
            scope: $rootScope,
            buttons: [
                { text: '取消' },
                {
                    text: '<b>保存</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if (!$rootScope.data.text) {
                            e.preventDefault();
                        } else {
                            return $rootScope.data.text;
                        }
                    }
                },
            ]
        }).then(function(res){
            if(cb)cb(res);
        });
    }
    $rootScope.getObjectURL=function(file) {
        var url = null ; 
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
}]);