<!DOCTYPE html>
<html lang="en">
<head>
    @include('head-meta')
    <title>编辑书单 - {{$booklist->name}}的书</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/bootstrap-datepicker/css/datepicker-custom.css" />
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
                书单 - {{$booklist->name}}的书
            </h3>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        编辑书单
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form id="form_booklist" action="../booklist/update" class="form-horizontal" method="post">
                                    <input type="hidden" id="blid" name="blid" class="form-control" value="{{$booklist->id}}"/>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单名称：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="name" name="name" class="form-control" value="{{$booklist->name}}" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单推荐人：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="creator" name="creator" class="form-control" value="{{$booklist->creator}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>外链：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="url" name="url" class="form-control" value="{{$booklist->url}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单书目：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <textarea id="intro" name="intro" rows="10" class="form-control" required>{{$booklist->intro}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单简评：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <textarea id="reason" name="reason" rows="10" class="form-control">{{$booklist->reason}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4  control-label">创建日期</label>
                                        <div class="col-sm-7">
                                            <input id="time" name="time"
                                                   class="form-control input-medium default-date-picker" type="text" value="{{$booklist->create_time}}" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-4 control-label">
                                        </div>
                                        <div class="col-sm-7 control-label">
                                            <input type="submit" class="btn btn-block btn-success" value="保存"/>
                                        </div>
                                    </div>
                                </form>
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
<script type="text/javascript" src="{{$path}}js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function() {
        $('#nav-my').addClass("active");
        $('#menu-booklist-my').addClass("active");
        $('.default-date-picker').datepicker();
        $("#form_booklist").validate({
            submitHandler:function(form){
                var name = $('#name').val();


                form.submit();
            }
        });
    });

</script>
</body>
</html>
