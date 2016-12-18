module.controller('AddScoreCtrl',['$scope','$state','ObjectFactory','$http','$ionicPopup','$stateParams','$location','$window','$ionicHistory'
    ,function($scope,$state,ObjectFactory,$http,$ionicPopup,$stateParams,$location,$window,$ionicHistory){
    
    $scope.md5=$stateParams.md5;
    $scope.type=$stateParams.type;
    $scope.eventId=$stateParams.eventId;

    $scope.remark={
        text:''
    };

    $scope.init=function(){
        $scope.selected={
            val:''
        }
        $scope.scores=ObjectFactory.get('scores');
        if(!$scope.scores){
            $http.post($scope.baseUrl+'?m=Home&c=Index&a=score',{}).success(function($data){
                $scope.scores=$data;
                ObjectFactory.set('scores',$scope.scores);
                $scope.event=$scope.scores[$stateParams.type].events[$scope.eventId];
            });
        }else{
            $scope.event=$scope.scores[$stateParams.type].events[$scope.eventId];
        }
        
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=group_member',{}).success(function(data){
            $scope.members=data;
            $scope.selected.val=$scope.md5;
        });
    }

    $scope.addScore=function(){
        var remark=$scope.remark.text;
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=add_score_event',{md5:$scope.selected.val,eventId:$scope.event.id,remark:remark}).success(function(data){
            // 一个确认对话框
            var confirmPopup = $ionicPopup.alert({
               title: "结果",
               template: "积分操作成功",
               okText:"返回首页"
            });
            confirmPopup.then(function(res) {
                $ionicHistory.goBack(-3);
            });
        });
    }
}])