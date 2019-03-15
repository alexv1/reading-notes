<!DOCTYPE html>
<html lang="en">
<head>
    @include('head-meta')
    <title>我的书架</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/rater-star/css/rater-star.css" />
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
                            <span>{{$year}}已读</span>
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
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-info">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                    <a href="../book/do_list" class="btn btn-info">更多</a>
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        在读({{$do_count}}本)
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="activity-desk">
                                    <div class="album">
                                        @foreach($do_list as $data)
                                            <a href="../book/my_view?bid={{$data->bid}}">
                                                @if(empty($data->pic_url))
                                                    <img src="{{$path}}images/no-image.png" width="80">
                                                @else
                                                    <img src="{{$data->pic_url}}" width="80">
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-success">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                    <a href="../book/wish_list" class="btn btn-success">更多</a>
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        想读({{$wish_count}}本)
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="activity-desk">
                                    <div class="album">
                                        @foreach($wish_list as $data)
                                            <a href="../book/my_view?bid={{$data->bid}}">
                                                @if(empty($data->pic_url))
                                                    <img src="{{$path}}images/no-image.png" width="80">
                                                @else
                                                    <img src="{{$data->pic_url}}" width="80">
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-success">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                    <a href="../book/collect_list" class="btn btn-success">更多</a>
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        已读({{$collect_count}}本)
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="activity-desk">
                                    <div class="album">
                                        @foreach($collect_list as $data)
                                            <a href="../book/my_view?bid={{$data->bid}}">
                                                @if(empty($data->pic_url))
                                                    <img src="{{$path}}images/no-image.png" width="80">
                                                @else
                                                    <img src="{{$data->pic_url}}" width="80">
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
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
                                            <a href="../book/my_view?bid={{$data->bid}}" class="pull-left" target="_blank">
                                                @if(empty($data->pic_url))
                                                    <img src="{{$path}}images/no-image.png" width="70">
                                                @else
                                                    <img src="{{$data->pic_url}}" width="70">
                                                @endif
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

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-8">

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-info">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                    <a href="../note/my_list" class="btn btn-info">更多</a>
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        我的笔记({{$note_count}})
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                @foreach($latest_notes as $data)
                                    <div class="media blog-cmnt">
                                        <a href="../book/my_view?bid={{$data->bid}}" class="pull-left">
                                            @if(empty($data->pic_url))
                                                <img src="{{$path}}images/no-image.png" width="70">
                                            @else
                                                <img src="{{$data->pic_url}}" width="70">
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <a href="../note/my_view?nid={{$data->nid}}">
                                                        {{$data->title}}
                                                </a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$data->create_time}}
                                            </h4>
                                            <p class="mp-less">
                                                {{$data->abstract}}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-info">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                    <a href="../comment/my_list" class="btn btn-info">更多</a>
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        我的评论({{$comment_count}})
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                @for ($i = 0; $i < count($comments); $i++)
                                    <div class="media blog-cmnt">
                                        <a href="../book/my_view?bid={{$comments[$i]->bid}}" class="pull-left">
                                            @if(empty($comments[$i]->pic_url))
                                                <img src="{{$path}}images/no-image.png" width="70">
                                            @else
                                                <img src="{{$comments[$i]->pic_url}}" width="70">
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <a href="../book/my_view?bid={{$comments[$i]->bid}}">{{$comments[$i]->bname}}</a>  {{$comments[$i]->create_time}}
                                            </h4>
                                            <h4 class="media-heading">
                                                {{$comments[$i]->title}}
                                            </h4>
                                            <h4 class="media-heading">
                                                <div id="star_{{$i}}"></div>
                                                <input type="hidden" id="star_val_{{$i}}" value="{{$comments[$i]->star}}">
                                            </h4>
                                            <p class="mp-less">
                                                {{$comments[$i]->content}}
                                            </p>
                                        </div>
                                    </div>
                                @endfor
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
<script type="text/javascript" src="{{$path}}js/rater-star/js/rater-star.js"></script>
<script>
    $(document).ready(function() {
        $('#nav-my').addClass("active");
        $('#menu-book-my').addClass("active");
        var count = {{count($comments)}};
        for(var j=0; j<count; j++){
            var options_rate = {
                max : 5,
                value : $('#star_val_' + j).val(),
                enabled : false
            }
            $('#star_'+j).rater(options_rate);
        }

    });

</script>
<script type="text/javascript" src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
</body>
</html>
