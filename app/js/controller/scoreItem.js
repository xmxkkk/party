module.controller('ScoreItemCtrl',['$scope','$state','ObjectFactory','$stateParams','$http'
    ,function($scope,$state,ObjectFactory,$stateParams,$http){
    $scope.md5=$stateParams.md5;
    $scope.type=$stateParams.type;

    $scope.scores=ObjectFactory.get('scores');
    if(!$scope.scores){
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=score',{}).success(function($data){
            $scope.scores=$data;
            ObjectFactory.set('scores',$scope.scores);
            $scope.events=$scope.scores[$stateParams.type].events;
        });
    }else{
        $scope.events=$scope.scores[$stateParams.type].events;
    }
    
}])