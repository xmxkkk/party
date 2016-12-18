module.controller('NewsCtrl',['$scope','$state','ObjectFactory','$http','$ionicPopup','$stateParams','$location','$window','$ionicHistory'
    ,function($scope,$state,ObjectFactory,$http,$ionicPopup,$stateParams,$location,$window,$ionicHistory){
    
    $scope.init=function(){
        $scope.page={
            pageNo:0,
            more:0,
            items:[],
            pageSize:0
        }
        if($stateParams.type==1){
            $scope.page.title="支部公告";
        }else if($stateParams.type==2){
            $scope.page.title="支部新闻";
        }
//        $scope.loadMore();
    }

    $scope.loadMore=function(){
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=news',{p:$scope.page.pageNo,type:$stateParams.type}).success(function(data){
            if(data.news.length==0){
                $scope.page.more=1;
            }
             $scope.page.items=$scope.page.items.concat(data.news);
             $scope.page.pageNo=$scope.page.pageNo+1;
             $scope.page.pageSize=$scope.page.items.length;
             $scope.$broadcast('scroll.infiniteScrollComplete');
        }).error(function(){
            $scope.page.more=1;
        });
    }
}]);