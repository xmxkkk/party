var module=angular.module('starter.controllers', []);

module.controller('IndexCtrl', function($scope) {

})


.controller('BindidcardCtrl', function($scope,$http,$stateParams,$ionicPopup) {
    console.log(0);
    $scope.init=function(){
        $scope.user={
            idcard:""
        };
    }

    $scope.submitBind=function(){
        console.log("idcard="+$scope.user.idcard);
        $http.post($scope.baseUrl+'/Home/home/bindidcard.html',{idcard:$scope.user.idcard,openid:"1234567890"}).success(function(data){
            console.log(data);
            if(data.status!=0){
                $scope.alert(data.message,null,true);
            }else{
                $scope.alert('绑定成功！',function(res){
                    $scope.back();
                },false);
            }
        });
    }
});
;
