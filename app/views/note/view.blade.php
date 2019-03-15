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
                    <a href="../note/list">笔记列表</a>
                </li>
                <li class="active">笔记详情</li>
            </ul>
        </div>

        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="../note/list" class="btn btn-success btn-sm">笔记列表</a>
                                <a href="../note/edit_view?nid={{$note->nid}}" class="btn btn-success btn-sm">编辑</a>
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
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="td-name">书名</td>
                                        <td>
                                            <a href="../book/view?bid={{$book->bid}}" target="_blank">{{$book->bname}}</a>
                                        </td>
                                        <td class="td-name">作者</td>
                                        <td>{{$book->author}}</td>
                                        <td class="td-name">阅读状态</td>
                                        <td>{{$read_status[$book->read_status]}}</td>
                                    </tr>
                                    <tr>
                                        <td width="12%" class="td-name">章节</td>
                                        <td width="21%">{{$note->chapter}}</td>
                                        <td width="12%" class="td-name">页数</td>
                                        <td width="21%">{{$note->page}}</td>
                                        <td width="12%" class="td-name">笔记数</td>
                                        <td width="21%">{{$book->note_count}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">引用</td>
                                        <td colspan="5">{{$note->refer}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-name">笔记</td>
                                        <td colspan="5">{{$note->content}}</td>
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
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-note-my').addClass("active");
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
                        location.href = '../note/list';
                    }
                });
        }
    }

</script>
</body>
</html>
