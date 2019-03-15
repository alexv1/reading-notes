<div class="left-side sticky-left-side">

    <!--logo and iconic logo start-->
    <div class="logo">
        <a href="index"><img src="{{$path}}images/logo.png" alt=""></a>
    </div>

    <div class="logo-icon text-center">
        <a href="index"><img src="{{$path}}images/logo_icon.png" alt=""></a>
    </div>
    <!--logo and iconic logo end-->
    <div class="left-side-inner">
        <!-- visible to small devices only -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">
            <div class="media logged-user">
                <img alt="" src="{{$path}}images/photos/user-avatar.png" class="media-object">
                <div class="media-body">
                    <h4><a href="#">{{getUserName()}}</a></h4>
                    <span>"你好！欢迎使用"</span>
                </div>
            </div>
            <h5 class="left-nav-title">账号信息</h5>
            <ul class="nav nav-pills nav-stacked custom-nav">
                <!--
                <li><a href="#"><i class="fa fa-user"></i> <span></span></a></li>
                <li><a href="#"><i class="fa fa-cog"></i> <span>账号设定</span></a></li>
                -->
                <li><a href="../logout"><i class="fa fa-sign-out"></i> <span>安全退出</span></a></li>
            </ul>
        </div>

        <!--sidebar nav start-->
        <ul class="nav nav-pills nav-stacked custom-nav">
            <li id="nav-home"><a href="../dashboard/index"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            {{--<li class="menu-list" id="nav-book"><a href=""><i class="fa fa-laptop"></i> <span>书架</span></a>--}}
                {{--<ul class="sub-menu-list">--}}
                    {{--<li id="menu-book-do"><a href="../book/interest/do">在读 <span id="do_menu"></span></a></li>--}}
                    {{--<li id="menu-book-wish"><a href="../book/interest/wish">想读 <span id="wish_menu"></span></a></li>--}}
                    {{--<li id="menu-book-collect"><a href="../book/interest/collect">读过 <span id="collect_menu"></span></a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li class="menu-list" id="nav-my"><a href=""><i class="fa fa-cogs"></i> <span>我的</span></a>
                <ul class="sub-menu-list">
                    <li id="menu-book-my"><a href="../book/my_list">我的书架</a></li>
                    <li id="menu-comment-my"><a href="../comment/my_list">我的书评</a></li>
                    <li id="menu-note-my"><a href="../note/my_list">我的笔记</a></li>
                    <li id="menu-booklist-my"><a href="../booklist/my_list">我的书单</a></li>
                </ul>
            </li>
            <li class="menu-list" id="nav-stats"><a href=""><i class="fa fa-tasks"></i> <span>阅读统计</span></a>
                <ul class="sub-menu-list">
                    <li id="menu-stats-book"><a href="../stats/read_list">阅历</a></li>
                </ul>
            </li>

            {{--<li class="menu-list" id="nav-setting"><a href=""><i class="fa fa-th-list"></i> <span>基础设置</span></a>--}}
                {{--<ul class="sub-menu-list">--}}
                    {{--<li id="menu-category"><a href="../user/list">书籍分类</a></li>--}}
                    {{--<li id="menu-book-all"><a href="../book/all_list">书籍管理</a></li>--}}
                    {{--<li id="menu-series"><a href="../series/list">丛书管理</a></li>--}}
                    {{--<li id="menu-tag"><a href="../tag/list">标签管理</a></li>--}}
                    {{--<li id="menu-booklist"><a href="../booklist/list">书单管理</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
        </ul>
        <!--sidebar nav end-->
    </div>
</div>
<!--sidebar nav end-->
</div>
</div>