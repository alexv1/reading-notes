<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>职位列表</title>
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
                <li class="active">职位列表</li>
            </ul>
        </div>

        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <span class="compose-btn pull-right">
                                <a href="/add_view" class="btn btn-info btn-sm">添加职位</a>
                            </span>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    职位列表
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
                                            <th>职位名</th>
                                            <th>部门</th>
                                            <th>工作性质</th>
                                            <th>月薪</th>
                                            <th>工作年限</th>
                                            <th>简历数</th>
                                            <th>发布状态</th>
                                            <th>创建时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @for ($i = 0; $i < count($list); $i++)
                                        <tr class="gradeX">
                                            <td>{{$i+1}}</td>
                                            <td>{{$list[$i]->job_name}}</td>
                                            <td>{{$list[$i]->depart_name}}</td>
                                            <td>{{$nature[$list[$i]->nature]}}</td>
                                            <td>{{$list[$i]->salary_min}} - {{$list[$i]->salary_max}}</td>
                                            <td>{{$list[$i]->work_year}}</td>
                                            <td>{{$list[$i]->resume_count}}</td>
                                            <td>{{$publish_state[$list[$i]->publish_state]}}</td>
                                            <td>{{formatDayFromStr($list[$i]->created)}}</td>
                                            <td>
                                                <a href="/view?job_id={{$list[$i]->job_id}}" class="btn btn-success btn-xs">查看</a>
                                                <a href="/edit_view?job_id={{$list[$i]->job_id}}" class="btn btn-success btn-xs">编辑</a>
                                                @if($list[$i]->publish_state==0)
                                                    <a href="javascript:publishJob('{{$list[$i]->job_id}}')" class="btn btn-success btn-xs">发布</a>
                                                @else
                                                    <a href="javascript:disableJob('{{$list[$i]->job_id}}')" class="btn btn-danger btn-xs">下架</a>
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
        $('#nav-job').addClass("nav-active");
        $('#menu-job-all').addClass("active");

        /*
         * Initialse DataTables, with no sorting on the 'details' column
         */
        var oTable = $('#hidden-table-info').dataTable( {
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [0] },
//                { "bSortable": false, "aTargets": [1] },
//                { "bSortable": false, "aTargets": [2] },
//                { "bSortable": false, "aTargets": [3] },
//                { "bSortable": false, "aTargets": [4] },
                { "bSortable": false, "aTargets": [5] }
            ],
            "aaSorting": [[0, 'asc']],
            "iDisplayLength": 50
        });

    });

    function publishJob(job_id){
        if(confirm("确定发布职位吗？")){
            $.post(
                "../job/publish",
                {
                    job_id: job_id,
                },
                function (data) {
                    var tmp = JSON.parse(data);
                    if (tmp.code == 0) {
                        location.href = '/all_list';
                    }
                });
        }
    }

    function disableJob(job_id){
        if(confirm("确定下架职位吗？")){
            $.post(
                "../job/disable",
                {
                    job_id: job_id,
                },
                function (data) {
                    var tmp = JSON.parse(data);
                    if (tmp.code == 0) {
                        location.href = '/all_list';
                    }
                });
        }
    }
</script>
<script type="text/javascript" language="javascript" src="{{$path}}js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="{{$path}}js/data-tables/DT_bootstrap.js"></script>

</body>
</html>