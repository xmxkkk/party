var module=angular.module('starter.controllers', []);

module.controller('IndexCtrl', function($scope) {

})

.controller('UploadCtrl', function($scope,$http,$stateParams,ObjectFactory){
    console.log($stateParams);
    $("img").attr("src",$stateParams.url);

    $scope.upload=function(){
        var file=ObjectFactory.get('file');

        var formdata = new FormData();
        formdata.append('title_id',$stateParams.title_id);
        formdata.append('menu_id',$stateParams.menu_id);
        formdata.append("fileList", file);                  
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress",  function(e){
            if(e.loaded==e.total){
                
            }
        }, false);
        xhr.addEventListener("load", function(e){
            if(e.target.status==200){
                var data = eval('(' + e.target.response + ')');
                $scope.alert(data.message,function(res){
                    console.log("上传失败");
                    $scope.back();

                },data.status!=0);

                if(ObjectFactory.get('file')){
                    ObjectFactory.get('file').value="";
                }
                ObjectFactory.set('file',null);
            }
        }, false);
        xhr.addEventListener("error", function(e){
            
        }, false);
        xhr.open("POST",$scope.baseUrl+"/Home/home/upload", true);
        xhr.setRequestHeader("X_FILENAME", file.name);
        xhr.send(formdata);
    }
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
