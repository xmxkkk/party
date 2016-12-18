var module=angular.module('starter.controllers', ['starter.services','ionic']);

module.run(['$rootScope','$state','$ionicModal','$location','$http','ObjectFactory','$ionicHistory'
  ,function($rootScope,$state,$ionicModal,$location,$http,ObjectFactory,$ionicHistory){
    $rootScope.go=function(page,params){
      console.log(params);
      $state.go(page,params);
    };
    $rootScope.$on('$stateChangeStart',function (ev, to, toParams, from, fromParams) {
      $rootScope.previousState = from;
      $rootScope.previousParams = fromParams;
    });
    $rootScope.$on('$stateChangeSuccess', function (ev, to, toParams, from, fromParams) {
      $rootScope.previousState = from;
      $rootScope.previousParams = fromParams;
    });

    //$rootScope.baseUrl="http://121.199.36.110/party/index.php";
    //$rootScope.staticBaseUrl="http://121.199.36.110/party/";

    //$rootScope.baseUrl="http://192.168.1.187:89/index.php";
    //$rootScope.staticBaseUrl="http://192.168.1.187:89/";

    $rootScope.baseUrl="http://127.0.0.1:89/index.php";
    $rootScope.staticBaseUrl="http://127.0.0.1:89/";
    
    $rootScope.loginData = {};

    $ionicModal.fromTemplateUrl('templates/login.html', {
      scope: $rootScope
    }).then(function(modal) {
      $rootScope.modal = modal;
    });

  // Triggered in the login modal to close it
  $rootScope.closeLogin = function() {
    $rootScope.modal.hide();
  };

  // Open the login modal
  $rootScope.login = function() {
    $rootScope.modal.show();
  };

  $rootScope.logout=function(){
    $http.post($rootScope.baseUrl+'?m=Home&c=Index&a=logout',{}).success(function(data){
        ObjectFactory.set('userMd5','');
        $rootScope.menu={
          log_name:'登录',
          log_status:0,
          title:'个人'
        }
        $rootScope.$broadcast('member_logout',{});
      });
  };

  $rootScope.log=function(log_status){
    if(log_status){
      $rootScope.logout();
    }else{
      $rootScope.login();
    }
  }

  $rootScope.loginData={
    phone:'',
    password:'',
    status:0,
    message:''
  };
  $rootScope.error={
    status:0,
    message:''
  }

  // Perform the login action when the user submits the login form
  $rootScope.doLogin = function(){
    $http.post($rootScope.baseUrl+'?m=Home&c=Index&a=login',{phone:$rootScope.loginData.phone,password:$rootScope.loginData.password}).success(function(data){
      $rootScope.loginData.status=data.status;
      $rootScope.loginData.message=data.message;
      if(data.status==1){
      }else if(data.status==2){
      }else if(data.status==0){
        ObjectFactory.set('userMd5',data.md5);
        $rootScope.closeLogin();

        $rootScope.menu={
          log_name:'退出登录',
          log_status:1,
          title:data.realname
        }
        $rootScope.$broadcast('member_login',{});
      }
    });
  };
  $rootScope.menu={
    log_name:'登录',
    log_status:0,
    title:'个人'
  }
  $http.post($rootScope.baseUrl+'?m=Home&c=Index&a=is_login',{}).success(function(data){
    if(data.status==0){
      ObjectFactory.set('userMd5',data.md5);
      $rootScope.menu={
          log_name:'退出登录',
          log_status:1,
          title:data.realname
      }
    }
 });
}]);

