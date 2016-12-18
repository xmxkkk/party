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
	<title>党员信息</title>
<style type="text/css">
*{font-family: "微软雅黑"}
.xlist_item{border-bottom:1px solid #d2d2d2;line-height:45px;}
.xlist_item_left{color:#666;letter-spacing:5px;}
</style>
</head>
<body>
<div class="text-center"  style="background:url(/Public/images/top.jpg)">
	<img class="img-circle" src="http://f.hiphotos.baidu.com/image/h%3D200/sign=d9c2d17b1a950a7b6a3549c43ad0625c/14ce36d3d539b600af3985faee50352ac75cb78c.jpg" style="width:150px;margin-top:10px;margin-bottom:10px;">
</div>
<div class="well bs-component">
	<div class="container xlist_item">
	<div class="col-xs-4 xlist_item_left" style="">姓名</div>
	<div class="col-xs-8"><b><?php echo ($member["realname"]); ?></b></div>
	</div>
	
	<div class="container xlist_item">
	<div class="col-xs-4 xlist_item_left">状态</div>
	<div class="col-xs-8"><b><?php echo ($member["stage_name"]); ?></b></div>
	</div>

	<div class="container xlist_item">
	<div class="col-xs-4 xlist_item_left">积分</div>
	<div class="col-xs-8"><b><?php echo ($member["score"]); ?></b></div>
	</div>

	<div class="container xlist_item">
	<div class="col-xs-4 xlist_item_left">年龄</div>
	<div class="col-xs-8"><b><?php echo ($member["age"]); ?></b></div>
	</div>
	
	<div class="container xlist_item">
	<div class="col-xs-4 xlist_item_left">性别</div>
	<div class="col-xs-8"><b><?php echo ($member["gender_name"]); ?></b></div>
	</div>

</div>

<div class="container">
	<?php if(empty($_SESSION['uid'])): ?><a class="btn btn-raised btn-info col-xs-12" href="<?php echo U('Index/login');?>">
			登录
		</a>
	<?php else: ?>
		<?php if(($$member['level']==1) == ""): ?><a class="btn btn-raised btn-info col-xs-12" href="<?php echo U('Index/record');?>">
			登记积分
		</a><?php endif; endif; ?>

</div>

</body>
</html>