<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>我的书单</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/rater-star/css/rater-star.css" />
    <link rel="stylesheet" type="text/css" href="{{$path}}js/bootstrap-datepicker/css/datepicker-custom.css" />
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
                <li class="active">我的书单</li>
            </ul>
        </div>

        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <div class="col-sm-4">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        我的书单
                                    </h4>
                                    <h5 class="pull-right">
                                        {{($page-1)*$page_size + 1}} - {{$page*$page_size}} / {{$list_count}}
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form id="pageForm" action="../booklist/my_list" method="post">
                                    <input type="hidden" name="p" id="p" value="{{$page}}"/>
                                </form>
                                @for ($i = 0; $i < count($list); $i++)
                                    <div class="media blog-cmnt">
                                        <a href="../booklist/my_view?blid={{$list[$i]->id}}" class="pull-left">

                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <a href="../booklist/my_view?blid={{$list[$i]->id}}" style="color: #65CEA7;">{{$list[$i]->name}}</a>  {{$list[$i]->create_time}}
                                            </h4>
                                            <p class="mp-less">
                                                {{wrapTextToHtml(mb_substr($list[$i]->intro,0, 40))}}<br>...
                                            </p>
                                            <p class="mp-less">
                                                <a href="{{$list[$i]->url}}" style="color: #428bca;" target="_blank">外链</a>
                                            </p>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div class="panel-footer text-center">
                                <ul class="pagination">
                                    <li><a href="javascript:goPage(1,{{$page_count}})">首页</a></li>
                                    <li id="last_page"><a href="javascript:goPage({{$page_count}},{{$page_count}})">末页</a></li>
                                </ul>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-6">
                        <div class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        添加书单
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form id="form_booklist" action="../booklist/add" class="form-horizontal" method="post">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单名称：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="name" name="name" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单推荐人：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="creator" name="creator" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>外链：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="url" name="url" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单书目：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <textarea id="intro" name="intro" rows="10" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            <span>书单简评：</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <textarea id="reason" name="reason" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4  control-label">创建日期</label>
                                        <div class="col-sm-7">
                                            <input id="time" name="time"
                                                   class="form-control input-medium default-date-picker" type="text" value="" required/>
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


        @include('footer')
    </div>
</section>

@include('foot-js')
<script type="text/javascript" src="{{$path}}js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-booklist-my').addClass("active");
        showPageNumber({{$page}},{{$page_count}});
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
