<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>我的书评</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/rater-star/css/rater-star.css" />
</head>

<body class="sticky-header">

<section>
    @include('left-side')
    <div class="main-content" >
        @include('header')
        <div class="page-heading">
            <ul class="breadcrumb">
                <li>
                    <a href="../book/my_list">我的书架</a>
                </li>
                <li class="active">我的书评</li>
            </ul>
        </div>

        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                <div class="col-sm-7">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    我的书评
                                </h4>
                                <h5 class="pull-right">
                                    {{($page-1)*$page_size + (count($comments)== 0 ? 0 : 1)}} - {{($page-1)*$page_size + count($comments)}} / {{$comment_count}}
                                </h5>
                            </div>
                        </header>
                        <div class="panel-body">
                            <form id="pageForm" action="../comment/my_list" method="post">
                                <input type="hidden" name="p" id="p" value="{{$page}}"/>
                            </form>
                            @for ($i = 0; $i < count($comments); $i++)
                                <div class="media blog-cmnt">
                                    <a href="../book/my_view?bid={{$comments[$i]->bid}}" class="pull-left">
                                        <img src="{{$comments[$i]->pic_url}}" width="70">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="../book/my_view?bid={{$comments[$i]->bid}}">{{$comments[$i]->bname}}</a>
                                            &nbsp;&nbsp;&nbsp; {{$comments[$i]->create_time}}
                                            &nbsp;&nbsp;&nbsp; {{$read_status[$comments[$i]->read_status]}}
                                        </h4>
                                        <p>
                                            标签：{{tagToHtml($comments[$i]->tags)}}
                                        </p>
                                        <h4 class="media-heading">
                                            书评：{{$comments[$i]->title}}
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
                        <div class="panel-footer text-center">
                            <ul class="pagination">
                                <li><a href="javascript:goPage(1,{{$page_count}})">首页</a></li>
                                <li id="last_page"><a href="javascript:goPage({{$page_count}},{{$page_count}})">末页</a></li>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
            </div>
        </div>


        @include('footer')
    </div>
</section>

@include('foot-js')
<script type="text/javascript" src="{{$path}}js/rater-star/js/rater-star.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-comment-my').addClass("active");
        showPageNumber({{$page}},{{$page_count}});

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
</body>
</html>
