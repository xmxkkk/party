angular.module('starter.controllers', [])

.controller('IndexCtrl', function($scope) {

})
.controller('TitleCtrl', function($scope,$http,$stateParams) {
    console.log($stateParams);
    $http.get($scope.baseUrl+'/Home/home/item/item_id/'+$stateParams.item_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);
    });
})
.controller('MenuCtrl', function($scope,$http,$stateParams,ObjectFactory) {
    console.log($stateParams);
    $http.get($scope.baseUrl+'/Home/home/menu/item_id/'+$stateParams.item_id+"/title_id/"+$stateParams.title_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);
    });
    function getObjectURL(file) {
        var url = null ; 
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
    $scope.takePicture=function(obj){
        console.log(obj);
        var file=$("input[type=file]");
        file.val("");

        file.change(function(){
            var objUrl=getObjectURL(this.files[0]);
            obj.url=objUrl;
            console.log(obj);
            ObjectFactory.set('file',this.files[0]);
            $scope.go('upload',obj);
        });
        file.click();
    }
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
.controller('PreviewCtrl', function($scope,$http,$stateParams) {
    console.log($stateParams);
    var sortItem=function(){
        var temp=new Array();
        for(var i=0;i<$scope.item.pictures.length;i++){
            if($scope.item.pictures[i].ord!=3.14){
                temp[temp.length]=$scope.item.pictures[i];
            }
        }
        $scope.item.pictures=temp;

        for(var i=0;i<$scope.item.pictures.length;i++){
            for(var j=i;j<$scope.item.pictures.length;j++){
                if($scope.item.pictures[i].ord>$scope.item.pictures[j].ord){
                    var temp = $scope.item.pictures[i];
                    $scope.item.pictures[i] = $scope.item.pictures[j];
                    $scope.item.pictures[j] = temp;
                }
            }
        }
        for(var i=0;i<$scope.item.pictures.length;i++){
            $scope.item.pictures[i].ord=i;
        }
    }
    var sendData=function(){
        sortItem();

        var data=new Array();
        for(var i=0;i<$scope.item.pictures.length;i++){
            data[data.length]={id:$scope.item.pictures[i].id,ord:$scope.item.pictures[i].ord};
        }
        data=JSON.stringify(data);

        $http.post($scope.baseUrl+'/Home/home/imageOp.html',{data:data,title_id:picture.title_id,menu_id:picture.menu_id}).success(function(data){
            console.log(data);
        });
    }
    $scope.imageOp=function(picture,type,id){
        if(type=='up'){
            picture.ord-=1.5;
            sendData();
        }else if(type=='down'){
            picture.ord+=1.5;
            sendData();
        }else if(type=='del'){
            $scope.confirm('确认删除',function(res){
                if(res){
                    picture.ord=3.14;
                    sendData();
                }
            });
        }
    }
    
    $http.get($scope.baseUrl+'/Home/home/picture/title_id/'+$stateParams.title_id+"/menu_id/"+$stateParams.menu_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);

        var html="";

        if($scope.item.pictures){

            /*
            html="";
            for(var i=0;i<$scope.item.pictures.length;i++){
                html += '<div style="position:relative;">';
                html += '<img src="'+$scope.item.pictures[i].picture_path+'" style="width:100%;left:0;top:0">';
                html += '<div style="position:absolute;bottom:10px;width:100%;text-align:center;filter:alpha(opacity=80);-moz-opacity:0.8;opacity:0.8;">';
                html += '<i class="button button-dark icon ion-arrow-up-c" ng-click="imageUp()"></i>&nbsp;';
                html += '<i class="button button-dark icon ion-arrow-down-c" ng-click="imageDown()"></i>&nbsp;';
                html += '<i class="button button-dark icon ion-close-round" ng-click="imageDel()"></i>';
                html += '</div>';
                html += '</div>';
            }
            $(".page").append(html);*/


            /*
            $(".page img").each(function(){
                $(this).click(function(){
                    $(".page").hide();
                    $("#slider").show();
                    html ='<ul>';
                    html += '<li style="display:block;"><div class="pinch-zoom"><img src="'+$(this).attr("src")+'"/></div></li>';
                    html +='</ul>';
                    $("#slider").append(html);

                    $('div.pinch-zoom').each(function () {
                        new RTP.PinchZoom($(this), {});
                    });

                    window.mySwipe = new Swipe(document.getElementById('slider'), {
                        speed: 200
                    });
                });
            });
            */

        }else{
            $(".page").append('<div class="list"><div class="item" style="text-align:center;border:0;">暂无资料！</div></div>');
        }
        
    });
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
