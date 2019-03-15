<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>我的书单</title>
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
                <li class="active">书籍搜索 - {{$keyword}}</li>
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
                                        书
                                    </h4>
                                    <h5 class="pull-right">
                                        搜索结果： {{($page-1)*$page_size + (count($books)== 0 ? 0 : 1)}} - {{($page-1)*$page_size + count($books)}} / {{$list_count}}
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form id="pageForm" action="../search/book" method="post">
                                    <input type="hidden" name="p" id="p" value="{{$page}}"/>
                                    <input type="hidden" name="keyword" id="keyword" value="{{$keyword}}"/>
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

                    <div class="col-sm-5">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        搜索结果 - 书单
                                    </h4>
                                    <div class="pull-right">
                                    </div>
                                </div>
                            </header>
                            <div class="panel-body">
                                @for ($i = 0; $i < count($booklists); $i++)
                                    <div class="media blog-cmnt">
                                        <a href="../booklist/my_view?blid={{$booklists[$i]->id}}" class="pull-left">

                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <a href="../booklist/my_view?blid={{$booklists[$i]->id}}" style="color: #65CEA7;">{{$booklists[$i]->name}}</a>
                                                {{$booklists[$i]->create_time}}
                                            </h4>
                                            <p class="mp-less">
                                                {{subStr($booklists[$i]->intro, 100)}}
                                            </p>
                                            <p class="mp-less">
                                                <a href="{{$booklists[$i]->url}}" style="color: #428bca;" target="_blank">外链</a>
                                            </p>
                                        </div>
                                    </div>
                                @endfor
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
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-booklist-my').addClass("active");
        showPageNumber({{$page}},{{$page_count}});
        $('#keyword').val('{{$keyword}}');
    });

</script>
</body>
</html>
