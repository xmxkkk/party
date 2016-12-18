module.controller('TopnCtrl',['$scope','$state','ObjectFactory','$stateParams','$http'
    ,function($scope,$state,ObjectFactory,$stateParams,$http){
    
    $scope.init=function(){
        $scope.items=[];
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=topn',{}).success(function(data){
            $scope.items=data.members;
        });
    }
}])