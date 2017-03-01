module.controller('ArticleDetailCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {
	var id=$stateParams.id;

	$scope.init=function(){
		$http.post($scope.baseUrl+'/Home/home/articleDetail.html',{id:id}).success(function(data){
			$scope.news=data.new_;
			$scope.item=data.item;
		});
	}
});
