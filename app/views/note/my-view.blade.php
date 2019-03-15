<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>笔记详情</title>
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
                    <a href="../note/my_list">我的笔记</a>
                </li>
                <li class="active">笔记详情</li>
            </ul>
        </div>
        @include('nav-my')
        <div class="wrapper">

            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="../note/my_list" class="btn btn-success btn-sm">我的笔记</a>
                                <a href="../note/edit_view?nid={{$note->nid}}" class="btn btn-success btn-sm">编辑</a>
                                <a href="../note/add_view?bid={{$book->bid}}" class="btn btn-success btn-sm">写笔记</a>
                                @if($note->is_deleted==0)
                                    <a href="javascript:deleteNote('{{$note->nid}}')" class="btn btn-danger btn-sm">删除</a>
                                @endif
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    笔记详情
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <input type="hidden" id="note" value="{{{$note->content}}}">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td width="12%" class="td-name">书名</td>
                                        <td width="21%">
                                            <a href="../book/my_view?bid={{$book->bid}}">{{$book->bname}}</a>
                                        </td>
                                        <td width="12%"class="td-name">作者</td>
                                        <td width="21%">{{$book->author}}</td>
                                        <td width="12%" class="td-name">阅读状态</td>
                                        <td width="21%">{{$read_status[$book->read_status]}}</td>
                                    </tr>
                                    <tr>

                                    </tr>
                                    <tr>
                                        <td class="td-name">标题</td>
                                        <td>{{$note->title}}</td>
                                        <td class="td-name">笔记数</td>
                                        <td>{{$book->note_count}}</td>
                                        <td class="td-name">外链</td>
                                        <td>
                                            @if(empty($note->share_url))
                                                无
                                            @else
                                                <a href="{{$note->share_url}}">点我</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">摘要</td>
                                        <td colspan="5">{{$note->abstract}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">笔记</td>
                                        <td colspan="5" id="note_content">{{$note->content}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">附件</td>
                                        <td colspan="5">
                                    @if(empty($note->file_url))
                                        无附件
                                    @else
                                        @if($note->file_type==0)
                                            <img src="{{$note->file_url}}" width="400">
                                        @else
                                            <audio controls="controls">
                                                <source src="{{$note->file_url}}" />
                                            </audio>
                                        @endif
                                    @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">创建时间</td>
                                        <td colspan="5">{{$note->create_time}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>


        @include('footer')
    </div>
</section>

@include('foot-js')
<script type="text/javascript" src="{{$path}}js/marked.min.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-note-my').addClass("active");
        var note = $('#note').val();
        // 区分markdown 和 html
        if(note.indexOf('<p stype=') == -1){
            note = note.replace('""', '');
            var html = marked(note);
            $('#note_content').html(html);
        }
//        var note = "Hello.\n\n* This is markdown.\n* It is fun\n* Love it or leave it."
//        $('#note_content').html(marked(note));
    });

    function deleteNote(note_id){
        if(confirm("确定删除笔记吗？")){
            $.post(
                "../note/delete",
                {
                    nid: note_id,
                },
                function (data) {
                    var tmp = JSON.parse(data);
                    if (tmp.code == 0) {
                        location.href = '../note/my_list';
                    }
                });
        }
    }

</script>
</body>
</html>
