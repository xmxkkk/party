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
                if(data.status==1){
                    $scope.alert(data.message,null,true);
                }
                console.log("上传成功");
                $scope.back();
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
    $http.get($scope.baseUrl+'/Home/home/picture/title_id/'+$stateParams.title_id+"/menu_id/"+$stateParams.menu_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);


        var html="";

        if($scope.item.pictures){
            /*
            html='<div id="slider"><ul>';
            for(var i=0;i<$scope.item.pictures.length;i++){
                if(i==0){
                    html += '<li style="display:block;"><div class="pinch-zoom"><img src="'+$scope.item.pictures[i].picture_path+'"/></div></li>';
                }else{
                    html += '<li style="display:none;"><div class="pinch-zoom"><img src="'+$scope.item.pictures[i].picture_path+'"/></div></li>';
                }
            }
            html+='</ul></div>';
            $(".page").append(html);

            $('div.pinch-zoom').each(function () {
                new RTP.PinchZoom($(this), {});
            });*/

            
            
            html="";
            for(var i=0;i<$scope.item.pictures.length;i++){
                html += '<div><img src="'+$scope.item.pictures[i].picture_path+'" style="width:100%;"></div>';
            }
            $(".page").append(html);


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


            /*
            var container=$(".container");
            var body=$(".view-container");
            var bodyWidth=body.width();
            var bodyHeight=body.height();
            var bodyRadio=1.0*bodyWidth/bodyHeight;
            console.log(bodyWidth+"/"+bodyHeight);
            container.hide();
            container.css("width",bodyWidth).css("height",bodyHeight);
             
            $(".page img").each(
                function(){
                    $(this).click(function(){
                        $(".page").hide();
                        
                        var img=$(".container img");
                        img.attr("src",$(this).attr("src"));

                        var new_image = new Image();
                        new_image.src = img[0].src;
                        var imgWidth=new_image.width;
                        var imgHeight=new_image.height;
                        var imgRadio=1.0*imgWidth/imgHeight;

                        console.log(imgWidth+"/"+imgHeight);

                        

                        img.css("width","none").css("height","none");
                        if(1.0*imgWidth/imgHeight > 1.0*bodyWidth/bodyHeight){
                            var hei=bodyWidth/imgRadio;
                            img.css("width",bodyWidth).css("height",hei);
                        }else{
                            var hei=bodyHeight*imgRadio;
                            img.css("height",bodyHeight).css("width",hei);
                        }

                        console.log("img="+img.width()+"/"+img.height());

                        var left=(bodyWidth-img.width())/2;
                        var top=(bodyHeight-img.height())/2;
                        img.css("left",left).css("top",top);

                        container.show();

                    })
                }
            );

            container.click(function(){
                var img=$(".container img");
                var new_image = new Image();
                new_image.src = img[0].src;
                var imgWidth=new_image.width;
                var imgHeight=new_image.height;
                var imgRadio=1.0*imgWidth/imgHeight;
                
                imgWidth*=2;
                imgHeight*=2;
                
                img.css("width",imgWidth).css("height",imgHeight);

                if(1.0*imgWidth/imgHeight > 1.0*bodyWidth/bodyHeight){
                    var left=(bodyWidth-imgWidth)/2;
                    var top=(bodyHeight-imgHeight)/2;
                    img.css("left",left).css("top",top);
                }else{
                    var left=(bodyWidth-imgWidth)/2;
                    var top=(bodyHeight-imgHeight)/2;
                    img.css("left",left).css("top",top);
                }

            });*/

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
