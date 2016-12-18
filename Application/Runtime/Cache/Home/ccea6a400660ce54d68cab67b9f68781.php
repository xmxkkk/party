<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
	<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap-material-design.css">
	<link rel="stylesheet" type="text/css" href="/Public/css/ripples.css">
	<link rel="stylesheet" type="text/css" href="/Public/css/style.css">
	<script type="text/javascript" src="/Public/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/js/material.js"></script>
	<script type="text/javascript" src="/Public/js/ripples.js"></script>
	<title>党员登录</title>
	<style type="text/css">
	*{font-family: "微软雅黑"}
	.xlist_item{border-bottom:1px solid #d2d2d2;line-height:45px;}
	.xlist_item_left{color:#666;letter-spacing:5px;}
	.form-group{margin-top:10px;}
	.material-input{padding-left: 20px;}
	</style>
	<script type="text/javascript">
	$(function(){
		$("#submit_login").click(function(){
			var phone=$("#phone").val();
			var password=$("#password").val();

			$.post("<?php echo U('Index/login');?>",{phone:phone,password:password},function(data){
				if(data.status==1){
					$("#phoneText").text(data.message);
				}else if(data.status==2){
					$("#passwordText").text(data.message);
				}else if(data.status==0){
					window.location.href=data._url;
				}
			});

		});

	});
</script>
</head>
<body>
	<div class="text-center"  style="background:url(/Public/images/top.jpg);height:200px;">


	</div>

	<div class="well">
		<legend>登录</legend>
		<form class="form-horizontal" method="post" onsubmit="return false;">
			<div class="form-group is-empty">
				<!--
				<label for="inputPhone" class="col-md-2 control-label">手机</label>
				-->
				<div class="col-md-12">
					<input type="text" id="phone" name="phone" class="form-control" id="inputPhone" placeholder="手机号码">
				</div>
				<span id="phoneText" class="material-input text-danger"></span>
			</div>
			<div class="form-group is-empty">
				<!--
				<label for="inputPassword" class="col-md-2 control-label">密码</label>
				-->
				<div class="col-md-12">
					<input type="password" id="password" name="password" class="form-control" id="inputPassword" placeholder="密码">
				</div>
				<span id="passwordText" class="material-input text-danger"></span>
			</div>
			<div class="form-group">
				<div class="col-xs-12 col-xs-offset-3">
				<button type="submit" id="submit_login" class="col-xs-6 btn btn-primary btn-raised">登 录</button>
			</div>
			</div>
		</form>
		
	</div>

</body>
</html>