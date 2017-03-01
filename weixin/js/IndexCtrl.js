module.controller('IndexCtrl', function($scope,$rootScope,$http,$stateParams) {
	$http.post($scope.baseUrl+'/Home/home/index.html',{}).success(function(data){
		if(data.status==999){
			$scope.go('login');
		}else if(data.status==1){
			$scope.items=data.data;
		}
	});
})
