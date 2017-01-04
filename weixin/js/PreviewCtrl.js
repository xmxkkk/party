module.controller('PreviewCtrl', function($scope,$http,$stateParams) {
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
    var sendData=function(picture){
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
        console.log("picture");
        console.log(picture);
        if(type=='up'){
            picture.ord-=1.5;
            sendData(picture,type,id);
        }else if(type=='down'){
            picture.ord+=1.5;
            sendData(picture,type,id);
        }else if(type=='del'){
            $scope.confirm('确认删除',function(res){
                if(res){
                    picture.ord=3.14;
                    sendData(picture,type,id);
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
            //$(".page").append('<div class="list"><div class="item" style="text-align:center;border:0;">暂无资料！</div></div>');
        }
        
    });
});