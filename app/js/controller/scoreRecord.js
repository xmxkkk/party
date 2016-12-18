
module.controller('ScoreRecordCtrl',['$scope','$http','ObjectFactory','$stateParams',function($scope,$http,ObjectFactory,$stateParams){
    $scope.$on('member_login',function(e){
        console.log('login in scoreRecord');
        $scope.init();
    });

    $scope.$on('member_logout',function(e){
    });
    
    $scope.init=function(){
        $scope.page={
            pageNo:0,
            more:0,
            items:[],
            pageSize:0
        }
        $scope.loadMore();
    }

    $scope.loadMore=function(){
        var md5=ObjectFactory.get('userMd5');

        $http.post($scope.baseUrl+'?m=Home&c=Index&a=score_record',{md5:md5,p:$scope.page.pageNo}).success(function(data){
            if($scope.page.pageNo==0){
                $scope.page.items=[];
            }
            if(!data.data || data.data.length==0){
                $scope.page.more=1;
                data.data=[];
            }

            $scope.page.items=$scope.page.items.concat(data.data);
            $scope.page.pageNo=$scope.page.pageNo+1;
            $scope.page.pageSize=$scope.page.items.length;
            $scope.$broadcast('scroll.infiniteScrollComplete');
        }).error(function(){
            $scope.page.more=1;
        });
    }

    var md5=ObjectFactory.get('userMd5');
    if(!md5){
        $scope.login();
        return;
    }

}]);