<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>书籍搜索</title>
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
                <li class="active">书籍搜索 - {{$q}}</li>
                @if($booklist_id != 0)
                    <li class="active">导入书单 -
                        <a href="../booklist/my_view?blid={{$booklist_id}}" target="_blank">{{$import_booklist_name}}</a></li>
                @endif
            </ul>
        </div>

        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <!-- 书 -->
                    <div class="col-sm-4">
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
                                    <input type="hidden" name="q" id="q" value="{{$q}}"/>
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
                                                    </a>&nbsp;&nbsp;&nbsp;{{$read_status[$data->read_status]}}
                                                </h5>
                                                <p>{{$data->author}}</p>
                                                <p>
                                                    {{$data->update_time}}<br>
                                                    <a href="../book/my_view?bid={{$data->bid}}">编辑</a>&nbsp;&nbsp;&nbsp;
                                                    @if($booklist_id > 0)
                                                    <a href="javascript:attachBookToBooklist({{$data->bid}},{{$booklist_id}})">导入书单</a>
                                                    @endif
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


                    <div class="col-sm-4">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        豆瓣
                                    </h4>
                                    <h5 class="pull-right">
                                        @if(!empty($douban_books))
                                        搜索结果：{{count($douban_books)}}
                                        @endif
                                        <!-- {{--搜索结果： {{($douban_books->total== 0 ? 0 : 1)}} - {{count($douban_books->books)}} / {{$douban_books->total}}--}} -->
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="blog-post">
                                @if(!empty($douban_books))
                                    @foreach($douban_books as $data)
                                        <div class="media">
                                            <a href="{{$data->url}}" target="_blank" class="pull-left">
                                                <img src="{{$data->pic}}" width="90">
                                            </a>
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    <a href="{{$data->url}}" target="_blank">{{$data->title}}</a>
                                                </h5>
                                                <p>
                                                    @if(!empty($data->author_name))
                                                      {{$data->author_name}}
                                                    @endif
                                                </p>
                                                <p>
                                                    @if(!empty($data->year))
                                                    {{$data->year}}<br>
                                                    @endif
                                                    @if($data->type == 'b')
                                                    <a href="javascript:importDoubanBook({{$data->id}},{{$booklist_id}},'{{$data->url}}', '{{$data->title}}', '{{$data->pic}}', '{{$data->author_name}}')">导入</a>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-sm-4">
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
<script type="text/javascript" src="{{$path}}js/rater-star/js/rater-star.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-booklist-my').addClass("active");
        showPageNumber({{$page}},{{$page_count}});
        $('#q').val('{{$q}}');

        var books = new Array();
        @if(!empty($douban_books->books))
            @foreach($douban_books->books as $data)
            books.push({{$data->id}});
            @endforeach

            for(var j=0; j<books.length; j++){
                var id = books[j];
                var options_rate_view = {
                    max : 5,
                    value : $('#star_val_' + id).val()/2,
                    enabled : false
                };
                $('#star_view_'+id).rater(options_rate_view);
            }
        @endif

    });

    function importDoubanBook(doubanId, booklist_id, url, title, pic, author){

        $.post(
            "../book/importDoubanBook",
            {
                douban_id: doubanId,
                booklist_id: booklist_id,
                url: url,
                title: title,
                pic: pic,
                author: author
            },
            function (data) {
                var tmp = JSON.parse(data);
                if (tmp.code == 0) {
                    location.href = '../book/my_view?bid=' + tmp.bid;
                }
            });

    }

    function attachBookToBooklist(bookId, booklist_id){

        $.post(
                "../booklist/attachBook",
                {
                    book_id: bookId,
                    booklist_id: booklist_id
                },
                function (data) {
                    var tmp = JSON.parse(data);
                    if (tmp.code == 0) {
                        location.href = '../book/my_view?bid=' + bookId;
                    }
                });

    }

</script>
</body>
</html>
