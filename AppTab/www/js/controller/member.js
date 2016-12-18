module.controller('MemberCtrl',function($scope,$stateParams,$http,ObjectFactory){

  $scope.$on('member_login',function(data){
    console.log('member in login');
    $scope.info();
  });
  $scope.$on('member_logout',function(data){
  });

  $scope.info=function(){
    var isLogin=ObjectFactory.get('isLogin');
    var userMd5=ObjectFactory.get('userMd5');
    if($stateParams.md5){
      $http.post($scope.baseUrl+'?m=Home&c=Index&a=member',{md5:$stateParams.md5}).success(function(data){
        $scope.member=data;
        console.log($scope.member.md5);
      });
    }else{
      if(userMd5){
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=member',{md5:userMd5}).success(function(data){
          $scope.member=data;
        });
      }else{
        $scope.login();
      }
    }
  }
  
  $scope.init=function(){
    $scope.member={};
    $scope.info();

  }


});