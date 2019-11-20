<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>图书标签</title>
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
                <li class="active">图书标签 - {{$q}}</li>
            </ul>
        </div>

        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <!-- 书 -->
                    <div class="col-sm-7">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">

                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        图书标签
                                    </h4>
                                    <h5 class="pull-right">
                                        <a href="javascript:tagPageSubmit(0)">书名排序</a>
                                        <a href="javascript:tagPageSubmit(1)">时间降序</a>
                                        <a href="javascript:tagPageSubmit(1)">时间升序</a>
                                        搜索结果： {{($page-1)*$page_size + (count($books)== 0 ? 0 : 1)}} - {{($page-1)*$page_size + count($books)}} / {{$list_count}}
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form id="pageForm" action="../search/tag" method="post">
                                    <input type="hidden" name="p" id="p" value="{{$page}}"/>
                                    <input type="hidden" name="q" id="q" value="{{$q}}"/>
                                    <input type="hidden" name="order" id="order" value="{{$order}}"/>
                                </form>
                                <div class="blog-post">
                                    @foreach($books as $data)
                                        <div class="media">
                                            <a href="../book/my_view?bid={{$data->bid}}" class="pull-left">
                                                <img src="{{$data->pic_url}}" width="90">
                                            </a>
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    <a href="../book/my_view?bid={{$data->bid}}">{{$data->bname}}
                                                        @if(!empty($data->subtitle))
                                                            ：{{$data->subtitle}}
                                                        @endif
                                                    </a>
                                                </h5>
                                                <p>
                                                    {{tagToHtml($data->tags)}}
                                                </p>
                                                <p>{{$data->author}}</p>
                                                <p>
                                                    {{$data->update_time}}<br>
                                                    <a href="../book/my_view?bid={{$data->bid}}">编辑</a>
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
        $('#menu-booklist-my').addClass("active");
        showPageNumber({{$page}},{{$page_count}});

    });

</script>
</body>
</html>
