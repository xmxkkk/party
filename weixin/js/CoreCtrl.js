module.controller('CoreCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory,$ionicNavBarDelegate) {
    // console.log($stateParams);
    $http.post($scope.baseUrl+'/Home/home/core.html',{}).success(function(data){
        console.log(data);
		$scope.islogin(data);
        $scope.user=data;
    });
    $http.post($scope.baseUrl+'/Home/home/scoreMenus.html',{}).success(function(data){
        console.log(data);
        $scope.scoreMenus=data;
    });

	$ionicNavBarDelegate.title("个人中心");
});
