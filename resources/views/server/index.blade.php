<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>商城后台</title>
    <link href="/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="/themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="/themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
    <link href="/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
    <!--[if IE]>
    <link href="/themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="/juijs/speedup.js" type="text/javascript"></script>
    <script src="/juijs/jquery-1.11.3.min.js" type="text/javascript"></script><![endif]-->
    <!--[if gte IE 9]><!-->
    <script src="/juijs/jquery-2.1.4.min.js" type="text/javascript"></script><!--<![endif]-->
    <script src="/juijs/jquery.cookie.js" type="text/javascript"></script>
    <script src="/juijs/jquery.validate.js" type="text/javascript"></script>
    <script src="/xheditor/xheditor-1.2.2.min.js" type="text/javascript"></script>
    <script src="/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
    <script src="/uploadify/scripts/jquery.uploadify.js" type="text/javascript"></script>

    <script src="/juijs/dwz.core.js" type="text/javascript"></script>
    <script src="/juijs/dwz.util.date.js" type="text/javascript"></script>
    <script src="/juijs/dwz.validate.method.js" type="text/javascript"></script>
    <script src="/juijs/dwz.barDrag.js" type="text/javascript"></script>
    <script src="/juijs/dwz.drag.js" type="text/javascript"></script>
    <script src="/juijs/dwz.tree.js" type="text/javascript"></script>
    <script src="/juijs/dwz.accordion.js" type="text/javascript"></script>
    <script src="/juijs/dwz.ui.js" type="text/javascript"></script>
    <script src="/juijs/dwz.theme.js" type="text/javascript"></script>
    <script src="/juijs/dwz.switchEnv.js" type="text/javascript"></script>
    <script src="/juijs/dwz.alertMsg.js" type="text/javascript"></script>
    <script src="/juijs/dwz.contextmenu.js" type="text/javascript"></script>
    <script src="/juijs/dwz.navTab.js" type="text/javascript"></script>
    <script src="/juijs/dwz.tab.js" type="text/javascript"></script>
    <script src="/juijs/dwz.resize.js" type="text/javascript"></script>
    <script src="/juijs/dwz.dialog.js" type="text/javascript"></script>
    <script src="/juijs/dwz.dialogDrag.js" type="text/javascript"></script>
    <script src="/juijs/dwz.sortDrag.js" type="text/javascript"></script>
    <script src="/juijs/dwz.cssTable.js" type="text/javascript"></script>
    <script src="/juijs/dwz.stable.js" type="text/javascript"></script>
    <script src="/juijs/dwz.taskBar.js" type="text/javascript"></script>
    <script src="/juijs/dwz.ajax.js" type="text/javascript"></script>
    <script src="/juijs/dwz.pagination.js" type="text/javascript"></script>
    <script src="/juijs/dwz.database.js" type="text/javascript"></script>
    <script src="/juijs/dwz.datepicker.js" type="text/javascript"></script>
    <script src="/juijs/dwz.effects.js" type="text/javascript"></script>
    <script src="/juijs/dwz.panel.js" type="text/javascript"></script>
    <script src="/juijs/dwz.checkbox.js" type="text/javascript"></script>
    <script src="/juijs/dwz.history.js" type="text/javascript"></script>
    <script src="/juijs/dwz.combox.js" type="text/javascript"></script>
    <script src="/juijs/dwz.file.js" type="text/javascript"></script>
    <script src="/juijs/dwz.print.js" type="text/javascript"></script>
    <script src="/juijs/dwz.regional.zh.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            DWZ.init("/dwz.frag.xml", {
                loginUrl: "login_dialog.html", loginTitle: "登录",	// 弹出登录对话框
                //loginUrl:"login.html",	// 跳到登录页面
                statusCode: {ok: 200, error: 300, timeout: 301}, //【可选】
                pageInfo: {
                    pageNum: "pageNum",
                    numPerPage: "numPerPage",
                    orderField: "orderField",
                    orderDirection: "orderDirection"
                }, //【可选】
                keys: {statusCode: "statusCode", message: "message"}, //【可选】
                ui: {hideMode: 'offsets'}, //【可选】hideMode:navTab组件切换的隐藏方式，支持的值有’display’，’offsets’负数偏移位置的值，默认值为’display’
                debug: false,	// 调试模式 【true|false】
                callback: function () {
                    initEnv();
                    $("#themeList").theme({themeBase: "themes"}); // themeBase 相对于index页面的主题base路径
                }
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
</head>

<body>
<div id="layout">
    <div id="header">
        <div class="headerNav">
            <ul class="nav">
                <li><a href="http://www.shashop.com" target="_blank">前台</a></li>
                <li><a href="javascript:;">管理员:{{$data['email']}}</a></li>
                <li><a href="javascript:;">微信号：{{$data['weixin']}}</a></li>
                <li><a href="/server/quit">退出</a></li>
            </ul>
        </div>

        <!-- navMenu -->

    </div>
    <div id="leftside">
        <div id="sidebar">
            <div class="toggleCollapse"><h2>后台管理</h2>

                <div>收缩</div>
            </div>
            <div class="accordion" fillSpace="sidebar">
                <div class="accordionHeader">
                    <h2><span>Folder</span>导航</h2>
                </div>
                <div class="accordionContent">
                    <ul class="tree treeFolder">
                        <li><a>商品管理</a>
                            <ul>
                                <li><a href="/server/classify" target="navTab" rel="classify">商品分类</a></li>
                                <li><a href="/server/commodity" target="navTab" rel="commodity">商品列表</a></li>
                            </ul>
                        </li>

                        <li><a>订单管理</a>
                            <ul>
                                <li><a href="/server/order" target="navTab" rel="order">订单列表</a></li>
                            </ul>
                        </li>

                        <li><a>渠道商管理</a>
                            <ul>
                                @if($data['grade'] == 0)
                                    <li><a href="/server/user" target="navTab" rel="user">注册渠道商</a></li>
                                @endif
                                <li><a href="/server/channel" target="navTab" rel="channel">个体渠道商</a></li>
                            </ul>
                        </li>
                        <li><a>杂项管理</a>
                            <ul>
                                @if($data['grade'] == 0)
                                    <li><a href="/server/admin" target="navTab" rel="admin">管理员管理</a></li>
                                    <li><a href="/server/banner" target="navTab" rel="banner">首页轮播图</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="container">
        <div id="navTab" class="tabsPage">
            <div class="tabsPageHeader">
                <div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
                    <ul class="navTab-tab">
                        <li tabid="main" class="main"><a href="javascript:;"><span><span
                                            class="home_icon">介绍</span></span></a></li>
                    </ul>
                </div>
            </div>
            <div class="navTab-panel tabsPageContent layoutBox">
                <div class="page unitBox">
                    <div class="">商城后台管</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="footer">Copyright &copy; 2017 <a href="demo_page2.html" target="dialog">商城团队</a> 沪ICP备15053290号-2</div>
</body>
</html>