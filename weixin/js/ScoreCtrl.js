module.controller('ScoreCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {
   $scope.tabIdx=0;
   var year=$stateParams.year;

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
	    });
   }
   $scope.selectTab(tabIdx);
});
