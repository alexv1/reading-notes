<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Joe&Alex">
    <title>学生列表</title>
    <link href="{{$path}}js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
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
                    <a href="../student/list">学生列表</a>
                </li>
                <li class="active">添加学生</li>
            </ul>
        </div>

        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            添加学生
                        </header>
                        <div class="panel-body">
                            <form action="#" class="form-horizontal">
                                <input type="hidden" name="" id="" />
                                <input type="hidden" name="" id="" />
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">学生姓名：</label>
                                    <div class="col-sm-4">
                                        <p class="form-control-static">小班</p>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label class="col-sm-2 control-label">授课类型：</label>
                                    <div class="col-sm-4">
                                        <label class="radio-inline">
                                            <input type="radio" id="" name="" value="1" checked="checked">一对一
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" id="" name="" value="2">小班
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" id="stu_name">
                                    <label class="col-sm-2 control-label">
                                        <span id="stuOrBanci">学生姓名：</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" id="" name="" class="form-control" />
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" type="button" onclick="">检索</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">
                                        <span id="kechengAndKemu">课程：</span>
                                    </label>
                                    <div class="col-lg-4">
                                        <select id="" name="" class="form-control m-bot15" onchange="">
                                            <option value="0">请选择</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
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
<script src="{{$path}}js/external-dragging-calendar.js"></script>
<script>


    $(document).ready(function() {
        $('#nav-').addClass("nav-active");
        $('#menu-').addClass("active");
    });


</script>
</body>
</html>
