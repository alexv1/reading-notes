<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Joe&Alex">
    <title>学生列表</title>
    <link href="{{$path}}js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="{{$path}}js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{$path}}js/data-tables/DT_bootstrap.css" />
    @include('head-js')
</head>

<body class="sticky-header">

<section>
    @include('left-side')
    <script type="text/javascript">

    </script>
    <div class="main-content" >
        @include('header')
        <div class="page-heading">
            <ul class="breadcrumb">
                <li>
                    <a href="../dashboard/index">首页</a>
                </li>
                <li class="active">学生列表</li>
            </ul>
        </div>
        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            学生列表
                            <span class="tools pull-right">
                                <a href="javascript:;" class="fa fa-chevron-down"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <div class="adv-table">
                                <div class="clearfix">
                                    <div class="btn-group pull-right">
                                        <a role="button" href="#" class="btn btn-info"></a>
                                    </div>
                                </div>
                                <div class="space15"></div>
                                <table class="display table table-bordered table-striped" id="hidden-table-info">
                                    <thead>
                                        <tr>
                                            <th>Rendering engine</th>
                                            <th>Browser</th>
                                            <th class="hidden-phone">Platform(s)</th>
                                            <th class="hidden-phone">Engine version</th>
                                            <th class="hidden-phone">CSS grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="gradeX">
                                            <td>Trident</td>
                                            <td>Chrome 4.0</td>
                                            <td class="hidden-phone">Win 95+</td>
                                            <td class="center hidden-phone">4</td>
                                            <td class="center hidden-phone">
                                                <a href="#view-modal-1" data-toggle="modal" class="btn btn-success btn-xs">查看</a>
                                                <a href="#delete-modal-2" data-toggle="modal" class="btn btn-danger btn-xs">删除</a>
                                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view-modal-1" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                                <h4 class="modal-title">查看学生详情</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                查看详细
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-success btn-sm">Save changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="delete-modal-2" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                                <h4 class="modal-title">删除学生</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                删除学生
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger btn-sm">Ok</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="gradeC">
                                            <td>Trident</td>
                                            <td>Internet
                                                Explorer 5.0</td>
                                            <td class="hidden-phone">Win 98+</td>
                                            <td class="center hidden-phone">5</td>
                                            <td class="center hidden-phone">C</td>
                                        </tr>
                                        <tr class="gradeA">
                                            <td>Trident</td>
                                            <td>Firefox Explorer 5.5</td>
                                            <td class="hidden-phone">Ubuntu</td>
                                            <td class="center hidden-phone">5.5</td>
                                            <td class="center hidden-phone">A</td>
                                        </tr>
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
        $('#nav-').addClass("nav-active");
        $('#menu-').addClass("active");


        $('#dynamic-table').dataTable( {
            "aaSorting": [[ 0, "desc" ]]
        } );


        /*
         * Initialse DataTables, with no sorting on the 'details' column
         */
        var oTable = $('#hidden-table-info').dataTable( {
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [ 0 ] }
            ],
            "aaSorting": [[0, 'asc']]
        });


    });


</script>
<script type="text/javascript" language="javascript" src="{{$path}}js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="{{$path}}js/data-tables/DT_bootstrap.js"></script>


</body>
</html>
