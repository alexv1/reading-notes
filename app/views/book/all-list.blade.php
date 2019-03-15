<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>藏书列表</title>
    <link href="{{$path}}js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="{{$path}}js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{$path}}js/data-tables/DT_bootstrap.css" />
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
                <li class="active">藏书列表</li>
            </ul>
        </div>

        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <span class="compose-btn pull-right">
                                <a href="/add_view" class="btn btn-info btn-sm">添书</a>
                            </span>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    所有藏书
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <div class="adv-table">
                                <div class="space15"></div>
                                <table class="display table table-bordered table-striped table-hover" id="hidden-table-info">
                                    <thead>
                                        <tr align="center">
                                            <th>序号</th>
                                            <th>书名</th>
                                            <th>图片</th>
                                            <th>作者</th>
                                            <th>类型</th>
                                            <th>豆瓣评分</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @for ($i = 0; $i < count($list); $i++)
                                        <tr class="gradeX">
                                            <td>{{$i+1}}</td>
                                            <td>{{$list[$i]->bname}}</td>
                                            <td><img src="{{$list[$i]->pic_url}}" width="70"/></td>
                                            <td>{{$list[$i]->author}}</td>
                                            <td>{{$list[$i]->tid}}</td>
                                            <td>{{$list[$i]->dou_rate}}</td>
                                            <td width="20%">
                                                <a href="/view?resume_id={{$list[$i]->bid}}" class="btn btn-success btn-xs">查看</a>
                                                <a href="/edit_view?resume_id={{$list[$i]->bid}}" class="btn btn-success btn-xs">编辑</a>
                                                @if($list[$i]->is_deleted==0)
                                                    <a href="javascript:publishJob('{{$list[$i]->bid}}')" class="btn btn-success btn-xs">发布</a>
                                                @else
                                                    <a href="javascript:disableJob('{{$list[$i]->bid}}')" class="btn btn-danger btn-xs">下架</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
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
        $('#nav-setting').addClass("nav-active");
        $('#menu-book-all').addClass("active");

        updateBookCount();

        /*
         * Initialse DataTables, with no sorting on the 'details' column
         */
        var oTable = $('#hidden-table-info').dataTable( {
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [0] },
//                { "bSortable": false, "aTargets": [1] },
                { "bSortable": false, "aTargets": [2] },
                { "bSortable": false, "aTargets": [3] },
//                { "bSortable": false, "aTargets": [4] },
                { "bSortable": false, "aTargets": [5] },
                { "bSortable": false, "aTargets": [7] }
            ],
            "aaSorting": [[0, 'asc']],
            "iDisplayLength": 50
        });

    });
</script>
<script type="text/javascript" language="javascript" src="{{$path}}js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="{{$path}}js/data-tables/DT_bootstrap.js"></script>

</body>
</html>