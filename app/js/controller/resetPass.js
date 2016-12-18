module.controller('ResetPassCtrl',['$scope','$http','$state','ObjectFactory','$stateParams','$ionicPopup'
    ,function($scope,$http,$state,ObjectFactory,$stateParams,$ionicPopup){
    $scope.$on('member_login',function(e){
        console.log('login in scoreRecord');
        $scope.init();
    });

    $scope.$on('member_logout',function(e){
    });

    $scope.init=function(){
        $scope.resetPass={
            oldpassword:'',
            newpassword:'',
            renewpassword:'',
            status:0,
            message:''
        }
    }

    $scope.doResetPass=function(){

        var oldpassword=$scope.resetPass.oldpassword;
        var newpassword=$scope.resetPass.newpassword;
        var renewpassword=$scope.resetPass.renewpassword;

        if(oldpassword.length<6 ){
            $scope.resetPass.status=1;
            $scope.resetPass.message='旧密码错误';

            return;
        }
        if(newpassword.length<6 ){
            $scope.resetPass.status=1;
            $scope.resetPass.message='新密码长度不能小于6位';
            return;
        }
        if(newpassword!=renewpassword) {
            $scope.resetPass.status=1;
            $scope.resetPass.message='新密码与确认密码不同';
            return;
        }
        
        $http.post($scope.baseUrl+'?m=Home&c=Index&a=reset_pass',{oldpassword:oldpassword,newpassword:newpassword}).success(function(data){
            
            if(data.status!=0){
                $scope.resetPass.status=data.status;
                $scope.resetPass.message=data.message;
            }else{
                 var confirmPopup = $ionicPopup.alert({
                   title: "结果",
                   template: "密码修改成功",
                   cancelText:"确认"
                });
                confirmPopup.then(function(res) {
                    $state.go('app.member');
                    location.reload();
                });
            }
        });
    }

    var md5=ObjectFactory.get('userMd5');
    if(!md5){
        $scope.login();
        return;
    }
}]);