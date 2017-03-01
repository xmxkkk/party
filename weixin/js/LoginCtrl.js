module.controller('LoginCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {

	$scope.init=function(){
		$scope.user={};
	}
	$scope.login=function(){
		$http.post($scope.baseUrl+'/Home/home/login.html',{phone:$scope.user.phone}).success(function(data){
			if(data.status==1){
				$scope.alert(data.message,function(){
					$scope.back();
					// window.location.reload();
				},false);
			}else{
				$scope.alert(data.message,function(){},true);
			}
	    });
	}
});
