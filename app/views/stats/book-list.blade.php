<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>阅历</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/rater-star/css/rater-star.css" />
    <link rel="stylesheet" type="text/css" href="{{$path}}js/bootstrap-datepicker/css/datepicker-custom.css" />
    <link rel="stylesheet" type="text/css" href="{{$path}}js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
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
                    <a href="../book/my_list">我的书架</a>
                </li>
                <li class="active">阅历</li>
            </ul>
        </div>

        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <div class="col-sm-12">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        高级查询
                                    </h4>
                                    <h5 class="pull-right">
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form class="form-horizontal" id="pageForm" action="../stats/read_list" method="post">
                                    <input type="hidden" name="p" id="p" value="{{$page}}"/>
                                    <input type="hidden" name="sid" id="sid" value="{{$sid}}"/>
                                    <input type="hidden" name="tid" id="tid" value="{{$tid}}"/>
                                    <input type="hidden" name="source" id="source" value="{{$source}}"/>
                                    <input type="hidden" name="status" id="status" value="{{$status}}"/>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">书名/作者/标签</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="word" id="word" value="{{{$filter['word'] or ''}}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">分类</label>
                                        <div class="col-sm-10">
                                            <ul class="pagination pagination-sm query-con">
                                                <li @if($sid == '-1') class="active" @endif>
                                                    <a href="javascript:void(0);" onclick="changeSid('-1')" >全部</a>
                                                </li>
                                                @foreach($second_category as $s)
                                                    <li @if($sid == $s->id) class="active" @endif>
                                                        <a href="javascript:void(0);" onclick="changeSid('{{$s->id}}')" >{{$s->name}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @if($sid != '-1')
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">分类</label>
                                            <div class="col-sm-10">
                                                <ul class="pagination pagination-sm query-con">
                                                    <li @if($tid == '-1') class="active" @endif>
                                                        <a href="javascript:void(0);" onclick="changeTid('-1')" >全部</a>
                                                    </li>
                                                    @foreach($third_category as $t)
                                                        <li @if($tid == $t->id) class="active" @endif>
                                                            <a href="javascript:void(0);" onclick="changeTid('{{$t->id}}')" >{{$t->name}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">阅读来源</label>
                                        <div class="col-sm-10">
                                            <ul class="pagination pagination-sm query-con">
                                                <li @if($source == '-1') class="active" @endif>
                                                    <a href="javascript:void(0);" onclick="changeSource('-1')">全部</a>
                                                </li>
                                                @foreach($source_options as $so)
                                                    <li @if($source == $so[0]) class="active" @endif>
                                                        <a href="javascript:void(0);" onclick="changeSource('{{$so[0]}}')">{{$so[1]}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">阅读状态</label>
                                        <div class="col-sm-10">
                                            <ul class="pagination pagination-sm query-con">
                                                <li @if($status == '-1') class="active" @endif>
                                                    <a href="javascript:void(0);" onclick="changeStatus('-1')">全部</a>
                                                </li>
                                                @foreach($read_status_options as $rs)
                                                    <li @if($status == $rs[0]) class="active" @endif>
                                                        <a href="javascript:void(0);" onclick="changeStatus('{{$rs[0]}}')">{{$rs[1]}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">阅读时间</label>
                                        <div class="col-sm-6">
                                            <div class="input-group input-large custom-date-range">
                                                <input type="text" class="form-control dpd1" name="start" id="start" value="{{{$filter['start'] or ''}}}">
                                                <span class="input-group-addon">To</span>
                                                <input type="text" class="form-control dpd2" name="end" id="end" value="{{{$filter['end'] or ''}}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn-sm btn-success" type="submit">检索</button>
                                            <button type="button" class="btn-sm btn-success" onclick="clearQuery()">清空</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        全部结果
                                    </h4>
                                    <h5 class="pull-right">
                                        {{($page-1)*$page_size + (count($books)== 0 ? 0 : 1)}} - {{($page-1)*$page_size + count($books)}} / {{$book_count}}
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="blog-post">
                                    <input type="hidden" id="star" value="-1">
                                    @foreach($books as $book)
                                        <div class="col-md-6 col-sm-6">
                                            <div class="panel">
                                                <div class="panel-body">
                                                    @include('item-book-view')
                                                </div>
                                            </div>
                                        </div>
                                        @include('item-book-modal')
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
                    <div class="col-sm-5">
                        <div class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        阅读品类
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div id="second_category_chart" style="width: 600px;height:400px;"></div>
                            </div>
                        </div>
                        <div class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        阅读来源
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div id="source_chart" style="width: 600px;height:400px;"></div>
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
<script type="text/javascript" src="{{$path}}js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="{{$path}}js/rater-star/js/rater-star.js"></script>
<script type="text/javascript" src="{{$path}}js/jquery-tags-input/jquery.tagsinput.js"></script>
<script type="text/javascript" src="{{$path}}js/echarts.min.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->
<script>

    $(document).ready(function() {
        $('#nav-stats').addClass("nav-active");
        $('#menu-stats-book').addClass("active");
        showPageNumber({{$page}},{{$page_count}});

        $('.dpd1').datepicker({});
        $('.dpd2').datepicker({});

        var books = new Array();
        @foreach($books as $data)
            books.push({{$data->bid}});
        @endforeach

        for(var j=0; j<books.length; j++){
            var id = books[j];
            var options_rate = {
                max : 5,
                value : $('#star_val_' + id).val(),
                enabled : true,
                after_click	: function(ret) {
                    $('#star').val(ret.number);
                }
            };
//            $('#star_'+id).rater(options_rate);
            var options_rate_view = {
                max : 5,
                value : $('#star_val_' + id).val(),
                enabled : false
            };
            $('#star_edit_'+id).rater(options_rate);
            $('#star_view_'+id).rater(options_rate_view);

            // 在modal启动时触发
            $('#myModal-'+id).on('shown.bs.modal', function (e) {
                var target = e.currentTarget.id;
                var modalId = target.replace("myModal-","");
                $('#star').val($('#star_val_' + modalId).val());
                showTids(modalId);
            });

            $('#tags_input_'+id).tagsInput({
                width:'auto'
            });
        }

        var secondChart = echarts.init(document.getElementById('second_category_chart'));
        var optionSecond = {
            title : {
                text: '阅读品类',
                subtext: '大类',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: {{$legend_data_second_category}}
            },
            series : [
                {
                    name: '阅读品类',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '50%'],
                    roseType: 'area',
                    data: {{$series_data_second_category}},
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };
        secondChart.setOption(optionSecond);

        var sourceChart = echarts.init(document.getElementById('source_chart'));
        var optionSource = {
            title : {
                text: '阅读来源',
                subtext: '来源',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: {{$legend_data_source}}
            },
            series : [
                {
                    name: '阅读来源',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data: {{$series_data_source}},
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };
        sourceChart.setOption(optionSource);
    });


    function changeSid(sid){
        $("#sid").val(sid);
        $("#tid").val('-1');
        $("#p").val('1');
        $("#pageForm").submit();
    }

    function changeTid(tid){
        $("#tid").val(tid);
        $("#p").val('1');
        $("#pageForm").submit();
    }

    function changeSource(source){
        $("#source").val(source);
        $("#p").val('1');
        $("#pageForm").submit();
    }

    function changeStatus(status){
        $("#status").val(status);
        $("#p").val('1');
        $("#pageForm").submit();
    }

    function clearQuery(){
        $("#p").val('1');
        $("#word").val('');
        $("#tid").val('-1');
        $("#sid").val('-1');
        $("#source").val('-1');
        $("#status").val('-1');
        $("#start").val('');
        $("#end").val('');
        $('.query-con li').removeClass("active");
        $('.query-con').each(function(){
            $(this).children().first().addClass("active");
        });
    }

    function updateBook(bid){
        var title = $('#title_'+bid).val();
        var content = $('#content_'+bid).val();
        var source = $('#source_'+bid).val();
        var sid = $('#sid_'+bid).val();
        var tid = $('#tid_'+bid).val();
        var star = $('#star').val();
        var readStatus = 0;
        var tags = $('#tags_input_'+bid).val();
        $(':radio[name^="read_status_"]').each(function() {
            var name = $(this).attr('name')
            if(name == ('read_status_' + bid)){
                if ($(this).attr('checked') == "checked") {
                    readStatus = $(this).val();
                }
            }
        });

        $.post(
                "../book/updateBookComment",
                {
                    bid: bid,
                    title: title,
                    content: content,
                    star: star,
                    status: readStatus,
                    source: source,
                    sid: sid,
                    tid: tid,
                    tags: tags
                },
                function (data) {
                    var tmp = JSON.parse(data);
                    if (tmp.code == 0) {
                        $("#pageForm").submit();
                    }
                });

    }


</script>
</body>
</html>
