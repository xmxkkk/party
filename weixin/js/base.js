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

module.run(['$rootScope','$state','$stateParams','$ionicPopup','$ionicHistory'
  ,function($rootScope,$state,$stateParams,$ionicPopup,$ionicHistory){
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
	$rootScope.previousState_name=[];
	$rootScope.previousState_params=[];
    $rootScope.$on("$stateChangeSuccess",  function(event, toState, toParams, fromState, fromParams) {
        // to be used for back button //won't work when page is reloaded.
        // $rootScope.previousState_name = fromState.name;
        // $rootScope.previousState_params = fromParams;
		if(fromState.name){
			$rootScope.previousState_name.push(fromState.name);
			$rootScope.previousState_params.push(fromParams);
		}
    });
    //back button function called from back button's ng-click="back()"
    $rootScope.back = function() {//实现返回的函数
		// console.log($rootScope.previousState_name);
		// console.log($rootScope.previousState_params);
		$ionicHistory.goBack(-1);
		var view=$ionicHistory.currentView();
		if(view){
			console.log(view);
			var stateName=view.stateName;
			if(stateName=='upload'||stateName=='addService'){
				$ionicHistory.goBack(-1);
			}
		}

		/*
		var stateName=$rootScope.previousState_name.pop();
		var params=$rootScope.previousState_params.pop();

		if(stateName){
			if(stateName=='upload'||stateName=='addService'){
				$rootScope.back();
			}else{
				$state.go(stateName,params);
			}
		}else{
			$state.go('index',{});
		}*/
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
	$rootScope.islogin=function(result){
		if(result.status==999){
			$rootScope.alert(result.message,function(){
				$rootScope.go('login',{});
			},true);
		}

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
