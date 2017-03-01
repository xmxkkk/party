module.controller('UploadCtrl', function($scope,$http,$stateParams,ObjectFactory,$ionicLoading){
    console.log($stateParams);
	var file=document.getElementById('file');

	var compress=function(data){
		try{
			var img=new Image();
			var maxW=800;
			img.onload=function(){
				var canvas=document.createElement("canvas");
				var ctx=canvas.getContext('2d');
				if(img.width>maxW){
					img.height*=maxW/img.width;
					img.width=maxW;
				}
				canvas.width=img.width;
				canvas.height=img.height;

				ctx.clearRect(0,0,canvas.width,canvas.height);
				ctx.drawImage(img,0,0,img.width,img.height);

				var dataUrl=canvas.toDataURL('image/jpeg',0.5);

				var imgDOM=document.getElementById('img');
				imgDOM.onload=function(){
					$ionicLoading.hide();
					$scope.canUpload=true;
				};
				imgDOM.src=dataUrl;
				// var newBase64 = dataUrl.replace(/\+/g, "%2B");
				var info=ObjectFactory.get("info");
				info.data=dataUrl;
				ObjectFactory.set("info",info);
			}
			img.src=data;
		}catch(err){
			alert(err);
		}
	}
	var dataURItoBlob=function(base64Data) {
		var byteString;
		if (base64Data.split(',')[0].indexOf('base64') >= 0)
			byteString = atob(base64Data.split(',')[1]);
		else
			byteString = unescape(base64Data.split(',')[1]);
		var mimeString = base64Data.split(',')[0].split(':')[1].split(';')[0];
		var ia = new Uint8Array(byteString.length);
		for (var i = 0; i < byteString.length; i++) {
			ia[i] = byteString.charCodeAt(i);
		}
		return new Blob([ia], {type:mimeString});
	}
	var post=function(data){
		$http.post($scope.baseUrl+"/Home/home/upload",{data:JSON.stringify(data)}).success(function(msg){
			ObjectFactory.set("info",null);
			$scope.alert(msg.message,function(res){
				$scope.back();
			},msg.status!=0);
			$ionicLoading.hide();
		});
		$ionicLoading.show({template: '上传中'});
	}
	var click=function(){
		if ( typeof(FileReader) === 'undefined' ){
			$scope.alert('浏览器不支持',function(){},true);
			return;
		}

		if(file.touchstart&&typeof(file.touchstart)=="function"){
			file.touchstart();
		}
		if(file.click&&typeof(file.click)=="function"){
			file.click();
		}
	}
	$scope.camera=function(){
		click();
	}


	$scope.upload=function(){
		if(!$scope.canUpload){
			click();
		}else{
			var info=ObjectFactory.get("info");
			post(info);
		}
	}

	$scope.init=function(){
		$scope.canUpload=false;
		file.addEventListener('change',function(){
			$ionicLoading.show({template: '压缩中'});

			var reader=new FileReader();
			reader.onload=function(e){
				// console.log(this.result);
				// document.getElementById('image').src=this.result;
				try{
					compress(this.result);
				}catch(err){
					alert(err);
				}
			}
			reader.readAsDataURL(this.files[0]);
		});

	}




/*
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
    }*/
})
