module.controller('AppCtrl', function($scope, $ionicModal, $timeout,ObjectFactory,$http,$rootScope,$ionicHistory,$state) {
  
  $scope.$on('member_logout',function(e){
    console.log('app on member_logout');
  });
  $scope.$on('member_login',function(e){
    console.log('app on member_login');
  });
});