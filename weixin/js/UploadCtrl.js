module.controller('UploadCtrl', function($scope,$http,$stateParams,ObjectFactory,$ionicLoading){
    console.log($stateParams);

    var info=ObjectFactory.get("info");

    $("img").attr("src",info.url);

    $scope.upload=function(){
        var file=ObjectFactory.get('file');

        var formdata = new FormData();
        formdata.append('info',JSON.stringify(info));
        formdata.append("fileList", file);
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress",  function(e){
            if(e.loaded==e.total){
                $ionicLoading.hide();
            }
        }, false);
        xhr.addEventListener("load", function(e){
            $ionicLoading.hide();
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
            $ionicLoading.hide();
            
        }, false);
        xhr.open("POST",$scope.baseUrl+"/Home/home/upload", true);
        xhr.setRequestHeader("X_FILENAME", file.name);
        xhr.send(formdata);
        $ionicLoading.show({template: '上传中'});
    }
})