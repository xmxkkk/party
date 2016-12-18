module.controller('TaskCtrl',['$scope','$http','ObjectFactory',function($scope,$http,ObjectFactory){
	
    $scope.$on('member_login',function(data){
        $scope.init();
    });
    $scope.$on('member_logout',function(data){
    });

    $scope.init=function(){
        $scope.tasks=[];
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=task').success(function(data){
            $scope.tasks=data.tasks;
        });
    }
    var md5=ObjectFactory.get('userMd5');
    if(!md5){
        $scope.login();
        return;
    }

}])