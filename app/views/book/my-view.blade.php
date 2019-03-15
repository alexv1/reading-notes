<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>图书详情</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/rater-star/css/rater-star.css" />
    <link rel="stylesheet" type="text/css" href="{{$path}}js/jquery-tags-input/jquery.tagsinput.css" />
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
                    <a id="breadcrumb_nav" href="../book/my_list">书籍列表</a>
                </li>
                <li class="active">书籍详情</li>
            </ul>
        </div>
        @include('nav-my')
        <div class="wrapper">

            <div class="row">
                <div class="col-sm-6">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right" style="margin-top: 5px;">
                                <a id="panel_nav" href="../book/my_list" class="btn btn-success btn-sm">书籍列表</a>
                                <a href="../book/edit_view?bid={{$book->bid}}" class="btn btn-success btn-sm">编辑</a>
                                <a href="#myModal" data-toggle="modal" class="btn btn-sm btn-success">写评论</a>
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
                                    <td width="15%" class="td-name">书名</td>
                                    <td width="35%">
                                        {{$book->bname}}
                                        @if(!empty($book->subtitle))
                                            ：{{$book->subtitle}}
                                        @endif
                                    </td>
                                    <td width="15%" class="td-name">作者</td>
                                    <td width="35%">{{$book->author}}</td>
                                </tr>
                                <tr>
                                    <td class="td-name">出版社</td>
                                    <td>{{$book->publisher}}</td>
                                    <td class="td-name">分类</td>
                                    <td>@include('part-category')</td>
                                </tr>
                                <tr>
                                    <td class="td-name">标签</td>
                                    <td colspan="3">{{tagToHtml($book->tags)}}</td>
                                </tr>
                                <tr>
                                    <td class="td-name">外链</td>
                                    <td>
                                        <a href="{{$book->share_url}}" target="_blank">传送门</a>
                                    </td>
                                    <td class="td-name">豆瓣评分</td>
                                    <td>{{$book->dou_rate}}</td>
                                </tr>
                                @if(empty($comment))
                                    <tr>
                                        <td class="td-name">评论</td>
                                        <td colspan="3">暂无</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="td-name">书评标题</td>
                                        <td colspan="3">{{$comment->title}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">评分</td>
                                        <td colspan="3">
                                            <div id="star_comment"></div>
                                            <input type="hidden" id="star_val" value="{{$comment->star}}">
                                            <input type="hidden" id="star" value="{{$comment->star}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">书评内容</td>
                                        <td colspan="3">{{$comment->content}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <h4 class="modal-title">修改</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form" class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label class="col-lg-2 col-sm-2 control-label">阅读状态</label>
                                                <div class="col-lg-10">
                                                    @for ($i = 0; $i < count($read_status); $i++)
                                                        <label class="radio-inline">
                                                            @if($book->read_status==$i)
                                                                <input type="radio" name="read_status" value="{{$i}}" checked="checked">{{$read_status[$i]}}
                                                            @else
                                                                <input type="radio" name="read_status" value="{{$i}}">{{$read_status[$i]}}
                                                            @endif
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-sm-2 control-label">评分</label>
                                                <div class="col-lg-10">
                                                    <div id="star_edit"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-sm-2 control-label">
                                                    <span>分类：</span>
                                                </label>
                                                <div class="col-lg-4">
                                                    <select id="sid" name="sid" class="form-control" onchange="showTids()" required>
                                                        <option value="">请选择分类</option>
                                                        @foreach($second_category as $c)
                                                            @if ($c->id == $book->sid)
                                                                <option value="{{$c->id}}" selected="selected">{{$c->name}}</option>
                                                            @else
                                                                <option value="{{$c->id}}">{{$c->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <select id="tid" name="tid" class="form-control" required>
                                                        @foreach($third_category as $c)
                                                            @if ($c->id == $book->tid)
                                                                <option value="{{$c->id}}" selected="selected">{{$c->name}}</option>
                                                            @else
                                                                <option value="{{$c->id}}">{{$c->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-sm-2 control-label">标签</label>
                                                <div class="col-lg-10">
                                                    <input id="tags_input" type="text" class="tags" value="{{tagToInput($book->tags)}}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-sm-2 control-label">标题</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" name="title" id="title" value="{{$book->c_title}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-sm-2 control-label">评价</label>
                                                <div class="col-lg-10">
                                                    <textarea rows="6" class="form-control" name="content" id="content">{{$book->c_content}}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <button type="button" class="btn btn-primary" onclick="updateBook({{$book->bid}})">保存</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    相关书单
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            @for ($i = 0; $i < count($booklist); $i++)
                                <div class="media blog-cmnt">
                                    <a href="../booklist/my_view?blid={{$booklist[$i]->id}}" class="pull-left">

                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="../booklist/my_view?blid={{$booklist[$i]->id}}" style="color: #65CEA7;">{{$booklist[$i]->name}}</a>  {{$booklist[$i]->create_time}}
                                        </h4>
                                        <p class="mp-less">
                                            {{wrapTextToHtml($booklist[$i]->intro)}}
                                        </p>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </section>
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    推荐书籍
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            @foreach($recommend_books as $data)
                                <div class="media blog-cmnt">
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
                                        <p>{{$data->author}}&nbsp;&nbsp;&nbsp;
                                            <strong>
                                                @if($data->sid!=0)
                                                    <a href="../search/category_2?sid={{$data->sid}}" target="_blank">{{$data->sname}}</a> -
                                                    <a href="../search/category_3?tid={{$data->tid}}" target="_blank">{{$data->tname}}</a>
                                                @else
                                                    {{$data->sname}}
                                                @endif
                                            </strong></p>
                                        <p>
                                            {{$data->update_time}}<br>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
                <div class="col-sm-6">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="../note/my_list" class="btn btn-success btn-sm">我的笔记</a>
                                <a href="../note/add_view?bid={{$book->bid}}" class="btn btn-success btn-sm">写笔记</a>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    我的笔记
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            @foreach($notes as $data)
                                <div class="media blog-cmnt">
                                    <a href="../note/my_view?nid={{$data->nid}}" class="pull-left">
                                        @if(empty($data->file_url) || $data->file_type != 0)
                                            <img alt="" src="{{$data->user_pic}}" class="media-object">
                                        @else
                                            <img alt="" src="{{$data->file_url}}" class="media-object">
                                        @endif
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="../note/my_view?nid={{$data->nid}}">
                                                {{$data->title}}
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$data->create_time}}
                                        </h4>
                                        <p>
                                            {{$data->abstract}}
                                        </p>
                                        <p class="mp-less marked">{{mb_substr($data->content,0, 200)}}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>


        @include('footer')
    </div>
</section>

@include('foot-js')
<script type="text/javascript" src="{{$path}}js/rater-star/js/rater-star.js"></script>
<script type="text/javascript" src="{{$path}}js/jquery-tags-input/jquery.tagsinput.js"></script>
<script type="text/javascript" src="{{$path}}js/marked.min.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-book-my').addClass("active");

        var options_rate = {
            max : 5,
            value : $('#star_val').val(),
            enabled : false
        }
        $('#star_comment').rater(options_rate);

        var options_rate_edit = {
            max : 5,
            value : $('#star_val').val(),
            enabled : true,
            after_click	: function(ret) {
                $('#star').val(ret.number);
            }
        };
        $('#star_edit').rater(options_rate_edit);

        $('#tags_input').tagsInput({
            width:'auto'
        });

        $('.marked').each(function() {
            var p = $(this);
            var note = p.html();
            console.log(note);
            if(note.indexOf('<p stype=') == -1){
                note = note.replace('""', '');
                var html = marked(note);
                p.html(html + '...');
            }
        });


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
                        location.href = '../book/my_view?bid='+book_id;
                    }
                });
        }
    }

    function showTids(){
        var sid = $("#sid").val();
        $.get(
            "../api/book/action/getTidBySidInJson",
            {
                sid: sid
            },
            function (data) {
                var tmp = JSON.parse(data);
                if (tmp.data.length > 0) {
                    var inputs = '';
                    $.each(tmp.data, function (idx, obj) {
                        inputs += '<option value="' + obj.id + '">' + obj.name + '</option>';
                    });
                    $("#tid").html(inputs);
                } else {
                    console.log("error tids");
                }
            });
    }

    function updateBook(bid){
        var title = $('#title').val();
        var content = $('#content').val();

        // rater 不支持动态事件
        var star = $('#star').val();
        var readStatus = 0;
        var tags = $('#tags_input').val();
        $(':radio[name^="read_status"]').each(function() {
            var name = $(this).attr('name')
            if ($(this).attr('checked') == "checked") {
                readStatus = $(this).val();
            }
        });
        var sid = $('#sid').val();
        var tid = $('#tid').val();

        $.post(
            "../book/updateBookComment",
            {
                bid: bid,
                title: title,
                content: content,
                star: star,
                status: readStatus,
                tags: tags,
                sid: sid,
                tid: tid
            },
            function (data) {
                var tmp = JSON.parse(data);
                if (tmp.code == 0) {
                    location.href = '../book/my_view?bid='+tmp.bid;
                }
            });

    }

</script>
</body>
</html>
