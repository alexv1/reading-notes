<!DOCTYPE html>
<html>
<head>
    <@include('head-meta')
    <title>书籍详情</title>
    @include('head-js')
</head>

<body class="sticky-header">

<section>
    @include('left-side')
    <div class="main-content" >
        @include('header')
        <div class="page-heading">
            <ul class="breadcrumb">
                <li>
                    <a href="../dashboard/index">首页</a>
                </li>
                <li>
                    <a id="breadcrumb_nav" href="">书籍列表</a>
                </li>
                <li class="active">书籍详情</li>
            </ul>
        </div>

        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a id="panel_nav" href="" class="btn btn-success btn-sm">书籍列表</a>
                                <a href="/edit_view?bid={{$book->bid}}" class="btn btn-success btn-sm">编辑</a>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-danger btn-sm dropdown-toggle" type="button">筛选<span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="javascript:changeReadState('{{$book->bid}}',0)">想读</a></li>
                                        <li><a href="javascript:changeReadState('{{$book->bid}}',1)">在读</a></li>
                                        <li><a href="javascript:changeReadState('{{$book->bid}}',2)">已读</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    {{$book->bname}} {{$read_status[$book->read_status]}}
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td width="12%" class="td-name">书名</td>
                                        <td width="21%">{{$book->bname}}</td>
                                        <td width="12%" class="td-name">作者</td>
                                        <td width="21%">{{$book->author}}</td>
                                        <td width="12%" class="td-name">页数</td>
                                        <td width="21%">{{$book->pages}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">出版社</td>
                                        <td>{{$book->publisher}}</td>
                                        <td class="td-name">出版时间</td>
                                        <td>{{$book->publish_day}}</td>
                                        <td class="td-name">ISBN</td>
                                        <td>{{$book->isbn13}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">豆瓣链接</td>
                                        <td>{{$book->dou_id}}</td>
                                        <td class="td-name">豆瓣评分</td>
                                        <td>{{$book->dou_rate}}</td>
                                        <td class="td-name">豆瓣售价</td>
                                        <td>{{$book->dou_price}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">作者简介</td>
                                        <td colspan="5">{{$book->dou_author_intro}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">内容简介</td>
                                        <td colspan="5">{{$book->dou_summary}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">目录</td>
                                        <td colspan="5">{{$book->dou_catalog}}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="../note/my_list" class="btn btn-success btn-sm">更多笔记</a>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    我的笔记
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <div class="media blog-cmnt">
                                <a href="javascript:;" class="pull-left">
                                    <img alt="" src="{{$path}}images/blog/blog-avatar-2.jpg" class="media-object">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="#">jones</a>
                                    </h4>
                                    <div class="bl-status">
                                        <span class="pull-left">About 10 Min ago</span>
                                        <a href="#" class="pull-right reply">Reply</a>
                                    </div>
                                    <p class="mp-less">
                                        Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>


        @include('footer')
    </div>
</section>

@include('foot-js')
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        var menu = store.get('resume_menu');
        $('#nav-resume').addClass("nav-active");
        $('#' + menu).addClass("active");
        updateResumeMenu();
        updateBookCount();
    });

    function changeReadState(book_id, status){
        if(confirm("确定更新读书状态吗？")){
            $.post(
                "../book/changeReadState",
                {
                    bid: book_id,
                    status: status
                },
                function (data) {
                    var tmp = JSON.parse(data);
                    if (tmp.code == 0) {
                        location.href = '../book/view?bid='+book_id;
                    }
                });
        }
    }

</script>
</body>
</html>
