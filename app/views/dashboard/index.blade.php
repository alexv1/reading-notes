<!DOCTYPE html>
<html lang="en">
<head>
    @include('head-meta')
    <title>我的书架</title>
    @include('head-js')
</head>

<body class="sticky-header">

<section>

    @include('left-side')
    <!-- left side end-->
    <div class="main-content" >
        @include('header')

        <!-- page heading start-->
        <div class="page-heading">
            <h3 style="color: #65CEA7;">
                我的书架
            </h3>
            <ul class="breadcrumb">
                <li>{{date('Y年m月d日',time())}}，{{$rest_days[date("w", time())]}}</li>
            </ul>
            <div class="state-info">
                <section class="panel">
                    <div class="panel-body">
                        <div class="summary">
                            <span>累计已读</span>
                            <h3 class="green-txt">{{$count_array[1]}}</h3>
                        </div>
                        <div id="expense" class="chart-bar"></div>
                    </div>
                </section>
                <section class="panel">
                    <div class="panel-body">
                        <div class="summary">
                            <span>2016已读</span>
                            <h3 class="red-txt">{{$count_array[0]}}</h3>
                        </div>
                        <div id="income" class="chart-bar"></div>
                    </div>
                </section>
                <section class="panel">
                    <div class="panel-body">
                        <div class="summary">
                            <span>本月已读</span>
                            <h3 class="green-txt">{{$count_array[3]}}</h3>
                        </div>
                        <div id="expense" class="chart-bar"></div>
                    </div>
                </section>
                <section class="panel">
                    <div class="panel-body">
                        <div class="summary">
                            <span>本月笔记</span>
                            <h3 class="green-txt">{{$count_array[4]}}</h3>
                        </div>
                        <div id="expense" class="chart-bar"></div>
                    </div>
                </section>
                <section class="panel">
                    <div class="panel-body">
                        <div class="summary">
                            <span>本月书评</span>
                            <h3 class="green-txt">{{$count_array[5]}}</h3>
                        </div>
                        <div id="expense" class="chart-bar"></div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-info">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="/book/interest/do" class="btn btn-info">更多</a>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    在读书目
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <ul class="activity-list">
                                <li>
                                    <div class="activity-desk">
                                        <div class="album">
                                            @foreach($do_list as $data)
                                            <a href="#">
                                                <img width="60" src="{{$data->pic_url}}">
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-danger">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    最新书目
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <div class="blog-post">
                                @foreach($latest_books as $data)
                                    <div class="media">
                                        <a href="../book/view?bid={{$data->bid}}" class="pull-left" target="_blank">
                                            <img src="{{$data->pic_url}}" class=" ">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="media-heading">{{$data->bname}}</h5>
                                            <p>
                                                {{$data->author}}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-right"><a href="../book/all_list" target="_blank">更多</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-success">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="/book/interest/wish" class="btn btn-success">更多</a>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    想读
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <ul class="activity-list">
                                <li>
                                    <div class="activity-desk">
                                        <div class="album">
                                            @foreach($wish_list as $data)
                                                <a href="#">
                                                    <img width="60" src="{{$data->pic_url}}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-info">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="/note/my_list" class="btn btn-info">更多</a>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    我的笔记
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="clearfix">
                                        <div id="main-chart-legend" class="pull-right">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-info">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="/comment/my_list" class="btn btn-info">更多</a>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    我的评论
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="clearfix">
                                        <div id="main-chart-legend" class="pull-right">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-warning">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    读书统计
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="clearfix">
                                        <div id="main-chart-legend" class="pull-right">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row blog">


            </div>

        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        @include('footer')
        <!--footer section end-->


    </div>
    <!-- main content end-->
</section>

@include('foot-js')
<script>
    $(document).ready(function() {
        $('#nav-home').addClass("active");

    });

</script>
<script type="text/javascript" src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
</body>
</html>
