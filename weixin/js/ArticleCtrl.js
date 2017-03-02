module.controller('ArticleCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {
	var article_id=$stateParams.item_id;

	$scope.init=function(){
		$scope.page={
			pageNo:0,
			items:[],
			more:false
		}
		$scope.item=null;
	}

	$scope.loadMore=function(){
		$http.post($scope.baseUrl+'/Home/home/article.html',{article_id:article_id,pageNo:$scope.page.pageNo}).success(function(data){
			$scope.item=data.item;
			$scope.page.pageNo++;
			if(data.news.length==0){
				$scope.page.more=true;
			}
			$scope.page.items=$scope.page.items.concat(data.news);
			$scope.$broadcast('scroll.infiniteScrollComplete');
		});
	}

});
