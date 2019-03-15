<!DOCTYPE html>
<html lang="en">
<head>
    @include('head-meta')
    <title>想读的书</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/rater-star/css/rater-star.css" />
    <link rel="stylesheet" type="text/css" href="{{$path}}js/jquery-tags-input/jquery.tagsinput.css" />
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
                我想读的书 {{$book_count}}
            </h3>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            @include('nav-my')
            <div class="directory-info-row">
                <div class="row">
                    <div class="col-md-7">
                        <div class="panel panel-success">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        想读书目
                                    </h4>
                                    <h5 class="pull-right">
                                        {{($page-1)*$page_size + (count($books)== 0 ? 0 : 1)}} - {{($page-1)*$page_size + count($books)}} / {{$book_count}}
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form id="pageForm" action="../book/wish_list" method="post">
                                    <input type="hidden" name="p" id="p" value="{{$page}}"/>
                                    <input type="hidden" id="star" value="">
                                </form>
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
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="panel panel-success">
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
                                <div id="second_category" style="width: 600px;height:400px;"></div>
                                <div id="third_category" style="width: 600px;height:400px;"></div>
                            </div>
                        </div>
                        <div class="panel panel-success">
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
                                <div id="source" style="width: 600px;height:400px;"></div>
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
<script type="text/javascript" src="{{$path}}js/rater-star/js/rater-star.js"></script>
<script type="text/javascript" src="{{$path}}js/jquery-tags-input/jquery.tagsinput.js"></script>
<script type="text/javascript" src="{{$path}}js/echarts.min.js"></script>
<script>
    $(document).ready(function() {

        $('#nav-my').addClass("active");
        $('#menu-book-my').addClass("active");
        showPageNumber({{$page}},{{$page_count}});

        var books = new Array();
        @foreach($books as $data)
            books.push({{$data->bid}});
        @endforeach

        for(var j=0; j<books.length; j++){
            var id = books[j];
            var defaultVal = $('#star_val_' + id).val();
            var options_rate = {
                max : 5,
                value : defaultVal,
                enabled : true,
                after_click	: function(ret) {
                    $('#star').val(ret.number);
                }
            };
//            $('#star_'+id).rater(options_rate);
            var options_rate_view = {
                max : 5,
                value : defaultVal,
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

        var secondChart = echarts.init(document.getElementById('second_category'));
        var optionSecond = {
            title : {
                text: '想读 - 阅读品类',
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

//        secondChart.on('legendselectchanged', function (params) {
//            // 获取点击图例的选中状态
//            var isSelected = params.selected[params.name];
//            // 在控制台中打印
//            console.log((isSelected ? '选中了' : '取消选中了') + '图例' + params.name);
//            // 打印所有图例的状态
//            console.log(params.selected);
//        });


        var thirdChart = echarts.init(document.getElementById('third_category'));
        var optionThird = {
            title : {
                text: '想读 - 阅读品类',
                subtext: '小类',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: {{$legend_data_third_category}}
            },
            series : [
                {
                    name: '阅读品类',
                    type: 'pie',
                    radius : '55%',
                    center: ['55%', '60%'],
                    roseType: 'area',
                    data: {{$series_data_third_category}},
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
        thirdChart.setOption(optionThird);

        var sourceChart = echarts.init(document.getElementById('source'));
        var optionSource = {
            title : {
                text: '想读 - 阅读来源',
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

    function updateBook(bid){
        var title = $('#title_'+bid).val();
        var content = $('#content_'+bid).val();
        var source = $('#source_'+bid).val();
        var sid = $('#sid_'+bid).val();
        var tid = $('#tid_'+bid).val();

        // rater 不支持动态事件
        var star = $('#star').val();
        var readStatus = 0;
        var p = $('#p').val();
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
                    // wish 按最近更新时间排序
                    if (tmp.code == 0) {
                        location.href = '../book/wish_list?p=' + p;
                    }
                });

    }

</script>
</body>
</html>
