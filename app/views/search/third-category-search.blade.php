<!DOCTYPE html>
<html lang="en">
<head>
    @include('head-meta')
    <title>图书分类-{{$category_self->sname}}-{{$category_self->tname}}</title>
    @include('head-js')
    <link rel="stylesheet" type="text/css" href="{{$path}}js/rater-star/css/rater-star.css" />
    <link rel="stylesheet" type="text/css" href="{{$path}}js/jquery-tags-input/jquery.tagsinput.css" />
    <link rel="stylesheet" type="text/css" href="{{$path}}js/fuelux/css/tree-style.css" />
</head>

<body class="sticky-header">

<section>

    @include('left-side')
    <!-- left side end-->
    <div class="main-content" >
        @include('header')

        <!-- page heading start-->
        <div class="page-heading">
            <ul class="breadcrumb">
                <li>
                    <a href="../book/my_list">我的书架</a>
                </li>
                <li><a href="../search/category_2?sid={{$category_self->sid}}">{{$category_self->sname}}</a></li>
                <li class="active">{{$category_self->tname}}</li>
            </ul>
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
                                        图书分类 - {{$category_self->tname}}
                                    </h4>
                                    <h5 class="pull-right">
                                        搜索结果： {{($page-1)*$page_size + (count($books)== 0 ? 0 : 1)}} - {{($page-1)*$page_size + count($books)}} / {{$list_count}}
                                    </h5>
                                </div>
                            </header>
                            <div class="panel-body">
                                <form id="pageForm" action="../search/category_3" method="post">
                                    <input type="hidden" name="p" id="p" value="{{$page}}"/>
                                    <input type="hidden" name="tid" id="tid" value="{{$tid}}"/>
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
                                        平级分类
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div id="categoryTree" class="tree tree-plus-minus">
                                    <div class = "tree-folder" style="display:none;">
                                        <div class="tree-folder-header">
                                            <i class="fa fa-folder"></i>
                                            <div class="tree-folder-name"></div>
                                        </div>
                                        <div class="tree-folder-content"></div>
                                        <div class="tree-loader" style="display:none"></div>
                                    </div>
                                    <div class="tree-item" style="display:none;">
                                        <i class="tree-dot"></i>
                                        <div class="tree-item-name"></div>
                                    </div>
                                </div>
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
<script type="text/javascript" src="{{$path}}js/fuelux/js/tree.min.js"></script>
<script type="text/javascript" src="{{$path}}js/fuelux/js/datasourceTree.js"></script>
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

        // 分类树
        var treeDataSource = new TreeDataSource({
            data: {{$tree_data}}
        });

        $('#categoryTree').tree({
            dataSource: treeDataSource,
            loadingHTML: '<img src="{{$path}}images/input-spinner.gif"/>',
        });

        $('#categoryTree').on('selected', function (evt, data) {
            var id = data.info[0].additionalParameters.id;
            if(data.info[0].type == "item"){
                location.href = '../search/category_3?tid=' + id;
            }

        });

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
                if (tmp.code == 0) {
                    location.href = '..//search/category_3?tid={{$tid}}&p={{$page}}';
                }
            });

    }

</script>
</body>
</html>
