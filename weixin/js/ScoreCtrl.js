module.controller('ScoreCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory,$ionicNavBarDelegate) {
   $scope.tabIdx=0;
   var year=$scope.year=$stateParams.year;

	var tabIdx=ObjectFactory.get('tabIdx');
	if(!tabIdx){
		tabIdx=0;
		ObjectFactory.set('tabIdx',tabIdx);
	}

   $scope.selectTab=function(idx){
	   ObjectFactory.set('tabIdx',idx);

   		$scope.tabIdx=idx;

   		$http.post($scope.baseUrl+'/Home/home/score.html',{type:idx,year:year}).success(function(data){
   			if($scope.tabIdx==0){
   				$scope.scores=data;
   			}else if($scope.tabIdx==1){
   				$scope.scores=data;
   			}else if($scope.tabIdx==2){
   				$scope.scores=data;
   			}
			$ionicNavBarDelegate.title(year+"年积分事件");
	    });
   }
   $scope.selectTab(tabIdx);
   $ionicNavBarDelegate.showBackButton(true);
});
