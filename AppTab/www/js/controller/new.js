module.controller('NewCtrl',['$scope','$state','ObjectFactory','$http','$ionicPopup','$stateParams','$location','$window','$ionicHistory'
    ,function($scope,$state,ObjectFactory,$http,$ionicPopup,$stateParams,$location,$window,$ionicHistory){
    
    $scope.init=function(){
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=newdetail',{id:$stateParams.id}).success(function(data){
            $scope.item=data.data;
        });
    }
}]);