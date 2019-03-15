<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>我的笔记</title>
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
                    <a href="../book/my_list">我的书架</a>
                </li>
                <li class="active">我的笔记</li>
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
                                    我的笔记
                                </h4>
                                <h5 class="pull-right">
                                    {{($page-1)*$page_size + (count($notes)== 0 ? 0 : 1)}} - {{($page-1)*$page_size + count($notes)}} / {{$note_count}}
                                </h5>
                            </div>
                        </header>
                        <div class="panel-body">
                            <form id="pageForm" action="../note/my_list" method="post">
                                <input type="hidden" name="p" id="p" value="{{$page}}"/>
                            </form>
                            @foreach($notes as $data)
                                <div class="media blog-cmnt">
                                    <a href="../book/my_view?bid={{$data->bid}}" class="pull-left">
                                        <img alt="" src="{{$data->pic_url}}" width="70">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="../note/my_view?nid={{$data->nid}}">
                                                {{$data->title}}
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{$data->create_time}}
                                        </h4>
                                        <div></div>
                                        <p class="mp-less">
                                            {{$data->abstract}}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
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
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-note-my').addClass("active");
        showPageNumber({{$page}},{{$page_count}});
    });

</script>
</body>
</html>
