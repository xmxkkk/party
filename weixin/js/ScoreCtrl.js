module.controller('ScoreCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {
   $scope.tabIdx=0;
   var year=$stateParams.year;

   $scope.selectTab=function(idx){
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
   $scope.selectTab(0);
});
