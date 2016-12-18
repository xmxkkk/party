<?php if (!defined('THINK_PATH')) exit();?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>模型管理|OneThink管理平台</title>
    <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/default_color.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <li class=""><a href="/admin.php?s=/Index/index.html">首页</a></li><li class=""><a href="/admin.php?s=/Article/index.html">内容</a></li><li class=""><a href="/admin.php?s=/User/index.html">用户</a></li><li class="current"><a href="/admin.php?s=/Config/group.html">系统</a></li><li class=""><a href="/admin.php?s=/Addons/index.html">扩展</a></li>        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="admin">admin</em></li>
                <li><a href="/admin.php?s=/User/updatePassword.html">修改密码</a></li>
                <li><a href="/admin.php?s=/User/updateNickname.html">修改昵称</a></li>
                <li><a href="/admin.php?s=/Public/logout.html">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                                <!-- 子导航 -->
                    <h3><i class="icon icon-unfold"></i>系统设置</h3>                        <ul class="side-sub-menu">
                            <li>
                                    <a class="item" href="/admin.php?s=/Config/group.html">网站设置</a>
                                </li><li>
                                    <a class="item" href="/admin.php?s=/Category/index.html">分类管理</a>
                                </li><li>
                                    <a class="item" href="/admin.php?s=/Model/index.html">模型管理</a>
                                </li><li>
                                    <a class="item" href="/admin.php?s=/Config/index.html">配置管理</a>
                                </li><li>
                                    <a class="item" href="/admin.php?s=/Menu/index.html">菜单管理</a>
                                </li><li>
                                    <a class="item" href="/admin.php?s=/Channel/index.html">导航管理</a>
                                </li>                        </ul>                    <!-- /子导航 --><!-- 子导航 -->
                    <h3><i class="icon icon-unfold"></i>数据备份</h3>                        <ul class="side-sub-menu">
                            <li>
                                    <a class="item" href="/admin.php?s=/Database/index/type/export.html">备份数据库</a>
                                </li><li>
                                    <a class="item" href="/admin.php?s=/Database/index/type/import.html">还原数据库</a>
                                </li>                        </ul>                    <!-- /子导航 -->            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
                        <!-- nav -->
            

            
    <!-- 标题栏 -->
    <div class="main-title">
        <h2>模型列表</h2>

    </div>
    <div class="tools">
        <a class="btn" href="/admin.php?s=/Model/add.html">新 增</a>
        <button class="btn ajax-post" target-form="ids" url="/admin.php?s=/Model/setStatus/status/1.html">启 用</button>
        <button class="btn ajax-post" target-form="ids" url="/admin.php?s=/Model/setStatus/status/0.html">禁 用</button>
        <a class="btn" href="/admin.php?s=/Model/generate.html">生 成</a>
    </div>

    <!-- 数据列表 -->
    <div class="data-table">
        <div class="data-table table-striped">
<table class="">
    <thead>
        <tr>
        <th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
        <th class="">编号</th>
        <th class="">标识</th>
        <th class="">名称</th>
        <th class="">创建时间</th>
        <th class="">状态</th>
        <th class="">操作</th>
        </tr>
    </thead>
    <tbody>
    <tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="4" /></td>
            <td>4 </td>
            <td>group</td>
            <td><a data-id="4" href="/admin.php?s=/model/edit/id/4.html">小组</a></td>
            <td><span>2016-03-24 11:41</span></td>
            <td>正常</td>
            <td>
                <a href="/admin.php?s=/think/lists/model/group.html">数据</a>
                <a href="/admin.php?s=/model/setstatus/ids/4/status/0.html" class="ajax-get">禁用</a>
                <a href="/admin.php?s=/model/edit/id/4.html">编辑</a>
                <a href="/admin.php?s=/model/del/ids/4.html" class="confirm ajax-get">删除</a>
            </td>
        </tr><tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="3" /></td>
            <td>3 </td>
            <td>download</td>
            <td><a data-id="3" href="/admin.php?s=/model/edit/id/3.html">下载</a></td>
            <td><span>2013-11-08 14:14</span></td>
            <td>正常</td>
            <td>
                <a href="/admin.php?s=/think/lists/model/download.html">数据</a>
                <a href="/admin.php?s=/model/setstatus/ids/3/status/0.html" class="ajax-get">禁用</a>
                <a href="/admin.php?s=/model/edit/id/3.html">编辑</a>
                <a href="/admin.php?s=/model/del/ids/3.html" class="confirm ajax-get">删除</a>
            </td>
        </tr><tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="2" /></td>
            <td>2 </td>
            <td>article</td>
            <td><a data-id="2" href="/admin.php?s=/model/edit/id/2.html">文章</a></td>
            <td><span>2013-11-08 14:14</span></td>
            <td>正常</td>
            <td>
                <a href="/admin.php?s=/think/lists/model/article.html">数据</a>
                <a href="/admin.php?s=/model/setstatus/ids/2/status/0.html" class="ajax-get">禁用</a>
                <a href="/admin.php?s=/model/edit/id/2.html">编辑</a>
                <a href="/admin.php?s=/model/del/ids/2.html" class="confirm ajax-get">删除</a>
            </td>
        </tr><tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="1" /></td>
            <td>1 </td>
            <td>document</td>
            <td><a data-id="1" href="/admin.php?s=/model/edit/id/1.html">基础文档</a></td>
            <td><span>2013-11-08 14:13</span></td>
            <td>正常</td>
            <td>
                <a href="/admin.php?s=/think/lists/model/document.html">数据</a>
                <a href="/admin.php?s=/model/setstatus/ids/1/status/0.html" class="ajax-get">禁用</a>
                <a href="/admin.php?s=/model/edit/id/1.html">编辑</a>
                <a href="/admin.php?s=/model/del/ids/1.html" class="confirm ajax-get">删除</a>
            </td>
        </tr>           </tbody>
    </table>

        </div>
    </div>
    <div class="page">
        <div>    </div>    </div>

        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用<a href="http://www.onethink.cn" target="_blank">OneThink</a>管理平台</div>
                <div class="fr">V1.1.141101</div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "", //当前网站地址
            "APP"    : "/admin.php?s=", //当前项目地址
            "PUBLIC" : "/Public", //项目公共目录地址
            "DEEP"   : "/", //PATHINFO分割符
            "MODEL"  : ["3", "", "html"],
            "VAR"    : ["m", "c", "a"]
        }
    })();
    </script>
    <script type="text/javascript" src="/Public/static/think.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/common.js"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

            /* 表单获取焦点变色 */
            $("form").on("focus", "input", function(){
                $(this).addClass('focus');
            }).on("blur","input",function(){
                        $(this).removeClass('focus');
                    });
            $("form").on("focus", "textarea", function(){
                $(this).closest('label').addClass('focus');
            }).on("blur","textarea",function(){
                $(this).closest('label').removeClass('focus');
            });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
    
    <script src="/Public/static/thinkbox/jquery.thinkbox.js"></script>
    <script type="text/javascript">
    $(function(){
        $("#search").click(function(){
            var url = $(this).attr('url');
            var status = $('select[name=status]').val();
            var search = $('input[name=search]').val();
            if(status != ''){
                url += '/status/' + status;
            }
            if(search != ''){
                url += '/search/' + search;
            }
            window.location.href = url;
        });
})
</script>

</body>
</html>