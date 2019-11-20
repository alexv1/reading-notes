<!DOCTYPE html>
<html lang="en">
<head>
    @include('head-meta')
    <title>书单 - {{$booklist->name}}的书</title>
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
                书单 - {{$booklist->name}}的书 ({{$book_count}})
            </h3>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <header class="panel-heading">
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        书单详情
                                    </h4>
                                    <h4 class="pull-right">
                                        <a href="{{$booklist->url}}">传送门</a>
                                        <a href="../booklist/edit?blid={{$booklist->id}}">修改</a>
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="blog-post">
                                    <div class="media blog-cmnt">
                                        <div class="media-body">
                                            <p class="mp-less">
                                                  推荐人：{{$booklist->creator}}<br>
                                                创建时间：{{$booklist->create_time}}<br>
                                                <a href="{{$booklist->url}}" style="color: #428bca;" target="_blank">外链</a>
                                            </p>
                                            <p class="mp-less">
                                                {{$booklist->reason}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-success">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        同作者书单
                                    </h4>
                                    <h5 class="pull-right">
                                         {{$same_creator_count}}
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                @foreach($same_creator_list as $data)
                                    <div class="media blog-cmnt">
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <a href="../booklist/my_view?blid={{$data->id}}" style="color: #65CEA7;">{{$data->name}}</a>  {{$data->create_time}}
                                            </h4>
                                            <p class="mp-less">
                                                {{wrapTextToHtml(mb_substr($data->intro,0, 40))}}<br>...
                                            </p>
                                            <p class="mp-less">
                                                <a href="{{$data->url}}" style="color: #428bca;" target="_blank">外链</a>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        入库书目  {{count($books)}}， 想读{{$want_count}}，在读{{$do_count}}，已读{{$collect_count}}
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="blog-post">
                                    @foreach($books as $data)
                                        <div class="media">
                                            <a href="../book/my_view?bid={{$data->bid}}" class="pull-left">
                                                <img src="{{$data->pic_url}}" width="90">
                                            </a>
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    <a href="../book/my_view?bid={{$data->bid}}" target="_blank">{{$data->bname}}
                                                    @if(!empty($data->subtitle))
                                                        ：{{$data->subtitle}}
                                                    @endif
                                                    </a>&nbsp;&nbsp;&nbsp;{{$read_status[$data->read_status]}}
                                                </h5>
                                                <p>{{$data->author}}</p>
                                                <p>
                                                    标签：{{tagToShortHtml($data->tags)}}<br>{{$data->update_time}}<br>
                                                    <a href="../booklist/detachBook?booklist_id={{$booklist->id}}&book_id={{$data->bid}}">移除</a>
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <header class="panel-heading">
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        书目 {{$booklist->book_count}}
                                    </h4>
                                    <h4 class="pull-right">
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="blog-post">
                                    @foreach(explode('<br>',wrapTextToHtml($booklist->intro)) as $data)
                                        @if(!empty($data))
                                            <div class="media blog-cmnt">
                                                <div class="media-body">
                                                    <p class="mp-less">
                                                        {{$data}}
                                                    </p>
                                                    <p class="mp-less">
                                                        <a href="../search/book?q={{filterTitle($data)}}&blid={{$booklist->id}}" style="color: #428bca;">检索书库+豆瓣</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- @if(count($books)>0)
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                            @endif -->
                                        @endif
                                    @endforeach
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
<script>
    $(document).ready(function() {
        $('#nav-my').addClass("active");
        $('#menu-booklist-my').addClass("active");
    });

</script>
</body>
</html>
